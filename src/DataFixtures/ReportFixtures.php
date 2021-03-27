<?php

namespace App\DataFixtures;

use App\Entity\Discussion;
use App\Entity\Report;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReportFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $discussions = $manager->getRepository(Discussion::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();

        $messages = [
            'Ce message est innaproprié',
            'C\'est une insulte',
            'Le message est vulgaire',
            'Cela na rien a faire la'
        ];

        foreach($messages as $message){
                $report = new Report();

                $user = $users[array_rand($users)];
                $report->setUser($user);

                $discussion = $discussions[array_rand($discussions)];
                $report->setDiscussion($discussion);
                $report->setCreatedAt();
                $report->setMessage($message);
                $report->setFigure($discussion->getFigure());

                $manager->persist($report);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DiscussionFixtures::class,
        ];
    }

}
