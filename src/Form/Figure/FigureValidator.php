<?php


namespace App\Form\Figure;

use Symfony\Component\Form\FormInterface;

class FigureValidator
{
    public function validator(FormInterface $form): bool
    {

        if ($form->isSubmitted() && $form->isValid()) {
            return true;
        }

        return false;
    }
}