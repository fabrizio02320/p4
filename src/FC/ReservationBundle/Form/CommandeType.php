<?php

namespace FC\ReservationBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('civ', ChoiceType::class, array(
                'expanded' => true,
                'multiple' => false,
                'choices' =>array(
                    'Madame'        => 'Madame',
                    'Monsieur'      => 'Monsieur',
                )
            ))
            ->add('nom')
            ->add('prenom')
            ->add('courriel', RepeatedType::class, array(
                'type' => EmailType::class,
                'first_options' => array('label' => 'Adresse email'),
                'second_options' => array('label' => 'Confirmer l\'adresse email'),
            ))
            ->add('Suivant',    SubmitType::class)
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FC\ReservationBundle\Entity\Commande'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fc_reservationbundle_commande';
    }


}
