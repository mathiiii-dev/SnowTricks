<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\Entity\Picture;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FigureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $figures = [
            [
                'name' => 'Air to Fakie',
                'description' => 'Il s\'agit d\'une figure relativement simple, et plus précisément d\'un saut sans rotation qui se fait généralement dans un pipe (un U). Le rider s\'élance dans les airs et retombe dans le sens inverse.',
                'group' => 'Jump'
            ],
            [
                'name' => 'Big air',
                'description' => 'C\'est l\'une des épreuves les plus impressionnantes dans les compétitions de snow. Le rider s’élance à une vitesse folle avant de sauter sur une tremplin et de réaliser un maximum de tricks dans les airs. Le big air peut aussi faire référence au tremplin de neige duquel le snowboardeur s\'élance avant de faire ses figures.',
                'group' => 'Jump'
            ],
            [
                'name' => 'Carver',
                'description' => 'C\'est un mot qui revient souvent dans la bouche des snowboardeurs. Mais pas que, puisqu\'on parle aussi de carving en skis. Mais alors qu\'est-ce que c\'est ? Carver, c\'est tout simplement faire un virage net en se penchant et sans déraper.',
                'group' => 'Turn'
            ],
            [
                'name' => 'Jib',
                'description' => 'Le Jib (aussi appelé slide ou grind) est une pratique du snow freestyle qui consiste à glisser sur tous types de modules autres que la neige (rails, troncs d\'arbre, tables etc.)',
                'group' => 'Slide'
            ],
            [
                'name' => 'Lipslide',
                'description' => 'Le lispslide consiste à glisser sur un obstacle en mettant la planche perpendiculaire à celui-ci. Un jib à 90 degrés en d\'autres termes. Le lipslide peut se faire en avant ou en arrière. Frontside ou backside, donc.',
                'group' => 'Slide'
            ],
            [
                'name' => 'Mc Twist',
                'description' => 'Le Mc Twist est un flip (rotation verticale) agrémenté d\'une vrille. Un saut plutôt périlleux réservé aux riders les plus confirmés. Le champion Shaun White s\'est illustré par un Double Mc Twist 1260 lors de sa session de Half-Pipe aux Jeux Olympiques de Vancouver en 2010.',
                'group' => 'Flip'
            ],
            [
                'name' => 'Mc Twist',
                'description' => 'Le Mc Twist est un flip (rotation verticale) agrémenté d\'une vrille. Un saut plutôt périlleux réservé aux riders les plus confirmés. Le champion Shaun White s\'est illustré par un Double Mc Twist 1260 lors de sa session de Half-Pipe aux Jeux Olympiques de Vancouver en 2010.',
                'group' => 'Flip'
            ],
            [
                'name' => 'Rodeoback / Rodeofront',
                'description' => 'C\'est une figure qui consiste à faire un salto arrière en y ajoutant une rotation d\'un demi-tour. Le rodeo est back quand le snowboarder part de dos et front quand il part de face.',
                'group' => 'Flip'
            ],
            [
                'name' => 'Pop/poper',
                'description' => 'Il s\'agit d\'un concept assez flou qu\'il est difficile de définir. Le pop est le fait de faire décoller sa board avec un mouvement assez énergique. Certains riders ont plus de pop que d\'autres et ça se voit quand ils sautent par dessus un obstacle.',
                'group' => 'Jump'
            ],
            [
                'name' => 'Noseslide',
                'description' => 'C\'est un jib que le rider effectue sur le nose de la planche, soit la spatule qui se trouve devant lui. La spatule arrière s\'appelle le tail. Le noseslide peut être frontside ou backside.',
                'group' => 'Slide'
            ],
        ];

        $pictures = [
            'https://images.unsplash.com/photo-1478700485868-972b69dc3fc4?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format',
            'https://images.unsplash.com/photo-1518630045166-e3cbc72e3e1c?ixlib=rb-1.2.1&ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&auto=format&fit=crop&w=1307&q=80',
            'https://images.unsplash.com/photo-1522445263200-1b4b91053db8?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
            'https://images.unsplash.com/photo-1487777266562-c209de215ec2?ixlib=rb-1.2.1&ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&auto=format&fit=crop&w=1950&q=80',
            'https://images.unsplash.com/photo-1455381528837-c6d513d76c99?ixlib=rb-1.2.1&ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&auto=format&fit=crop&w=1350&q=80',
            'https://images.unsplash.com/photo-1525995049888-5b77b53751b6?ixlib=rb-1.2.1&ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&auto=format&fit=crop&w=1348&q=80'
        ];

        $videos = [
            'https://www.youtube.com/watch?v=mCYhIliI4fY',
            'https://www.youtube.com/watch?v=1TJ08caetkw',
        ];

        $users = $manager->getRepository(User::class)->findAll();

        for($i = 0; $i < count($figures); $i++){
            $figure = new Figure();
            $figure->setName($figures[$i]['name']);
            $figure->setDescription($figures[$i]['description']);
            $figure->setFigureGroup($figures[$i]['group']);

            $user = $users[array_rand($users)];
            $figure->setUser($user);

            $picture = new Picture();
            $picture->setFigure($figure);
            $pictureFigure = $pictures[array_rand($pictures)];
            $picture->setLink($pictureFigure);

            $manager->persist($picture);

            $video = new Video();
            $video->setFigure($figure);
            $videoFigure = $videos[array_rand($videos)];
            $video->setLink($videoFigure);

            $manager->persist($video);

            $manager->persist($figure);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
