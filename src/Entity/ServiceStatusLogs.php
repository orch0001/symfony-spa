<?php

namespace App\Entity;

use App\Repository\ServiceStatusLogsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource(normalizationContext: ['groups' => ['log:read']])]
#[ApiResource(
    normalizationContext: ['groups' => ['log:read']]
)]
#[ORM\Entity(repositoryClass: ServiceStatusLogsRepository::class)]
class ServiceStatusLogs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['log:read'])]
    private ?int $id = null;

    #[Groups(['log:read'])]
    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[Groups(['log:read'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $checkedAt = null;

    #[Groups(['log:read'])]
    #[ORM\ManyToOne(inversedBy: 'serviceStatusLogs')]
    private ?Service $service = null;

    #[Groups(['log:read'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contentResponse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCheckedAt(): ?\DateTimeImmutable
    {
        return $this->checkedAt;
    }

    public function setCheckedAt(\DateTimeImmutable $checkedAt): static
    {
        $this->checkedAt = $checkedAt;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getContentResponse(): ?string
    {
        return $this->contentResponse;
    }

    public function setContentResponse(?string $contentResponse): static
    {
        $this->contentResponse = $contentResponse;

        return $this;
    }
}
