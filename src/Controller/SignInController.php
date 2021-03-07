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
    private $user;

    public function __construct(EntityManagerInterface $entityManager, MailService $mail, FlashService $flash, UserRepository $user)
    {
        $this->entityManager = $entityManager;
        $this->mail = $mail;
        $this->flash = $flash;
        $this->user = $user;
    }

    /**
     * @Route("/sign-in", name="snowtricks_signin")
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/forgot-password", name="snowtricks_forgotpass")
     * @return Response
     */
    public function forgotPassword(Request $request, UserRepository $user, MailerInterface $mailer): Response
    {
        if ($request->getMethod() === Request::METHOD_POST){
            $username = $request->request->get('username');
            $currentUser = $this->user->findOneBy(['username' => $username]);

            if(!$user){
                throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
            }

            $uuid = Uuid::v4();
            $currentUser->setToken($uuid);
            $this->entityManager->persist($currentUser);
            $this->entityManager->flush();

            $this->mail->sendMailResetPassword($mailer, $currentUser);

            $this->flash->setFlashMessages(http_response_code(), 'Un lien de réinitialisation vous a été envoyé par mail !');
        }
        return $this->render('security/forgotpass.html.twig');
    }

    /**
     * @Route("/reset-password/{username}/{token}", name="snowtricks_resetpass")
     * @return Response
     */
    public function resetPassword($username, $token, Request $request, UserRepository $user, UserPasswordEncoderInterface $encoder): Response
    {
        if ($request->getMethod() === Request::METHOD_POST){
            $password = $request->request->get('password');

            $currentUser = $this->user->findOneBy([
                'username' => $username,
                'token' => $token
            ]);

            if(!$user){
                throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
            }

            $hash = $encoder->encodePassword($currentUser, $password);
            $currentUser->setPassword($hash);
            $currentUser->setToken(null);

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
