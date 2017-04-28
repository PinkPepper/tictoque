<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class,
                array(
                    'choices'  =>
                        array(
                            'Tout voir'=>'all',
                            'Entrée' => 'entree',
                            'Plat' => 'plat',
                            'Dessert' => 'dessert',
                            'Boisson' => 'boisson',
                        ),
                    'expanded'=>false,
                    'multiple'=>false,
                )
            )
            ->add('categorie', EntityType::class, array(
                'class' => 'AppBundle:Categorie',
                'choice_label' => 'nom',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'type_filtre';
    }


}
