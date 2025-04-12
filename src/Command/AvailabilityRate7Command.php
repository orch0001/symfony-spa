<?php
namespace App\Command;


use App\Entity\Service;
use App\Entity\ServiceStatusLogs; 
use App\Entity\AvailabilityRateDays; 
use App\Repository\ServiceStatusLogsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command; 
use Symfony\Component\Console\Input\InputInterface; 
use Symfony\Component\Console\Output\OutputInterface; 
use Symfony\Component\Stopwatch\Stopwatch;


#[AsCommand(
    name: 'app:check-availability-rate-7',
    description: 'Vérifie l\'état des services.'
)]
final class AvailabilityRate7Command extends Command 
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
        $stopwatch->start('app:check-availability-rate-7');

        // Get all services
        $services = $this->entityManager->getRepository(Service::class)->findAll();
       
        foreach ($services as $service) { 

            $availabilityRateDays = new AvailabilityRateDays(); 
            $availabilityRateDays->setRate($this->entityManager->getRepository(ServiceStatusLogs::class)->getAvailabilityRateLast7Days($service->getId())); 
            $availabilityRateDays->setService($service->getName()); 
            $this->entityManager->persist($availabilityRateDays); 
        } 
    
        $this->entityManager->flush(); 
        return Command::SUCCESS; 
    } 

}