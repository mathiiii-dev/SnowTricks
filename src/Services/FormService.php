<?php

namespace App\Services;

use App\Entity\Figure;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class FormService
{
    private $urlCheck;
    private $flashService;

    public function __construct(UrlService $urlCheck, FlashService $flashService)
    {
        $this->urlCheck = $urlCheck;
        $this->flashService = $flashService;
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

    public function checkDiscussion(string $message)
    {
        if (strlen($message) > 255) {
            $this->flashService->setFlashMessages(500, 'Le message est trop long ! (max 255 char.)');
            return false;
        }

        if (strlen($message) < 3) {
            $this->flashService->setFlashMessages(500,'Le message est trop court ! (min 3 char.)');
            return false;
        }

        return true;
    }
}
