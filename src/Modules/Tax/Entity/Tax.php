<?php

declare(strict_types=1);

namespace App\Modules\Tax\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'tax')]
#[ORM\HasLifecycleCallbacks()]
class Tax
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $country = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $code = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $codeNumber = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    protected ?int $percent = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCodeNumber(): ?string
    {
        return $this->codeNumber;
    }

    public function setCodeNumber(?string $codeNumber): self
    {
        $this->codeNumber = $codeNumber;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPercent(): ?int
    {
        return $this->percent;
    }

    public function setPercent(?int $percent): self
    {
        $this->percent = $percent;

        return $this;
    }
}
