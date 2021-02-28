<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class FlashService
{
    /** @var FlashBagInterface */
    private $flashBagInterface;

    public function __construct(FlashBagInterface $flashBagInterface)
    {
        $this->flashBagInterface = $flashBagInterface;
    }

    public function setFlashMessages(int $httpResponse, string $successMessage): void
    {
        if ($httpResponse !== 200) {
            $this->flashBagInterface->add(
                'error',
                'Une erreur est survenue !'
            );

            return;
        }

         $this->flashBagInterface->add(
            'success',
            $successMessage
        );
    }
}
