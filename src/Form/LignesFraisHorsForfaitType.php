<?php

namespace App\Form;

use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LignesFraisHorsForfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('libelle', TextType::class, [
                'label' => 'Libelle',
                'attr' => [
                    'placeholder' => 'Frais etape',
                    'size' => '70'
                ],

            ])
            ->add('montant', NumberType::class, [
                'label' => 'Montant',
                'attr' => [
                    'placeholder' => 'précisez le montant',
                    'size' => '30'
                ],

            ])
            ->add('date', DateType::class, [
                'label' => 'Date de modification',
                'attr' => [
                    'placeholder' => 'Précisez la date du dernier bilan ou noter NC pour non connu',
                    'size' => '50'
                ],
            ])
            ->add('add', SubmitType::class, [
                'label' => 'Ajouter une ligne frais hors forfait',
                'attr' => [
                    'class' => 'save, btn btn-primary',

                ]
            ])



        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
