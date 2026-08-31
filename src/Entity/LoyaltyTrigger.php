<?php

namespace App\Entity;

use App\Repository\LoyaltyTriggerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LoyaltyTriggerRepository::class)]
class LoyaltyTrigger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $points_earned = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $related_order = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPointsEarned(): ?int
    {
        return $this->points_earned;
    }

    public function setPointsEarned(int $points_earned): static
    {
        $this->points_earned = $points_earned;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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

    public function getRelatedOrder(): ?Order
    {
        return $this->related_order;
    }

    public function setRelatedOrder(?Order $related_order): static
    {
        $this->related_order = $related_order;

        return $this;
    }
}
