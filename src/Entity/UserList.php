<?php

namespace App\Entity;

use App\Repository\UserListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserListRepository::class)
 */
class UserList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=ListItem::class, mappedBy="list", cascade={"persist"})
     */
    private $listItems;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="lists")
     */
    private $writer;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="userLists")
     */
    private $event;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\ManyToMany(targetEntity=UserList::class)
     */
    private $otherLists;

    public function __construct()
    {
        $this->listItems = new ArrayCollection();
        $this->otherLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|ListItem[]
     */
    public function getListItems(): Collection
    {
        return $this->listItems;
    }

    public function addListItem(ListItem $listItem): self
    {
        dump('ALO');
        if (!$this->listItems->contains($listItem)) {
            $this->listItems[] = $listItem;
            $listItem->setList($this);
        }

        return $this;
    }

    public function removeListItem(ListItem $listItem): self
    {
        if ($this->listItems->removeElement($listItem)) {
            // set the owning side to null (unless already changed)
            if ($listItem->getList() === $this) {
                $listItem->setList(null);
            }
        }

        return $this;
    }

    public function getWriter(): ?User
    {
        return $this->writer;
    }

    public function setWriter(?User $writer): self
    {
        $this->writer = $writer;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return Collection|UserList[]
     */
    public function getOtherLists(): Collection
    {
        return $this->otherLists;
    }

    public function addOtherList(UserList $otherList): self
    {
        if (!$this->otherLists->contains($otherList)) {
            $this->otherLists[] = $otherList;
        }

        return $this;
    }

    public function removeOtherList(UserList $otherList): self
    {
        $this->otherLists->removeElement($otherList);

        return $this;
    }
}
