<?php

namespace App\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class MediaService
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function originalMedia($figureMedia): ArrayCollection
    {
        $originalMedias = new ArrayCollection();

        foreach ($figureMedia as $media) {
            $originalMedias->add($media);
        }
        return $originalMedias;
    }

    public function editMedia($figureMedia)
    {
        $OriginalMedias = $this->originalMedia($figureMedia);
        foreach ($OriginalMedias as $media) {
            if (false === $figureMedia->contains($media)) {
                $this->em->remove($media);
            }
        }
    }
}
