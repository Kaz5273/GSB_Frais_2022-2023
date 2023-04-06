<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Entity\LigneFraisForfait;
use App\Entity\LigneFraisHorsForfait;
use App\Entity\User;
use App\Form\ChoiceMoisType;
use App\Form\FicheFraisType;
use App\Form\LignesFraisHorsForfaitType;
use App\Repository\FicheFraisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/fichefrais')]
class FicheFraisController extends AbstractController
{

    #[Route('/', name: 'app_fiche_frais_index', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $repository = $doctrine->getRepository(FicheFrais::class);
        $fichesfrais = $repository->findAll();

        return $this->render('fiche_frais/listFicheFrais.html.twig', [
            'fichesfrais' => $fichesfrais,
        ]);
    }

    #[Route('/new/', name: 'app_fiche_frais_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ficheFrai = new FicheFrais();
        $form = $this->createForm(FicheFraisType::class, $ficheFrai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ficheFrai);
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

        return $this->render('fiche_frais/edit.html.twig', [
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
