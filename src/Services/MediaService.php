<?php

namespace App\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class MediaService
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function originalMedia($figureMedia): ArrayCollection
    {
        $originalMedias = new ArrayCollection();

        foreach ($figureMedia as $media) {
            $originalMedias->add($media);
        }
        return $originalMedias;
    }

    public function editMedia($figureMedia, $originalMedias)
    {
        foreach ($originalMedias as $media) {
            if (false === $figureMedia->contains($media)) {
                $this->entityManager->remove($media);
            }
        }
    }
}
