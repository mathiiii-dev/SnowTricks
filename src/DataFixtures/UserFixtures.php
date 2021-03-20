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
        $userArray = [
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

        $pictureArray = [
            'BAwYBb8Y-400x400-1f71e964-5530-4f9a-a154-11b1ddd09289.jpg',
            '238052cb5170c6e2779a32bc9ff555be-f35da74e-82f5-4fd4-8764-2164a5625703.jpg'
        ];

        for($i = 0; $i < count($userArray); $i++){
            $user = new User();

            $user->setUsername($userArray[$i]['username']);
            $user->setEmail($userArray[$i]['email']);

            $password = $this->encoder->encodePassword($user, 'password');
            $user->setPassword($password);

            $picture = $pictureArray[array_rand($pictureArray)];
            $user->setProfilePicture($picture);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
