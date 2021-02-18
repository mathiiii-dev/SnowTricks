<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\FormValidator;
use App\Form\SignUpType;
use App\Repository\UserRepository;
use App\Services\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Uid\Uuid;

class SignUpController extends AbstractController
{
    /**
     * @Route("/sign-up", name="snowtricks_signup")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @param $mailer
     * @return Response
     */
    public function signUp(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, MailerInterface $mailer): Response
    {
        $user = new User();

        $form = $this->createForm(SignUpType::class, $user);
        $form->handleRequest($request);

        $formValidator = new FormValidator();
        if ($formValidator->validator($form)) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $uuid = Uuid::v4();
            $user->setToken($uuid);

            $em->persist($user);
            $em->flush();

            $mail = new MailService();
            $mail->send($mailer, $user);

            $this->addFlash(
                'success',
                "Inscription réussite ! Un mail vous à été envoyé pour validé votre compte. "
            );
            return $this->redirectToRoute('snowtricks_home');
        }

        return $this->render('sign_up/index.html.twig' ,[
            'formSignUp' => $form->createView() ]
        );

    }

    /**
     * @Route("/confirm-account/{pseudo}/{token}", name="snowtricks_confirmaccount")
     * @param $token
     * @param UserRepository $users
     * @param $pseudo
     * @return RedirectResponse
     */
    public function activation($token, UserRepository $users, $pseudo): RedirectResponse
    {
        $user = $users->findOneBy([
            'token' => $token,
            'username' => $pseudo
        ]);

        if(!$user){
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        $user->setToken(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Votre compte à bien été activé !'
        );

        return $this->redirectToRoute('snowtricks_signin');
    }
}
