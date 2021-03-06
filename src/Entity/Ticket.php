<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 */
class Ticket
{

    const CHILDREN_PRICE_HT = 800;
    const NORMAL_PRICE_HT = 1600;
    const SENIOR_PRICE_HT = 1200;
    const BABY_PRICE_HT = 0;
    const DISCOUNT_PERCENT = 0.1;
    const HALF_DAY_DISCOUNT_PERCENT = 0.5;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderRelation;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $discount;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="date")
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFullDay;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $country;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getDiscount(): ?bool
    {
        return $this->discount;
    }

    public function setDiscount(bool $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getOrderRelation(): ?Order
    {
        return $this->orderRelation;
    }

    public function setOrderRelation(?Order $orderRelation): self
    {
        $this->orderRelation = $orderRelation;

        return $this;
    }

    public function getIsFullDay(): ?bool
    {
        return $this->isFullDay;
    }

    public function setIsFullDay(bool $isFullDay): self
    {
        $this->isFullDay = $isFullDay;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }
}
