<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', Texttype::class, [
                'empty_data' => "",
                'required' => false
            ])
            ->add('lastname', Texttype::class, [
                'empty_data' => "",
                'required' => false
            ])
            ->add('email', EmailType::class, [
                'empty_data' => "",
                'required' => false
            ])
            ->add('password', PasswordType::class, [
                ])
            ->add('save', SubmitType::class, [
                'label' => "Ajouter"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
