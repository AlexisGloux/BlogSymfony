<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('title', null, [
                'required' => true
            ])
            ->add('body', TextareaType::class, [
                'required' => true
            ]);

        // Gestion du formulaire suivant des options (déclarer dans configureOptions())
        if ($options['with_author']){
            $builder
                ->add('writtenBy', null, [
                    'class' => Author::class,
                    'required' => true
                ])
            ;
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'with_author' => true,
        ]);
    }

}
