<?php

namespace App\Form\Figure;

use App\Entity\Figure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
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
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('figure_group', TextType::class, ['label' => 'Groupe'])
            ->add('pictures', TextType::class, ['label' => 'Photo (Lien d\'image uniquement)'])
            ->add('videos', TextType::class, ['label' => 'Video (Lien YouTube uniquement)']);

        $builder->get('pictures')
            ->addModelTransformer(new CallbackTransformer(
                function ($picturesAsArray) {
                    return implode(', ', $picturesAsArray);
                },
                function ($picturesAsString) {
                    return explode(', ', $picturesAsString);
                }
            ));

        $builder->get('videos')
            ->addModelTransformer(new CallbackTransformer(
                function ($videosAsArray) {
                    return implode(', ', $videosAsArray);
                },
                function ($videosAsArray) {
                    return explode(', ', $videosAsArray);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);

    }
}
