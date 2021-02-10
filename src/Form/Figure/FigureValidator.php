<?php


namespace App\Form\Figure;

class FigureValidator
{
    public function validator($form, $figure, $em, $repository): bool
    {

        if ($form->isSubmitted() && $form->isValid())
        {
            if (!$figure->getId()) {
                $figure->setCreatedAt(new \DateTime());

                $user = $repository->findOneBy(['pseudo' => 'admin']);
                $figure->setUser($user);
            }
            else {
                $figure->setModifiedAt(new \DateTime());
            }
            $em->persist($figure);
            $em->flush();

            return true;
        }

        return false;
    }
}