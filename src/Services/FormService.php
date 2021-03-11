<?php

namespace App\Services;

use App\Entity\Figure;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class FormService
{
    private $urlCheck;

    public function __construct(UrlService $urlCheck)
    {
        $this->urlCheck = $urlCheck;
    }

    public function checkFigure(Figure $figure, FormInterface $form): bool
    {
        if ($figure->getPictures()->isEmpty()) {
            $form->get('pictures')->addError(new FormError('Une image, au moins, est necessaire.'));
            return false;
        }

        if (!$this->urlCheck->checkImageUrl($figure)) {
            $form->get('pictures')->addError(new FormError('Les liens données ne sont pas des images.'));
            return false;
        }

        if (!$this->urlCheck->checkVideoUrl($figure)) {
            $form->get('videos')->addError(new FormError('Les vidéos données ne proviennent pas de Youtube.'));
            return false;
        }
        return true;

    }
}
