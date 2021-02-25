<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignUpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['label' => 'Pseudo', 'label_attr' => ['class' => 'mb-2 font-bold text-lg text-gray-900']])
            ->add('email', EmailType::class, ['label' => 'Email' , 'label_attr' => ['class' => 'mb-2 font-bold text-lg text-gray-900']])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe' , 'label_attr' => ['class' => 'mb-2 font-bold text-lg text-gray-900']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
