<?php

namespace App\Form;

use App\Entity\FicheFrais;
use IntlDateFormatter;
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
                'choice_label' =>function ($choice){
                    $dataObj = \DateTime::createFromFormat('Ym', $choice);
                    $fmt = datefmt_create(
                        'fr_FR',
                        IntlDateFormatter::FULL,
                        IntlDateFormatter::FULL,
                        'Europe/Paris',
                        IntlDateFormatter::GREGORIAN,
                        'MMMM yyyy'
                    );
                        return datefmt_format($fmt, $dataObj);
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
