<?php
// src/AppBundle/Form/RegistrationType.php

namespace AppBundle\Form;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\EmailType'), array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\RepeatedType'), array(
                'type' => LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->add('telephone', TextType::class, array('label'=>'Numéro de téléphone '))
            ->add('nom', TextType::class, array('label'=>'Nom '))
            ->add('prenom', TextType::class, array('label'=>'Prénom '))
            ->add('allergenes', EntityType::class, array(
                'class' => 'AppBundle:Allergene',
                'choice_label' => 'nomForm',
                'expanded' => true,
                'multiple' => true,
                'choice_attr' => function($val, $key, $index) {
                    if($key == "aucun")
                    {
                        return ['class' => 'unique-class', "style" => "display:none"];
                    }
                    return ['class' => 'unique-class'];
                },))
            ->add('adresse', TextType::class, array('label'=>'Adresse de votre lieu de travail '))
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}