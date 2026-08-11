<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $countryIso = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $order_method = null;

    #[ORM\Column(length: 255)]
    private ?string $order_type = null;

    #[ORM\Column(length: 255)]
    private ?string $order_site = null;

    #[ORM\Column]
    private ?int $order_id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?customer $customer = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountryIso(): ?string
    {
        return $this->countryIso;
    }

    public function setCountryIso(string $countryIso): static
    {
        $this->countryIso = $countryIso;

        return $this;
    }

    public function getOrderMethod(): ?string
    {
        return $this->order_method;
    }

    public function setOrderMethod(?string $order_method): static
    {
        $this->order_method = $order_method;

        return $this;
    }

    public function getOrderType(): ?string
    {
        return $this->order_type;
    }

    public function setOrderType(string $order_type): static
    {
        $this->order_type = $order_type;

        return $this;
    }

    public function getOrderSite(): ?string
    {
        return $this->order_site;
    }

    public function setOrderSite(string $order_site): static
    {
        $this->order_site = $order_site;

        return $this;
    }

    public function getOrderId(): ?int
    {
        return $this->order_id;
    }

    public function setOrderId(int $order_id): static
    {
        $this->order_id = $order_id;

        return $this;
    }

    public function getCustomer(): ?customer
    {
        return $this->customer;
    }

    public function setCustomer(?customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }
}
