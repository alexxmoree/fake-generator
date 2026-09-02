<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private Customer $customer;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $total_amount = null;

    #[ORM\Column]
    private ?int $crm_id = null;

    #[ORM\Column(length: 255)]
    private ?string $country_iso = null;

    #[ORM\Column(length: 255)]
    private ?string $order_method = null;

    #[ORM\Column(length: 255)]
    private ?string $order_type = null;

    #[ORM\Column(length: 255)]
    private ?string $site = null;

    #[ORM\Column(length: 255)]
    private ?string $customer_first_name = null;

    #[ORM\Column(length: 255)]
    private ?string $customer_last_name = null;

    #[ORM\Column(length: 255)]
    private ?string $customer_phone = null;

    #[ORM\Column(length: 255)]
    private ?string $customer_email = null;

    /**
     * @var Collection<int, OrderItem>
     */
    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'related_order', orphanRemoval: true)]
    private Collection $order_items;

    public function __construct()
    {
        $this->order_items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getTotalAmount(): ?string
    {
        return $this->total_amount;
    }

    public function setTotalAmount(string $total_amount): static
    {
        $this->total_amount = $total_amount;

        return $this;
    }

    public function getCrmId(): ?int
    {
        return $this->crm_id;
    }

    public function setCrmId(int $crm_id): static
    {
        $this->crm_id = $crm_id;

        return $this;
    }

    public function getCountryIso(): ?string
    {
        return $this->country_iso;
    }

    public function setCountryIso(string $country_iso): static
    {
        $this->country_iso = $country_iso;

        return $this;
    }

    public function getOrderMethod(): ?string
    {
        return $this->order_method;
    }

    public function setOrderMethod(string $order_method): static
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

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(string $site): static
    {
        $this->site = $site;

        return $this;
    }

    public function getCustomerFirstName(): ?string
    {
        return $this->customer_first_name;
    }

    public function setCustomerFirstName(string $customer_first_name): static
    {
        $this->customer_first_name = $customer_first_name;

        return $this;
    }

    public function getCustomerLastName(): ?string
    {
        return $this->customer_last_name;
    }

    public function setCustomerLastName(string $customer_last_name): static
    {
        $this->customer_last_name = $customer_last_name;

        return $this;
    }

    public function getCustomerPhone(): ?string
    {
        return $this->customer_phone;
    }

    public function setCustomerPhone(string $customer_phone): static
    {
        $this->customer_phone = $customer_phone;

        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customer_email;
    }

    public function setCustomerEmail(string $customer_email): static
    {
        $this->customer_email = $customer_email;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->order_items;
    }

    public function addOrderItem(OrderItem $orderItem): static
    {
        if (!$this->order_items->contains($orderItem)) {
            $this->order_items->add($orderItem);
            $orderItem->setRelatedOrder($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): static
    {
        if ($this->order_items->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getRelatedOrder() === $this) {
                $orderItem->setRelatedOrder(null);
            }
        }

        return $this;
    }
}
