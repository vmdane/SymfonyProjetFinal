<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Category;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class BookForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('datePublication', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('isbn')
            ->add('disponible')
            ->add('imageCouverture')
            ->add('authors', EntityType::class, [
                'class'        => Author::class,
                'choice_label' => function(Author $author) {
                    return $author->getPrenom() . ' ' . strtoupper($author->getNom());
                },
                'multiple'     => true,
                'expanded'     => true,    // cases à cocher
                'required'     => false,
            ])
            ->add('categorys', EntityType::class, [
                'class'        => Category::class,
                'choice_label' => 'nom',   // affiche le nom de la catégorie
                'multiple'     => true,
                'expanded'     => true,    // cases à cocher
                'required'     => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
