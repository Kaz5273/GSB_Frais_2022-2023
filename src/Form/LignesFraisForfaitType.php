<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LignesFraisForfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $fichefrais = $options['data'];
        $currentEtape = $fichefrais->getLigneFraisForfaits()[0]->getQuantite();
        $currentKilometrique = $fichefrais->getLigneFraisForfaits()[1]->getQuantite();
        $currentNuitee = $fichefrais->getLigneFraisForfaits()[2]->getQuantite();
        $currentRestaurant = $fichefrais->getLigneFraisForfaits()[3]->getQuantite();

        $builder
            ->add('ForfaitEtape', IntegerType::class, [
                'empty_data' => 0,
                'label' => 'Frais etape',
                'attr' => [
                    'placeholder' => 'Frais etape',
                    'value' => $currentEtape
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
                    'value' => $currentKilometrique
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
                    'value' => $currentNuitee
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
                    'value' => $currentRestaurant
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
                'mapped' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
