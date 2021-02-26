<?php

namespace App\Controller;

use App\Services\FlashService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SignInController extends AbstractController
{
    /**
     * @Route("/sign-in", name="snowtricks_signin")
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, FlashService $flash): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="snowtricks_logout")
     */
    public function logout()
    {

    }
}
