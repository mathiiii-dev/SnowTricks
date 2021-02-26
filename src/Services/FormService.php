<?php


namespace App\Services;


use App\Entity\Figure;

class FormService
{
    private $error;
    private $urlCheck;

    public function __construct(ErrorService $error, UrlService $urlCheck)
    {
        $this->error = $error;
        $this->urlCheck = $urlCheck;
    }

    public function checkFigure(Figure $figure, $form): bool
    {
        if ($figure->getPictures()->isEmpty()) {
            $this->error->errorForm('pictures', $form, 'Une image, au moins, est necessaire.');
            return false;
        }

        if (!$this->urlCheck->checkImageUrl($figure)) {
            $this->error->errorForm('pictures', $form, 'Les liens données ne sont pas des images.');
            return false;
        }

        if (!$this->urlCheck->checkVideoUrl($figure)) {
            $this->error->errorForm('videos', $form, 'Les vidéos données ne proviennent pas de Youtube.');
            return false;
        }
        return true;

    }
}