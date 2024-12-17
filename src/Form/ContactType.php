<?php

namespace App\Form;

use App\DTO\ContactDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Your name'
            ],
            'label_attr' => [
                'class' => 'form-label'
            ],
            'invalid_message' => 'Please enter a valid name'
        ])
        ->add('email', EmailType::class, [
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'your.email@example.com'
            ],
            'label_attr' => [
                'class' => 'form-label'
            ],
            'invalid_message' => 'Please enter a valid email address'
        ])
        ->add('message', TextareaType::class, [
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'rows' => 5,
                'placeholder' => 'Your message'
            ],
            'label_attr' => [
                'class' => 'form-label'
            ],
            'invalid_message' => 'Please enter your message'
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDTO::class,
        ]);
    }
}
