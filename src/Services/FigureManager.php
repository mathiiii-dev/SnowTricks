<?php

namespace App\Services;

use App\Entity\Discussion;
use App\Entity\Figure;
use App\Entity\Picture;
use App\Entity\Report;
use App\Entity\Video;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class FigureManager
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
        $media = [
            $pictures = $this->entityManager->getRepository(Picture::class)->findBy(['figure' => $figure->getId()]),
            $videos = $this->entityManager->getRepository(Video::class)->findBy(['figure' => $figure->getId()]),
            $messages = $this->entityManager->getRepository(Discussion::class)->findBy(['figure' => $figure->getId()]),
            $reports = $this->entityManager->getRepository(Report::class)->findBy(['figure' => $figure->getId()])
        ];


        foreach ($videos as $video) {
            $this->entityManager->remove($video);
        }

        foreach ($pictures as $picture) {
            $this->entityManager->remove($picture);
        }

        foreach ($messages as $message) {
            $this->entityManager->remove($message);
        }

        foreach ($reports as $report) {
            $this->entityManager->remove($report);
        }
    }
}
