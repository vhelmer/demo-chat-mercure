<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: ["get"],
    attributes: [
        'normalization_context' => ['groups' => ['read']],
        'denormalization_context' => ['groups' => ['write']],
    ],
    mercure: true,
    order: ["createdAt" => "DESC"]
)]
class Message
{

    #[Groups(["read", "write"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[Groups(["read", "write"])]
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING)]
    public string $name;

    #[Groups(["read", "write"])]
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    public string $text;

    #[Groups(["read"])]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}