<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;



class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ref')
            ->add('title')
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Science-Fiction' => 'Science-Fiction',
                    'Mystery' => 'Mystery',
                    'Autobiography' => 'Autobiography',
                ], ])          
                 ->add('published')
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'username',
            ])
            ->add('publicationDate', DateType::class, [
                'widget' => 'choice',
                'years' => range(date('Y'), 2050),
                'months' => range(1, 12),
                'days' => range(1, 31),
            ])
            ->add('save', SubmitType::class) //2ème argument c'est le type de l'input

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
