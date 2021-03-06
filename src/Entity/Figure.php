<?php

namespace App\Entity;

use App\Repository\FigureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AppAssert;

/**
 * @ORM\Entity(repositoryClass=FigureRepository::class)
 * @UniqueEntity(
 *     fields={"name"},
 *     message="La figure existe déjà."
 * )
 * @ORM\HasLifecycleCallbacks()
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
     * @Assert\Length(min=3, max=255, minMessage="Le nom de la figure est trop court", maxMessage="Le titre de la figure est trop long.")
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
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifiedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Picture", mappedBy="figure")
     * @AppAssert\ConstraintPicture
     */
    private $pictures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Video", mappedBy="figure")
     * @AppAssert\ConstraintVideo
     */
    private $videos;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

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

    public function getCreatedAt(): ?\DateTimeInterface
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

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modifiedAt;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setModifiedAt(): self
    {
        $this->modifiedAt = new \DateTime();

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

    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): void
    {
        $picture->setFigure($this);

        $this->pictures->add($picture);
    }

    public function removePicture(Picture $picture): void
    {
        $this->pictures->removeElement($picture);
    }

    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): void
    {
        $video->setFigure($this);

        $this->videos->add($video);
    }

    public function removeVideo(Video $video): void
    {
        $this->videos->removeElement($video);
    }

}
