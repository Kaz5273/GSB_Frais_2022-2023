<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Entity\LigneFraisForfait;
use App\Entity\LigneFraisHorsForfait;
use App\Form\LignesFraisHorsForfaitType;
use App\Form\LignesFraisForfaitType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CurrentFicheFraisController extends AbstractController
{
    #[Route('/saisie', name: 'app_saisie_fiche_frais')]
    public function saisie(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        $moisEnCours = new \DateTime('now');
        $etat = new Etat();
        $etat->getId();
        //Get fiche du mois en cours
        $user= $this->getUser();
        $repositoryFiche = $doctrine->getRepository(FicheFrais::class);
        $mois = $moisEnCours->format('Ym');
        $ficheFraisEnCours = $repositoryFiche->findOneBy(['mois' => $mois, 'user' => $user]);

        //Saisie FraisForfaitise
        if($ficheFraisEnCours == null){
            $fichefrais = new FicheFrais();

            $fichefrais->setUser($user);
            $fichefrais->setMois($mois);
            $fichefrais->setEtat($etat);
            dd($fichefrais);
        }
        $form2 = $this->createForm(LignesFraisForfaitType::class, $fichefrais);
        $form2 ->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid()) {
            //Test si la fichefrais existe
            if($ficheFraisEnCours == null){
                $repository = $doctrine->getRepository(FraisForfait::class);

                $fraisEtape = $repository->findOneBy(['id' => 1]);
                $lignefraisForfaitEtape = new LigneFraisForfait();
                $lignefraisForfaitEtape->setQuantite($form2->get('ForfaitEtape')->getData());
                $lignefraisForfaitEtape->setFicheFrais($fichefrais);
                $lignefraisForfaitEtape->setFraisForfait($fraisEtape);

                $fraisKilometrique = $repository->findOneBy(['id' => 2]);
                $ligneFraisForfaitKilometrique = new LigneFraisForfait();
                $ligneFraisForfaitKilometrique->setQuantite($form2->get('ForfaitKilometrique')->getData());
                $ligneFraisForfaitKilometrique->setFicheFrais($fichefrais);
                $ligneFraisForfaitKilometrique->setFraisForfait($fraisKilometrique);

                $fraisNuitee = $repository->findOneBy(['id' => 3]);
                $ligneFraisForfaitNuitee = new LigneFraisForfait();
                $ligneFraisForfaitNuitee->setQuantite($form2->get('ForfaitNuitee')->getData());
                $ligneFraisForfaitNuitee->setFicheFrais($fichefrais);
                $ligneFraisForfaitNuitee->setFraisForfait($fraisNuitee);

                $fraisRestaurant = $repository->findOneBy(['id' => 4]);
                $ligneFraisForfaitRestaurant = new LigneFraisForfait();
                $ligneFraisForfaitRestaurant->setQuantite($form2->get('ForfaitRestaurant')->getData());
                $ligneFraisForfaitRestaurant->setFicheFrais($fichefrais);
                $ligneFraisForfaitRestaurant->setFraisForfait($fraisRestaurant);
            }
            $entityManager->persist($lignefraisForfaitEtape);
            $entityManager->persist($ligneFraisForfaitKilometrique);
            $entityManager->persist($ligneFraisForfaitNuitee);
            $entityManager->persist($ligneFraisForfaitRestaurant);
            $entityManager->flush();

            return $this->redirectToRoute('app_fiche_frais_index', [], Response::HTTP_SEE_OTHER);

        }

        //Saisie LigneFraisHorsForfait
        $ligneFraisHorsForfait = new LigneFraisHorsForfait();
        $form = $this->createForm(LignesFraisHorsForfaitType::class, $ligneFraisHorsForfait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            //Saisie LigneFraisHorsForfait
            $ligneFraisHorsForfait->setFicheFrais($ficheFraisEnCours);
            $ligneFraisHorsForfait->setLibelle($form->get('libelle')->getData());
            $ligneFraisHorsForfait->setMontant($form->get('montant')->getData());
            $ligneFraisHorsForfait->setDate($form->get('date')->getData());

//            foreach ($ligneFraisHorsForfait as $uneLigneFraisHorsForfait){
//                $uneLigneFraisHorsForfait[] = $ligneFraisHorsForfait->getLibelle();
//            }


            $entityManager->persist($ligneFraisHorsForfait);
            $entityManager->flush();

            return $this->redirectToRoute('app_fiche_frais_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('fiche_frais/new.html.twig', [
            'ligneFraisHorsForfait' => $ligneFraisHorsForfait,
            'moisFrais' => $moisEnCours,
            'fichesFrais' => $ficheFraisEnCours,
            'form' => $form,
            'form2' => $form2

        ]);
    }
}
