<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ServiceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $services = [
            ['name' => 'Site Wordpress', 'url' => 'https://sepia2.unil.ch/wp/', 'api' => null, 'token' => null],
            ['name' => 'Site BookStack', 'url' => 'https://wiki.unil.ch/', 'api' => null, 'token' => null],
            ['name' => 'Service Compilatio', 'url' => 'https://app.compilatio.net/api/public/alerts', 'api' => null, 'token' => null],
            ['name' => 'Média serveur', 'url' => 'https://rec.unil.ch/api/v2/info/', 'api' => null, 'token' => null],
            ['name' => 'Enregisteur', 'url' => 'https://cse.unil.ch/miris/?q=POL-A', 'api' => null, 'token' => null],
            ['name' => 'Service SOAP', 'url' => null, 'api' => 'https://cse.unil.ch/soapws/soap_server.php', 'token' => 'hJ89sdf83hf02j1MsdKf02JhQp91xZ'],
        ];

        foreach ($services as $data) {
            $service = new Service();
            $service->setName($data['name']);
            $service->setUrl($data['url']);
            $service->setApi($data['api']);
            $service->setToken($data['token']);
            $manager->persist($service);
        }

        $manager->flush();
    }
}