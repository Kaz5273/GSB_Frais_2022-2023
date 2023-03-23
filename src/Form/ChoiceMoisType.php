<?php

namespace App\Form;

use App\Entity\FicheFrais;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceMoisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $ficheFrais = $options['data'];

        $builder
            ->add('mois', ChoiceType::class, [
                'label' => 'Mois',
                'mapped' => false,
                'choices' => $ficheFrais,
                'choice_label' =>function ($choices, $key, $value){
                        return $value;
                },
                'attr' => [
                    'placeholder' => 'Mois',

                ],
                'row_attr' => [
                    'class' => 'form-floating text-center',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
