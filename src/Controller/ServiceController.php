<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Interruption;
use App\Entity\ServiceStatusLogs;
use App\Entity\AvailabilityRateHours;
use App\Entity\AvailabilityRateDays;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

class ServiceController extends AbstractController
{
    #[Route('/services', name: 'services', methods: ['GET'])]
    public function listHtml(EntityManagerInterface $em): Response
    {
        $services = $em->getRepository(Service::class)->findAll();
        $serviceStatusLogs = $em->getRepository(ServiceStatusLogs::class)->findLastSixServiceDesc();

        $availabilityRate24Hours = $em->getRepository(AvailabilityRateHours::class)->findAll();
        $availabilityRate7Days = $em->getRepository(AvailabilityRateDays::class)->findAll();

        $interruptions = $em->getRepository(Interruption::class)->findLastFiveInterruption();

        return $this->render('service/index.html.twig', [
            'serviceStatusLogs' => $serviceStatusLogs,
            'availabilityRate24Hours' => $availabilityRate24Hours,
            'availabilityRate7Days' => $availabilityRate7Days,
            'interruptions' => $interruptions,
        ]);
    }

    #[Route('/iframe', name: 'iframe-spa')]
    public function iframe(): Response
    {
        return $this->render('iframe.html.twig');

    }

    #[Route('/controller/service-status', name: 'service-status')]
    public function serviceStatus(EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $serviceStatusLogs = $em->getRepository(ServiceStatusLogs::class)->findLastSixServiceDesc();

        // Serialize the data to an array or JSON format
        $data = $serializer->normalize($serviceStatusLogs, null, ['groups' => 'log:read']); 
        // Return the response as a JsonResponse
        return new JsonResponse($data);

    }

    #[Route('/controller/availability-rate-days', name: 'vailability_rate_days')]
    public function availabilityRate7Days(EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $availabilityRate7Days = $em->getRepository(AvailabilityRateDays::class)->findAll();

        // Serialize the data to an array or JSON format
        $data = $serializer->normalize($availabilityRate7Days, null, ['groups' => 'availability_rate']); 
        // Return the response as a JsonResponse
        return new JsonResponse($data);

    }

    #[Route('/controller/availability-rate-hours', name: 'availability_rate_hours')]
    public function availabilityRate24Hours(EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $availabilityRate24Hours = $em->getRepository(AvailabilityRateHours::class)->findAll();

        // Serialize the data to an array or JSON format
        $data = $serializer->normalize($availabilityRate24Hours, null, ['groups' => 'availability_rate']); 
        
        return new JsonResponse($data);

    }

    #[Route('/controller/last-five-interruptions', name: 'last-five-interruptions')]
    public function lastFiveInterruptions(EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $interruptions = $em->getRepository(Interruption::class)->findLastFiveInterruption();

        // Serialize the data to an array or JSON format
        $data = $serializer->normalize($interruptions, null, ['groups' => 'interruption']); 
        
        return new JsonResponse($data);

    }

}