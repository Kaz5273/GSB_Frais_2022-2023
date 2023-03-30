<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Entity\LigneFraisForfait;
use App\Entity\LigneFraisHorsForfait;
use App\Entity\User;
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
        $repoEtat = $doctrine->getRepository(Etat::class);
        $etat = $repoEtat->find(['id' => 2]);
        $montant = 0.00;
        $nbJustificatifs = 0;
        //Get fiche du mois en cours
        /**@var User $user
         **/
        $user= $this->getUser();
        $repositoryFiche = $doctrine->getRepository(FicheFrais::class);
        $mois = $moisEnCours->format('Ym');
        $ficheFraisEnCours = $repositoryFiche->findOneBy(['mois' => $mois, 'user' => $user]);

        //Saisie FraisForfaitise
        if($ficheFraisEnCours == null){
            $fichefrais = new FicheFrais();

            $ligneFraisEtape = new LigneFraisForfait();
            $ligneFraisEtape->setQuantite(0);

            $ligneFraisKilometrique = new LigneFraisForfait();
            $ligneFraisKilometrique->setQuantite(0);

            $ligneFraisNuitee = new LigneFraisForfait();
            $ligneFraisNuitee->setQuantite(0);

            $ligneFraisRestaurant = new LigneFraisForfait();
            $ligneFraisRestaurant->setQuantite(0);

            $fichefrais->setUser($user);
            $fichefrais->setMois($mois);
            $fichefrais->setEtat($etat);
            $fichefrais->setMontant($montant);
            $fichefrais->setNbJustificatifs($nbJustificatifs);
            $fichefrais->setDateModif($moisEnCours);
            $fichefrais->addLigneFraisForfait($ligneFraisEtape);
            $fichefrais->addLigneFraisForfait($ligneFraisKilometrique);
            $fichefrais->addLigneFraisForfait($ligneFraisNuitee);
            $fichefrais->addLigneFraisForfait($ligneFraisRestaurant);
            dd($fichefrais);
        }


        $form2 = $this->createForm(LignesFraisForfaitType::class, $ficheFraisEnCours);
        $form2 ->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid()) {
            //Test si la fichefrais existe
                $repository = $doctrine->getRepository(FraisForfait::class);

                $fraisEtape = $repository->findOneBy(['id' => 1]);
                $fichefrais->getLigneFraisForfaits()[0]->setQuantite($form2->get('ForfaitEtape'));
                $lignefraisForfaitEtape = new LigneFraisForfait();
                $lignefraisForfaitEtape->setQuantite($form2->get('ForfaitEtape')->getData());
                $fichefrais = $lignefraisForfaitEtape->setQuantite($form2->get('ForfaitEtape')->getData());
                $lignefraisForfaitEtape->setFicheFrais($ficheFraisEnCours);
                $lignefraisForfaitEtape->setFraisForfait($fraisEtape);

                $fraisKilometrique = $repository->findOneBy(['id' => 2]);
                $ligneFraisForfaitKilometrique = new LigneFraisForfait();
                $ligneFraisForfaitKilometrique->setQuantite($form2->get('ForfaitKilometrique')->getData());
                $fichefrais = $ligneFraisForfaitKilometrique->setQuantite($form2->get('ForfaitKilometrique')->getData());
                $ligneFraisForfaitKilometrique->setFicheFrais($ficheFraisEnCours);
                $ligneFraisForfaitKilometrique->setFraisForfait($fraisKilometrique);

                $fraisNuitee = $repository->findOneBy(['id' => 3]);
                $ligneFraisForfaitNuitee = new LigneFraisForfait();
                $ligneFraisForfaitNuitee->setQuantite($form2->get('ForfaitNuitee')->getData());
                $fichefrais = $ligneFraisForfaitNuitee->setQuantite($form2->get('ForfaitNuitee')->getData());
                $ligneFraisForfaitNuitee->setFicheFrais($ficheFraisEnCours);
                $ligneFraisForfaitNuitee->setFraisForfait($fraisNuitee);

                $fraisRestaurant = $repository->findOneBy(['id' => 4]);
                $ligneFraisForfaitRestaurant = new LigneFraisForfait();
                $ligneFraisForfaitRestaurant->setQuantite($form2->get('ForfaitRestaurant')->getData());
                $fichefrais = $ligneFraisForfaitRestaurant->setQuantite($form2->get('ForfaitRestaurant')->getData());
                $ligneFraisForfaitRestaurant->setFicheFrais($ficheFraisEnCours);
                $ligneFraisForfaitRestaurant->setFraisForfait($fraisRestaurant);



            $entityManager->persist($lignefraisForfaitEtape);
            $entityManager->persist($ligneFraisForfaitKilometrique);
            $entityManager->persist($ligneFraisForfaitNuitee);
            $entityManager->persist($ligneFraisForfaitRestaurant);
            $entityManager->persist($ficheFraisEnCours);
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
            $ficheFraisEnCours->addLigneFraisHorsForfait($ligneFraisHorsForfait);
            $entityManager->persist($ficheFraisEnCours);
            $entityManager->persist($ligneFraisHorsForfait);
            $entityManager->flush();

        }


        return $this->render('fiche_frais/new.html.twig', [

            'moisFrais' => $moisEnCours,
            'fichesFrais' => $ficheFraisEnCours,
            'form' => $form,
            'form2' => $form2

        ]);
    }
}
