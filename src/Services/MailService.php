<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailService
{
    public function send(MailerInterface $mailer, User $user, string $subject, string $template)
    {
        $token = $user->getToken();

        $email = (new TemplatedEmail())
            ->from('snowtricks@mail.com')
            ->to('mat.micheli99@gmail.com')
            ->subject($subject)
            ->htmlTemplate($template)

            ->context([
                'token' => $token,
                'username' => $user->getUsername()
            ]);

        $mailer->send($email);
    }
}
