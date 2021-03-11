<?php

namespace App\Services;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ErrorService
{
    private $templating;

    public function __construct(\Twig\Environment $templating )
    {
        $this->templating = $templating;
    }

    public function errorForm($formInput, $form, $message): Response
    {
        $form->get($formInput)->addError(new FormError($message));

        return new Response($this->templating->render('figure/formFigure.html.twig', [
            'formFigure' => $form->createView(),
            'editMode' => null
        ]));

    }

}