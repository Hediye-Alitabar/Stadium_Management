<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TicketPurchaseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', IntegerType::class, [
                'label' => 'form.Number_of_Tickets',
                'constraints' => [
                    new NotBlank([
                        'message' => 'form.error.quantity_not_blank',
                    ]),
                ],
                'attr' => [
                    'min' => 1,
                    'max' => $options['max_tickets'],
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'max_tickets' => 1,
        ]);
    }
}

