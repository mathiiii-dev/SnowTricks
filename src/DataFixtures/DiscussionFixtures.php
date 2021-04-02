<?php

namespace App\DataFixtures;

use App\Entity\Discussion;
use App\Entity\Figure;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DiscussionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $figures = $manager->getRepository(Figure::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();

        $messages = [
            'Bonjour à tous',
            'Salut ça va ?',
            'Je commence le snowboard',
            'J\'adore cette figure',
            'La figure à l\'air compliqué',
            'Cette figure est réservé au expert',
            'Je faisais du ski avant',
            'Je trouve cette figure compliqué',
            'Je vais essayer cette figure ce week-end',
            'J\'ai réussis cette figure pour la premiere fois hier !',
        ];

        foreach($figures as $figure){
            for($i = 0; $i < rand(9, 25); $i++) {
                $discussion = new Discussion();

                $user = $users[array_rand($users)];
                $discussion->setUser($user);

                $discussion->setFigure($figure);

                $message = $messages[array_rand($messages)];
                $discussion->setMessage($message);

                $manager->persist($discussion);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            FigureFixtures::class,
        ];
    }
}
