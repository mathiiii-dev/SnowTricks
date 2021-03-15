<?php

namespace App\Services;

use App\Entity\Figure;
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

    public function editMedia($figureMedia, $originalMedias): void
    {
        foreach ($originalMedias as $media) {
            if (false === $figureMedia->contains($media)) {
                $this->entityManager->remove($media);
            }
        }
    }

    public function getFirstPicture($figures, $repository): array
    {
        $firstPictures = [];
        foreach ($figures as $figure) {

            $figure = $repository->find($figure->getId());

            array_push($firstPictures, $figure->getPictures()->first());

        }
        return $firstPictures;
    }

    public function removeMedia(Figure $figure): void
    {
        foreach ($figure->getVideos() as $video) {
            $this->entityManager->remove($video);
        }
        foreach ($figure->getPictures() as $picture) {
            $this->entityManager->remove($picture);
        }
    }
}
