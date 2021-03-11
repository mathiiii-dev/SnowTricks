<?php

namespace App\Entity;

use App\Repository\DiscussionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DiscussionRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Discussion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=3, max=255, minMessage="Le message est trop court. (min 3 char.)", maxMessage="Le message figure est trop long. (max 255 char.)")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    /**
     * @ORM\ManyToOne(targetEntity=Figure::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $figure;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTime();

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;


        return $this;
    }

    public function getFigure(): ?Figure
    {
        return $this->figure;
    }

    public function setFigure(Figure $figure): self
    {
        $this->figure = $figure;

        return $this;
    }
}
