<?php


namespace App\Services;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FlashService extends AbstractController
{
    public function setFlashMessages(int $httpResponse, string $successMessage)
    {

        if ($httpResponse !== 200) {
            return $this->addFlash(
                'error',
                'Une erreur est survenue !'
            );
        }
         return $this->addFlash(
            'success',
            $successMessage
        );
    }
}