<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('teamHome', TextType::class, [
                'label' => 'form.team_home'
            ])
            ->add('teamAway', TextType::class, [
                'label' => 'form.team_away'
            ])
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'form.date'
            ])
            ->add('ticketPrice', MoneyType::class, [
                'currency' => 'USD',
                'label' => 'form.ticket_price'
            ])
            ->add('stadiumCapacity', NumberType::class, [
                'required' => false,
                'label' => 'form.stadium_capacity'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}