<?php

namespace App\Entity;

use App\Repository\FigureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FigureRepository::class)
 */
class Figure
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=5, max=255, minMessage="Le nom de la figure est trop court", maxMessage="Le titre de la figure est trop long.")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=5, minMessage="La description est trop courte (min 5 caractère).")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(min=3, max=50, minMessage="Le groupe de la figure est trop court.", maxMessage="Le groupe de la figure est trop long.")
     */
    private $figure_group;

    /**
     * @ORM\Column(type="text")
     * @Assert\Url(message="Ce champ doit être une url valide")
     */
    private $picture;

    /**
     * @Assert\Url(message="Ce champ doit être une url valide")
     * @ORM\Column(type="text", nullable=true)
     */
    private $video;

    /**
     * @ORM\Column(type="date")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $modifiedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFigureGroup(): ?string
    {
        return $this->figure_group;
    }

    public function setFigureGroup(string $figure_group): self
    {
        $this->figure_group = $figure_group;

        return $this;
    }
  
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(?\DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

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
}
