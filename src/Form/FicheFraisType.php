<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\Piece;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FicheFraisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbJustificatifs')
            ->add('dateModif')
            ->add('montant')
            ->add('mois')
            ->add('etat', EntityType::class, [
                'class' => Etat::class,
                'query_builder' => function (EntityRepository $p) {
                    return $p->createQueryBuilder('u')
                        ->orderBy('u.libelle', 'ASC');
                },
                'choice_label' => 'libelle'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FicheFrais::class,
        ]);
    }
}