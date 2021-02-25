<?php


namespace App\Form;

use Symfony\Component\Form\FormInterface;

class FormValidator
{
    public function validator(FormInterface $form): bool
    {

        if ($form->isSubmitted() && $form->isValid()) {
            return true;
        }

        return false;
    }
}