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
            ->from('resgister@example.com')
            ->to('mat.micheli99@gmail.com'/*new Address($user->getEmail())*/)
            ->subject('Snowtricks - Merci de votre inscription !')
            ->htmlTemplate('sign_up/email.html.twig')

            ->context([
                'token' => $token,
                'username' => $user->getUsername()
            ]);

        $mailer->send($email);
    }
}