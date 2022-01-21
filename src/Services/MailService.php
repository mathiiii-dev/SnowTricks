<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{
    public function send(MailerInterface $mailer, User $user, string $subject, string $template): void
    {
        $email = (new TemplatedEmail())
            ->from('snowtricks@mail.com')
            ->to($user->getEmail())
            ->subject($subject)
            ->htmlTemplate($template)

            ->context([
                'token' => $user->getToken(),
                'username' => $user->getUsername()
            ]);

        $mailer->send($email);
    }
}
