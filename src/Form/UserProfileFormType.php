<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'form.email',
            ])
            ->add('username', TextType::class, [
                'label' => 'form.username',
            ])
            ->add('currentPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'form.current_password',
                'constraints' => [
                    new NotBlank([
                        'message' => 'form.error.current_password_not_blank',
                    ]),
                ],
                'attr' => ['autocomplete' => 'current-password'],
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => ['label' => 'form.new_password'],
                'second_options' => ['label' => 'form.confirm_new_password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'form.error.new_password_not_blank',
                    ]),
                ],
                'invalid_message' => 'form.error.password_mismatch',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}