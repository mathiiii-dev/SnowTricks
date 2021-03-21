<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Picture
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Figure", cascade="persist", inversedBy="pictures")
     */
    private $figure;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\Url(message="Liens d'image uniquement")
     */
    private $link;

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * @return mixed
     */
    public function getFigure()
    {
        return $this->figure;
    }

    /**
     * @param mixed $figure
     */
    public function setFigure($figure): void
    {
        $this->figure = $figure;
    }
}