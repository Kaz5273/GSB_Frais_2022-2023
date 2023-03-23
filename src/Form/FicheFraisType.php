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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FicheFraisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ForfaitEtape', IntegerType::class, [
                'empty_data' => 0,
                'label' => 'Frais etape',
                'attr' => [
                    'placeholder' => 'Frais etape',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
                'mapped' => false,
            ])
            ->add('ForfaitKilometrique', IntegerType::class, [
                'empty_data' => 0,
                'label' => 'Frais kilometrique',
                'attr' => [
                    'placeholder' => 'Frais kilometrique',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
                'mapped' => false,
            ])
            ->add('ForfaitNuitee', IntegerType::class, [
                'empty_data' => 0,
                'label' => 'Frais nuitée',
                'attr' => [
                    'placeholder' => 'Frais nuitée',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
                'mapped' => false,
            ])
            ->add('ForfaitRestaurant', IntegerType::class, [
                'empty_data' => 0,
                'label' => 'Frais restaurant',
                'attr' => [
                    'placeholder' => 'Frais restaurant',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
                'mapped' => false,
            ])


//            ->add('nbJustificatifs')
//            ->add('dateModif', DateType::class, [
//                'label' => 'Date de modification',
//                'attr' => [
//                    'placeholder' => 'Précisez la date du dernier bilan ou noter NC pour non connu',
//                ],
//                'required' => false,
//                'row_attr' => [
//                    'class' => 'form-floating',
//                ],
//            ])
//            ->add('montant')
//            ->add('mois')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FicheFrais::class,
        ]);
    }
}
