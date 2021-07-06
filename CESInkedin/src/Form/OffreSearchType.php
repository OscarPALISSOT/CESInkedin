<?php

namespace App\Form;

use App\Entity\OffreSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Titre'
                ]
            ])
            ->add('entreprise', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entreprise'
                ]
            ])
            ->add('description', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'description'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OffreSearch::class,
            'method' => 'get',
            'csrf_protection' => false,
        ]);
    }


    public function getBlockPrefix()
    {
        return '';
    }
}
