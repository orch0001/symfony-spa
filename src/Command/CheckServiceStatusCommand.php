<?php
namespace App\Command;


use App\Entity\Service;
use App\Entity\ServiceStatusLogs; 
use App\Entity\Interruption;
use App\Repository\ServiceStatusLogsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command; 
use Symfony\Component\Console\Input\InputInterface; 
use Symfony\Component\Console\Output\OutputInterface; 
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DomCrawler\Crawler;


#[AsCommand(
    name: 'app:check-service-status',
    description: 'Vérifie l\'état des services.'
)]
final class CheckServiceStatusCommand extends Command 
{ 

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ServiceStatusLogsRepository $serviceStatusLogsRepository,
        private HttpClientInterface $client,
        ) 
    { 
        parent::__construct(); 
    } 
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    { 
        $stopwatch = new Stopwatch();
        $stopwatch->start('app:check-service-status');

        // Get all services
        $services = $this->entityManager->getRepository(Service::class)->findAll();
       
        foreach ($services as $service) { 
            // HTTP request for check service
            $service->getUrl() == null ? $checkService = $this->checkServiceApi($service->getApi(), $service->getToken()) : $checkService = $this->checkServiceUrl($service->getUrl());
    
            // Add new services in database
            $serviceStatusLogs = new ServiceStatusLogs(); 
            $serviceStatusLogs->setService($service); 
            if ($checkService['status'] == 'OK') $serviceStatusLogs->setStatus('OK'); 
            if ($checkService['status']  == 'KO') $serviceStatusLogs->setStatus('KO'); 
            if ($checkService['status'] == 'problem') $serviceStatusLogs->setStatus('Problème existant'); 
            $serviceStatusLogs->setCheckedAt(new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'))); 
            $serviceStatusLogs->setContentResponse($checkService['content']); 
            $this->entityManager->persist($serviceStatusLogs); 
        } 
    
        $this->entityManager->flush(); 
        $this->checkInterruptionService();
        return Command::SUCCESS; 
    } 
    
    private function checkServiceUrl($url) 
    { 
        $response = $this->client->request(
            'GET',
            $url
        );
        $html = $response->getContent();

        $crawler = new Crawler($html);

        // wordpress siteweb
        $paragraphs = $crawler->filter('p')->each(function (Crawler $node) {
            return $node->text();
        });

        foreach ($paragraphs as $p) { 
            if ($p == 'Aucun site n’est disponible à cette adresse. Veuillez entrer l’adresse complète du site.') {
                return [
                    'status' => 'problem', 
                    'content' => $p] ;  
            }
        }
        
        // Enregisteur
        $array = $crawler->filter('table')->first();

        $headers = $crawler->filter('thead th')->each(function (Crawler $th) {
            return $th->text(); // get text of each <th>
        });

        $tdClasses = $crawler->filter('td')->each(function (Crawler $td) {
            // check if td have a class
            return $td->attr('class') ?: null; // if no class return null
        });

        $position = array_search('Enregistrement', $headers);

        if (!empty($headers)) {
            if ($tdClasses[$position] == 'offline' OR $tdClasses[$position] == NULL) 
            return [
                'status' => 'KO', 
                'content' => $tdClasses[$position]] ;  
        }

        return $response->getStatusCode() == 200 ? [
            'status' => 'OK', 
            'content' => $response->getContent(), 
            'availability_rate' => 99.99] : ['status' => 'KO']; 
    } 

    private function checkServiceApi($api, $token) 
    { 
        $response = $this->client->request('GET', $api, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);
        return $response->getStatusCode() == 200 ? [
            'status' => 'OK', 
            'content' => new JsonResponse($response)] : ['status' => 'KO']; 
    }

    private function checkInterruptionService() 
    { 
        $this->entityManager->getRepository(Interruption::class)->deleteAllInterruptions();

        $logs = $this->entityManager->getRepository(ServiceStatusLogs::class)->findAll();

        $groupes = [];
        foreach ($logs as $log) {
            $groupes[$log->getService()->getName()][] = $log;
        }
        $resultats = [];

        foreach ($groupes as $service => $events) {
            $interruption = new Interruption();
            $interruption->setService($service);
            $dernier_debut = null;
            $dernier_fin = null;
        
            for ($i = count($events) - 1; $i >= 0; $i--) {
                if ($events[$i]->getStatus() === 'OK') {
                    $dernier_fin = $events[$i]->getCheckedAt();
                }
        
                if ($events[$i]->getStatus() === 'KO' && $dernier_fin) {
                    $dernier_debut = $events[$i]->getCheckedAt();
                    break; // on a trouvé la dernière paire non fonctionnel → fonctionnel
                }

            }
        
            if ($dernier_debut && $dernier_fin) {
                $interruption->setStart($dernier_debut);
                $interruption->setEndDate($dernier_fin);
            }
            
            $this->entityManager->persist($interruption);
        }
            
            
        $this->entityManager->flush();  
        
    }
       

}