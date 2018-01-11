<?php

namespace FC\ReservationBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class VisiteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('dateCommande')
//            ->add('prix')
            ->add('dateVisite', DateType::class, array(
                'widget'    => 'single_text',
                'input'     => 'datetime',
                'format'    => 'dd/MM/yyyy',
                'invalid_message' => 'Veuillez saisir une date au format JJ/MM/AAAA.',
            ))
            ->add('nbTicket',   ChoiceType::class, array(
                'choices' => range(1, 10),
                'choice_label' => function ($choice){
                    return $choice;
                }
            ))
            ->add('demiJournee', ChoiceType::class, array(
                'choices' =>array(
                    'Journée'       => false,
                    'Demi-journée'  => true,
                )
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
