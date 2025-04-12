<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['log:read'])]
    private ?int $id = null;

    #[Groups(['log:read'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['log:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[Groups(['log:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $api = null;

    #[Groups(['log:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $token = null;

    /**
     * @var Collection<int, ServiceStatusLogs>
     */
    #[ORM\OneToMany(targetEntity: ServiceStatusLogs::class, mappedBy: 'service')]
    private Collection $serviceStatusLogs;

    public function __construct()
    {
        $this->serviceStatusLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getApi(): ?string
    {
        return $this->api;
    }

    public function setApi(?string $api): static
    {
        $this->api = $api;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return Collection<int, ServiceStatusLogs>
     */
    public function getServiceStatusLogs(): Collection
    {
        return $this->serviceStatusLogs;
    }

    public function addServiceStatusLog(ServiceStatusLogs $serviceStatusLog): static
    {
        if (!$this->serviceStatusLogs->contains($serviceStatusLog)) {
            $this->serviceStatusLogs->add($serviceStatusLog);
            $serviceStatusLog->setService($this);
        }

        return $this;
    }

    public function removeServiceStatusLog(ServiceStatusLogs $serviceStatusLog): static
    {
        if ($this->serviceStatusLogs->removeElement($serviceStatusLog)) {
            // set the owning side to null (unless already changed)
            if ($serviceStatusLog->getService() === $this) {
                $serviceStatusLog->setService(null);
            }
        }

        return $this;
    }
}
