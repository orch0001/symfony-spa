<?php
namespace App\Command;


use App\Entity\Service;
use App\Entity\ServiceStatusLogs; 
use App\Entity\AvailabilityRateHours; 
use App\Repository\ServiceStatusLogsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command; 
use Symfony\Component\Console\Input\InputInterface; 
use Symfony\Component\Console\Output\OutputInterface; 
use Symfony\Component\Stopwatch\Stopwatch;


#[AsCommand(
    name: 'app:check-availability-rate-24',
    description: 'Vérifie l\'état des services.'
)]
final class AvailabilityRate24Command extends Command 
{ 

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ServiceStatusLogsRepository $serviceStatusLogsRepository,
        ) 
    { 
        parent::__construct(); 
    } 
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    { 
        $stopwatch = new Stopwatch();
        $stopwatch->start('app:check-availability-rate-24');

        // Get all services
        $services = $this->entityManager->getRepository(Service::class)->findAll();
       
        foreach ($services as $service) { 
            // Exemple de requête HTTP pour vérifier l'état du service 
            $rate = $this->entityManager->getRepository(ServiceStatusLogs::class)->getAvailabilityRateLast24Hours($service->getId());

            $availabilityRateHours = new AvailabilityRateHours(); 
            $availabilityRateHours->setRate($this->entityManager->getRepository(ServiceStatusLogs::class)->getAvailabilityRateLast24Hours($service->getId())); 
            $availabilityRateHours->setService($service->getName()); 
            $this->entityManager->persist($availabilityRateHours); 
        } 
    
        $this->entityManager->flush(); 
        return Command::SUCCESS; 
    } 

}