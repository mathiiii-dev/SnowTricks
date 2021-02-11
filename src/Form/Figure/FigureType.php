<?php

namespace App\Form\Figure;

use App\Entity\Figure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'row_attr' => [
                    'rows' => '6'
            ]])
            ->add('figure_group', TextType::class, ['label' => 'Groupe'])
            ->add('picture', TextType::class, ['label' => 'Photo'])
            ->add('video', TextType::class, ['label' => 'Video'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }
}
