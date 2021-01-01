<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=UserList::class, mappedBy="writer")
     */
    private $lists;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="reserver")
     */
    private $reservations;

    public function __construct()
    {
        $this->lists = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|UserList[]
     */
    public function getLists(): Collection
    {
        return $this->lists;
    }

    public function addList(UserList $list): self
    {
        if (!$this->lists->contains($list)) {
            $this->lists[] = $list;
            $list->setWriter($this);
        }

        return $this;
    }

    public function removeList(UserList $list): self
    {
        if ($this->lists->removeElement($list)) {
            // set the owning side to null (unless already changed)
            if ($list->getWriter() === $this) {
                $list->setWriter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setReserver($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getReserver() === $this) {
                $reservation->setReserver(null);
            }
        }

        return $this;
    }
}
