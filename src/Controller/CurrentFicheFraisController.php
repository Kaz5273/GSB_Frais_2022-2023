<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Entity\LigneFraisForfait;
use App\Entity\LigneFraisHorsForfait;
use App\Entity\User;
use App\Form\ChoiceMoisType;
use App\Form\LignesFraisHorsForfaitType;
use App\Form\LignesFraisForfaitType;
use App\Repository\LigneFraisHorsForfaitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CurrentFicheFraisController extends AbstractController
{
    #[Route('/fichefraisMois', name: 'app_fiche_frais_ListMois', methods: ['GET', 'POST'])]
    public function afficheFrais(ManagerRegistry $doctrine, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user= $this->getUser();
        $repository = $doctrine->getRepository(FicheFrais::class);
        $fichesfrais = $repository->findBy(['user' => $user]);

        foreach ($fichesfrais as $uneFicheFrais){
            $listMois[] = $uneFicheFrais->getMois();
        }
        $form = $this->createForm(ChoiceMoisType::class, $listMois, []);
        $form->handleRequest($request);
        $bool = false;
        $myFicheFrais = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $bool = true;

            $mois = $form->get('mois')->getData();
            $myFicheFrais = $repository->findOneBy(['mois' => $mois, 'user' => $user]);

        }

        return $this->render('fiche_frais/index.html.twig', [
            'fichefrais' => $myFicheFrais,
            'listMois' => $listMois,
            'form' => $form->createView(),
            'bool' => $bool

        ]);

    }

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
        $fichefrais = $repositoryFiche->findOneBy(['mois' => $mois, 'user' => $user]);
        $repoFrais = $doctrine->getRepository(FraisForfait::class);
        $fraisEtape = $repoFrais->find(1);
        $fraisKilometrique = $repoFrais->find(2);
        $fraisNuitee = $repoFrais->find(3);
        $fraisRestaurant = $repoFrais->find(4);

        //Saisie FraisForfaitise
        if($fichefrais == null){
            $fichefrais = new FicheFrais();

            $ligneFraisEtape = new LigneFraisForfait();
            $ligneFraisEtape->setQuantite(0);
            $ligneFraisEtape->setFraisForfait($fraisEtape);

            $ligneFraisKilometrique = new LigneFraisForfait();
            $ligneFraisKilometrique->setQuantite(0);
            $ligneFraisKilometrique->setFraisForfait($fraisKilometrique);

            $ligneFraisNuitee = new LigneFraisForfait();
            $ligneFraisNuitee->setQuantite(0);
            $ligneFraisNuitee->setFraisForfait($fraisNuitee);

            $ligneFraisRestaurant = new LigneFraisForfait();
            $ligneFraisRestaurant->setQuantite(0);
            $ligneFraisRestaurant->setFraisForfait($fraisRestaurant);

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

            $entityManager->persist($fichefrais);
            $entityManager->flush();
        }

        $form2 = $this->createForm(LignesFraisForfaitType::class, $fichefrais);
        $form2->setData($fichefrais);
        $form2 ->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid()) {

                $fichefrais->getLigneFraisForfaits()[0]->setQuantite($form2->get('ForfaitEtape')->getData());
                $fichefrais->getLigneFraisForfaits()[1]->setQuantite($form2->get('ForfaitKilometrique')->getData());
                $fichefrais->getLigneFraisForfaits()[2]->setQuantite($form2->get('ForfaitNuitee')->getData());
                $fichefrais->getLigneFraisForfaits()[3]->setQuantite($form2->get('ForfaitRestaurant')->getData());

            $entityManager->persist($fichefrais);
            $entityManager->flush();

            return $this->redirectToRoute('app_fiche_frais_ListMois', [], Response::HTTP_SEE_OTHER);

        }

        //Saisie LigneFraisHorsForfait
        $ligneFraisHorsForfait = new LigneFraisHorsForfait();
        $form = $this->createForm(LignesFraisHorsForfaitType::class, $ligneFraisHorsForfait);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            //Saisie LigneFraisHorsForfait
            $ligneFraisHorsForfait->setFicheFrais($fichefrais);
            $ligneFraisHorsForfait->setLibelle($form->get('libelle')->getData());
            $ligneFraisHorsForfait->setMontant($form->get('montant')->getData());
            $ligneFraisHorsForfait->setDate($form->get('date')->getData());
            $fichefrais->addLigneFraisHorsForfait($ligneFraisHorsForfait);
            $entityManager->persist($fichefrais);
            $entityManager->persist($ligneFraisHorsForfait);
            $entityManager->flush();

        }


        return $this->render('fiche_frais/new.html.twig', [

            'moisFrais' => $moisEnCours,
            'fichesFrais' => $fichefrais,
            'form' => $form,
            'form2' => $form2

        ]);
    }
    #[Route('/lignefraishorsforfait/{id}', name: 'app_ligne_frais_hors_forfait_delete', methods: ['POST', 'GET'])]
    public function delete(Request $request, LigneFraisHorsForfait $ligneFraisHorsForfait, LigneFraisHorsForfaitRepository $ligneFraisHorsForfaitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ligneFraisHorsForfait->getId(), $request->request->get('_token'))) {
            $ligneFraisHorsForfaitRepository->remove($ligneFraisHorsForfait, true);
        }

        return $this->redirectToRoute('app_saisie_fiche_frais', [], Response::HTTP_SEE_OTHER);
    }


}
