<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Video
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Figure", cascade="persist", inversedBy="videos")
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
     */
    private $video;

    public function getVideo(): string
    {
        return $this->video;
    }

    public function setVideo(string $video): void
    {
        $this->video = $video;
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