<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitCreationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('description', TextareaType::class)
            ->add('type', ChoiceType::class,
                array(
                    'choices'  =>
                        array(
                            'Entrée' => 'entree',
                            'Plat' => 'plat',
                            'Dessert' => 'dessert',
                            'Boisson' => 'boisson',
                        )
                )
            )
            ->add('datePeremption', DateType::class)
            ->add('prix')
            ->add('quantite')
            ->add('pr', EntityType::class, array(
                'class' => 'AppBundle\Entity\PointRelais',
                'choice_label' => 'nom',
                'expanded' => false,
                'multiple' => true,
            ))
            ->add('file',  FileType::class)
            ->add('cat', EntityType::class, array(
                // query choices from this entity
                'class' => 'AppBundle:Categorie',
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true))
            ->add('all', EntityType::class, array(
            // query choices from this entity
            'class' => 'AppBundle:Allergene',
            'choice_label' => 'nom',
            'multiple' => true,
            'expanded' => true));
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Produit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_produit';
    }


}
