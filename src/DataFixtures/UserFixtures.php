<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Services\UploadService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

        for($i = 0; $i < count($userArray); $i++){
            $user = new User();
            
            $user->setUsername($userArray[$i]['username']);
            $user->setEmail($userArray[$i]['email']);

            $password = $this->encoder->encodePassword($user, 'password');
            $user->setPassword($password);

            $user->setProfilePicture('BAwYBb8Y-400x400-1f71e964-5530-4f9a-a154-11b1ddd09289.jpg');
            $manager->persist($user);
        }

        $manager->flush();
    }
}
