<?php

declare(strict_types=1);

namespace App\Entity;

use App\Dto\LogLineDto;
use App\Repository\LogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogRepository::class)]
class Log
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $serviceName;

    #[ORM\Column]
    private int $StatusCode;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeInterface $Date;

    public function getId(): int
    {
        return $this->id;
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function setServiceName(string $serviceName): self
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->StatusCode;
    }

    public function setStatusCode(int $StatusCode): self
    {
        $this->StatusCode = $StatusCode;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public static function hydrate(LogLineDto $logLineDto): self
    {
        $entity = new self();
        foreach (get_object_vars($logLineDto) as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists(self::class, $method)) {
                $entity->{$method}($value);
            }
        }

        return $entity;
    }
}
