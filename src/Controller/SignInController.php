<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Services\FlashService;
use App\Services\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Uid\Uuid;

class SignInController extends AbstractController
{
    private $entityManager;
    private $mail;
    private $flash;

    public function __construct(EntityManagerInterface $entityManager, MailService $mail, FlashService $flash)
    {
        $this->entityManager = $entityManager;
        $this->mail = $mail;
        $this->flash = $flash;
    }

    /**
     * @Route("/sign-in", name="snowtricks_signin")
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/forgot-password", name="snowtricks_forgotpass")
     * @return Response
     */
    public function forgotPassword(Request $request, UserRepository $users, MailerInterface $mailer): Response
    {
        if ($request->getMethod() == Request::METHOD_POST){
            $username = $request->request->get('username');
            $user = $users->findOneBy(['username' => $username]);

            if(!$user){
                throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
            }

            $uuid = Uuid::v4();
            $user->setToken($uuid);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->mail->sendMailResetPassword($mailer, $user);

            $this->flash->setFlashMessages(http_response_code(), 'Un lien de réinitialisation vous a été envoyé par mail !');

        }
        return $this->render('security/forgotpass.html.twig');
    }

    /**
     * @Route("/reset-password/{username}/{token}", name="snowtricks_resetpass")
     * @return Response
     */
    public function resetPassword($username, $token, Request $request, UserRepository $users, UserPasswordEncoderInterface $encoder): Response
    {
        if ($request->getMethod() == Request::METHOD_POST){
            $password = $request->request->get('password');

            $user = $users->findOneBy([
                'username' => $username,
                'token' => $token
            ]);

            if(!$user){
                throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
            }

            $hash = $encoder->encodePassword($user, $password);
            $user->setPassword($hash);
            $user->setToken(null);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->flash->setFlashMessages(http_response_code(), 'Mot de passe modifié !');

            return $this->redirectToRoute('snowtricks_home');
        }
        return $this->render('security/resetpass.html.twig', ['username' => $username]);
    }

    /**
     * @Route("/logout", name="snowtricks_logout")
     */
    public function logout()
    {

    }
}
