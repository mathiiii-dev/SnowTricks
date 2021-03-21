<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Services\UploadService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;
    private $upload;
    private $targetDirectory;

    public function __construct(UserPasswordEncoderInterface $encoder, UploadService $upload, $targetDirectory)
    {
        $this->encoder = $encoder;
        $this->upload = $upload;
        $this->targetDirectory = $targetDirectory;
    }

    public function load(ObjectManager $manager)
    {
        $users = [
            [
                'username' => 'Mathias',
                'email' => 'mathias.micheli@mail.com'
            ],
            [
                'username' =>'John',
                'email' =>'john@mail.com'
            ],
            [
                'username' =>'Paul',
                'email' =>'Paul@mail.com'
            ],
            [
                'username' =>'Edward',
                'email' =>'ed42@mail.comm'
            ]
        ];

        $pictures = [
            'BAwYBb8Y-400x400-1f71e964-5530-4f9a-a154-11b1ddd09289.jpg',
            '238052cb5170c6e2779a32bc9ff555be-f35da74e-82f5-4fd4-8764-2164a5625703.jpg',
            'Ew-306BVgAIZarR-a1ef5923-8505-4fda-9bcd-df3cc8ebd2ce.jpg'
        ];

        for($i = 0; $i < count($users); $i++){
            $user = new User();

            $user->setUsername($users[$i]['username']);
            $user->setEmail($users[$i]['email']);

            $password = $this->encoder->encodePassword($user, 'password');
            $user->setPassword($password);

            $picture = $pictures[array_rand($pictures)];
            $user->setProfilePictureName($picture);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
