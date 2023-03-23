<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Entity\LigneFraisForfait;
use App\Entity\LigneFraisHorsForfait;
use App\Form\ChoiceMoisType;
use App\Form\FicheFraisType;
use App\Repository\FicheFraisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fichefrais')]
class FicheFraisController extends AbstractController
{
    #[Route('/', name: 'app_fiche_frais_index', methods: ['GET', 'POST'])]
    public function index(ManagerRegistry $doctrine, Request $request, EntityManagerInterface $entityManager): Response
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
            $repositoryFiche = $doctrine->getRepository(FicheFrais::class);

            $mois = $form->get('mois')->getData();
            $myFicheFrais = $repositoryFiche->findOneBy(['mois' => $mois, 'user' => $user]);
            $entityManager->persist($myFicheFrais);
            $entityManager->flush();
        }

        return $this->render('fiche_frais/index.html.twig', [
            'fichefrais' => $myFicheFrais,
            'listMois' => $listMois,
            'form' => $form->createView(),
            'bool' => $bool

        ]);

    }

    #[Route('/new', name: 'app_fiche_frais_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine ): Response
    {

        $ficheFrai = new FicheFrais();
        $form = $this->createForm(FicheFraisType::class, $ficheFrai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $repository = $doctrine->getRepository(FraisForfait::class);

            $fraisEtape = $repository->findOneBy(['id' => 1]);
            $lignefraisForfaitEtape = new LigneFraisForfait();
            $lignefraisForfaitEtape->setQuantite($form->get('ForfaitEtape')->getData());
            $lignefraisForfaitEtape->setFicheFrais($ficheFrai);
            $lignefraisForfaitEtape->setFraisForfait($fraisEtape);

            $fraisKilometrique = $repository->findOneBy(['id' => 2]);
            $ligneFraisForfaitKilometrique = new LigneFraisForfait();
            $ligneFraisForfaitKilometrique->setQuantite($form->get('ForfaitKilometrique')->getData());
            $ligneFraisForfaitKilometrique->setFicheFrais($ficheFrai);
            $ligneFraisForfaitKilometrique->setFraisForfait($fraisKilometrique);

            $fraisNuitee = $repository->findOneBy(['id' => 3]);
            $ligneFraisForfaitNuitee = new LigneFraisForfait();
            $ligneFraisForfaitNuitee->setQuantite($form->get('ForfaitNuitee')->getData());
            $ligneFraisForfaitNuitee->setFicheFrais($ficheFrai);
            $ligneFraisForfaitNuitee->setFraisForfait($fraisNuitee);

            $fraisRestaurant = $repository->findOneBy(['id' => 4]);
            $ligneFraisForfaitRestaurant = new LigneFraisForfait();
            $ligneFraisForfaitRestaurant->setQuantite($form->get('ForfaitRestaurant')->getData());
            $ligneFraisForfaitRestaurant->setFicheFrais($ficheFrai);
            $ligneFraisForfaitRestaurant->setFraisForfait($fraisRestaurant);

            dd($lignefraisForfaitEtape);
            $entityManager->persist($ficheFrai);
            $entityManager->persist($lignefraisForfaitEtape);
            $entityManager->persist($ligneFraisForfaitKilometrique);
            $entityManager->persist($ligneFraisForfaitNuitee);
            $entityManager->persist($ligneFraisForfaitRestaurant);

            $entityManager->flush();

            return $this->redirectToRoute('app_fiche_frais_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('fiche_frais/new.html.twig', [
            'fiche_frai' => $ficheFrai,
            'form' => $form,

        ]);
    }

    #[Route('/{id}', name: 'app_fiche_frais_show', methods: ['GET'])]
    public function show(FicheFrais $ficheFrai): Response
    {
        return $this->render('fiche_frais/show.html.twig', [
            'fiche_frai' => $ficheFrai,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fiche_frais_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FicheFrais $ficheFrai, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FicheFraisType::class, $ficheFrai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fiche_frais_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fiche_frais/edit.html.twig', [
            'fiche_frai' => $ficheFrai,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_frais_delete', methods: ['POST'])]
    public function delete(Request $request, FicheFrais $ficheFrai, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ficheFrai->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ficheFrai);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fiche_frais_index', [], Response::HTTP_SEE_OTHER);
    }
}
