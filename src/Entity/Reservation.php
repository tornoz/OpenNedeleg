<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    const RESERVED = 'reserved';
    const GOT = 'got';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     */
    private $reserver;

    /**
     * @ORM\ManyToOne(targetEntity=ListItem::class, inversedBy="reservations")
     */
    private $item;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status = self::RESERVED;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReserver(): ?User
    {
        return $this->reserver;
    }

    public function setReserver(?User $reserver): self
    {
        $this->reserver = $reserver;

        return $this;
    }

    public function getItem(): ?ListItem
    {
        return $this->item;
    }

    public function setItem(?ListItem $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
