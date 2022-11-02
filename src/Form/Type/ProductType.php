<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('price', TextType::class)
            ->add('brand', TextType::class)
            ->add('color', TextType::class)
            ->add('description', TextType::class)
            ->add('faceImage', TextType::class)
            ->add('profileImage', TextType::class)
            ->add('backImage', TextType::class)
            ->add('save', SubmitType::class)
        ;
    }
}