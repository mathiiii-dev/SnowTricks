<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignUpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Pseudo',
                'label_attr' => [
                    'class' => 'mb-2 font-bold text-lg text-gray-900'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email' ,
                'label_attr' => [
                    'class' => 'mb-2 font-bold text-lg text-gray-900'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe' ,
                'label_attr' => [
                    'class' => 'mb-2 font-bold text-lg text-gray-900'
                ]
            ])
            ->add('profilePicture', FileType::class, [
                'label' => 'Photo de profil',
                'label_attr' => [
                    'class' => 'mb-2 font-bold text-lg text-gray-900'
                ],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Image au format jpeg, jpg, png seulement',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
