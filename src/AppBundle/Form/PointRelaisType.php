<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PointRelaisType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')
                ->add('adresse')
                ->add('user',EntityType::class, array(
                    'class' => 'AppBundle:User',
                    'query_builder' => function(EntityRepository $er){
                        return $er->createQueryBuilder('u')->where('u.roles = \'a:1:{i:0;s:12:"ROLE_LIVREUR";}\'');
                    }
                    ,'choice_label' => 'nom'
                    )
                );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PointRelais'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_pointrelais';
    }


}
