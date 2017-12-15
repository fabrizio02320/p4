<?php

namespace FC\ReservationBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tarifReduit', CheckboxType::class, array(
                'label'     => 'Cochez si tarif réduit',
                'required'  => false,
            ))
//            ->add('prix')
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('ddn', DateType::class, array(
                'widget' => 'choice',
                'days' => range(1, 31),
                'months' => range(1, 12),
                'years' => range(date('Y')-110, date('Y')),
                'format' => 'dd-MM-yyyy',
            ))
            ->add('pays', CountryType::class, array(
                'preferred_choices' => array('FR')
            ))
//            ->add('commande')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FC\ReservationBundle\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fc_reservationbundle_ticket';
    }


}
