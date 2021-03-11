<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailService
{
    public function send(MailerInterface $mailer, User $user)
    {
        $token = $user->getToken();

        $email = (new TemplatedEmail())
            ->from('snowtricks@mail.com')
            ->to('mat.micheli99@gmail.com')
            ->subject('Snowtricks - Merci de votre inscription !')
            ->htmlTemplate('sign_up/email.html.twig')

            ->context([
                'token' => $token,
                'username' => $user->getUsername()
            ]);

        $mailer->send($email);
    }

    public function sendMailResetPassword(MailerInterface $mailer, User $user)
    {
        $token = $user->getToken();

        $email = (new TemplatedEmail())
            ->from('snowtricks@mail.com')
            ->to('mat.micheli99@gmail.com')
            ->subject('Snowtricks - Réinistialisation de votre mot de passe')
            ->htmlTemplate('security/email.html.twig')

            ->context([
                'token' => $token,
                'username' => $user->getUsername()
            ]);

        $mailer->send($email);
    }
}
