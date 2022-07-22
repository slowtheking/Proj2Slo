<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('ref')
            ->add('description')

            ->add('photoForm', FileType::class, [
                "mapped" => false,
                "required" => false
            ])
            ->add('couleur')
            ->add('taille')           
            ->add('epoque')
            ->add('prix')

            ->add('categorie', EntityType::class,[
                'class'=>Categorie::class, 
                'choice_label'=> 'nom',
                'placeholder'=> 'Choix de categorie'
            ])
            
            ->add('Envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
