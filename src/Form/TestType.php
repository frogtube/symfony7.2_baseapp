<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Test;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Regex;

class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du test',
                'empty_data' => '',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir une catégorie',
            ])
            ->add('thumbnailFile', FileType::class, [
                'label' => 'Thumbnail',
                'required' => false,
            ])
            ->add('slug', HiddenType::class, [
                'required' => false,
                'constraints' => [
                    new Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                ],
                'empty_data' => '',
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->autoslug(...))
            ;
    }

    public function autoslug(PreSubmitEvent $event): void
    {
        $slugger = new AsciiSlugger();
        $data = $event->getData();
        $data['slug'] = strtolower($slugger->slug($data['name']));
        $event->setData($data);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Test::class,
        ]);
    }
}
