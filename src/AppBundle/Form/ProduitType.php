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

class ProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('description', TextareaType::class)
           /* ->add('allergenes', EntityType::class, array(
                // query choices from this entity
                'class' => 'AppBundle:Allergene',

                // use the User.username property as the visible option string
                'choice_label' => 'nom',

            // used to render a select box, check boxes or radios
                'multiple' => true))*/
          // ->add('allergenes')
            ->add('type', ChoiceType::class,
                array(
                    'choices'  =>
                        array(
                                'Entrée' => 'entree',
                                'Plat' => 'plat',
                                'Dessert' => 'dessert',
                        )
                    )
                )
            ->add('datePeremption', DateType::class)
            ->add('prix')
            ->add('quantite')
            ->add('file',  FileType::class);
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
