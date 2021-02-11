<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\FormValidator;
use App\Form\SignUpType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SignUpController extends AbstractController
{
    /**
     * @Route("/sign-up", name="snowtricks_signup")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function signUp(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();

        $form = $this->createForm(SignUpType::class, $user);
        $form->handleRequest($request);

        $formValidator = new FormValidator();
        if ($formValidator->validator($form)) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setRoles(['ROLE_USER']);
            $user->setCreatedAt(new \DateTime());
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                'Inscription réussite !'
            );
            return $this->redirectToRoute('snowtricks_home');
        }

        return $this->render('sign_up/index.html.twig' ,[
            'formSignUp' => $form->createView() ]
        );

    }
}
