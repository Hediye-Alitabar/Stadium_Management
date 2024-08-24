<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $teamHome = null;

    #[ORM\Column(length: 255)]
    private ?string $teamAway = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $ticketPrice = null;

    #[ORM\Column(nullable: true)]
    private ?int $stadiumCapacity = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Ticket::class, orphanRemoval: true)]
    private Collection $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeamHome(): ?string
    {
        return $this->teamHome;
    }

    public function setTeamHome(string $teamHome): static
    {
        $this->teamHome = $teamHome;

        return $this;
    }

    public function getTeamAway(): ?string
    {
        return $this->teamAway;
    }

    public function setTeamAway(string $teamAway): static
    {
        $this->teamAway = $teamAway;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTicketPrice(): ?string
    {
        return $this->ticketPrice;
    }

    public function setTicketPrice(string $ticketPrice): static
    {
        $this->ticketPrice = $ticketPrice;

        return $this;
    }

    public function getStadiumCapacity(): ?int
    {
        return $this->stadiumCapacity;
    }

    public function setStadiumCapacity(?int $stadiumCapacity): static
    {
        $this->stadiumCapacity = $stadiumCapacity;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function getSoldTicketsCount(): int
    {
        return $this->tickets->count();
    }
}
