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

class ProduitHomePageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("produit1", EntityType::class, array(
            'class' => 'AppBundle:Produit',
            'choice_label' => 'nom',
            'expanded' => false,
            'multiple' => false,
            'choice_attr' => function($val, $key, $index) {
                return ['class' => 'unique-class'];
            }
            ))
            ->add("produit2", EntityType::class, array(
                'class' => 'AppBundle:Produit',
                'choice_label' => 'nom',
                'expanded' => false,
                'multiple' => false,
                'choice_attr' => function($val, $key, $index) {
                    return ['class' => 'unique-class'];
                }
            ))
            ->add("produit3", EntityType::class, array(
                'class' => 'AppBundle:Produit',
                'choice_label' => 'nom',
                'expanded' => false,
                'multiple' => false,
                'choice_attr' => function($val, $key, $index) {
                    return ['class' => 'unique-class'];
                }
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ProduitHomePage'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_produitHomepage';
    }


}
