<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('quantite')
                ->add('date',DateType::class,array(
                    'widget' => 'single_text',
                    'html5' => false,
                    'attr' => ['class' => 'js-datepicker'],
                    'format' => 'dd/M/yyyy',
                ))
                ->add('pointRelais',EntityType::class,array(
                    'class' => 'AppBundle:PointRelais',
                    'choice_label' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                ))
                ->add('produit',EntityType::class,array(
                    'class' => 'AppBundle:Produit',
                    'choice_label' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Livraison'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_livraison';
    }


}
