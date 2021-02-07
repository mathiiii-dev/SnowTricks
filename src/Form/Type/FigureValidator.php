<?php


namespace App\Form\Type;

class FigureValidator
{
    public function validator($form, $figure, $em, $repository): bool
    {

        if ($form->isSubmitted() && $form->isValid())
        {
            $figure->setCreatedAt(new \DateTime());
            $user = $repository->findOneBy(['pseudo' => 'admin']);
            $figure->setUser($user);
            $em->persist($figure);
            $em->flush();

            return true;
        }

        return false;
    }
}