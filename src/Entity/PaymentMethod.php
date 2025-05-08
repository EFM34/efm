<?php

namespace App\Entity;

use App\Repository\PaymentMethodRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentMethodRepository::class)]
class PaymentMethod
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $more_description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $test_public_api_key = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $test_private_api_key = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $prod_public_api_key = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $prod_private_api_key = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMoreDescription(): ?string
    {
        return $this->more_description;
    }

    public function setMoreDescription(?string $more_description): static
    {
        $this->more_description = $more_description;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getTestPublicApiKey(): ?string
    {
        return $this->test_public_api_key;
    }

    public function setTestPublicApiKey(?string $test_public_api_key): static
    {
        $this->test_public_api_key = $test_public_api_key;

        return $this;
    }

    public function getTestPrivateApiKey(): ?string
    {
        return $this->test_private_api_key;
    }

    public function setTestPrivateApiKey(?string $test_private_api_key): static
    {
        $this->test_private_api_key = $test_private_api_key;

        return $this;
    }

    public function getProdPublicApiKey(): ?string
    {
        return $this->prod_public_api_key;
    }

    public function setProdPublicApiKey(?string $prod_public_api_key): static
    {
        $this->prod_public_api_key = $prod_public_api_key;

        return $this;
    }

    public function getProdPrivateApiKey(): ?string
    {
        return $this->prod_private_api_key;
    }

    public function setProdPrivateApiKey(?string $prod_private_api_key): static
    {
        $this->prod_private_api_key = $prod_private_api_key;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
