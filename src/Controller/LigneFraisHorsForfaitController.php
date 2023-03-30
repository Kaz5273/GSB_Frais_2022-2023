<?php

namespace App\Controller;

use App\Entity\LigneFraisHorsForfait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LigneFraisHorsForfaitController extends AbstractController
{
    #[Route('/lignefraishorsforfait', name: 'app_ligne_frais_hors_forfait')]
    public function index(): Response
    {
        return $this->render('ligne_frais_hors_forfait/index.html.twig', [
            'controller_name' => 'LigneFraisHorsForfaitController',
        ]);
    }
    #[Route('/{id}', name: 'app_lignefraishorsforfait_delete', methods: ['POST', 'GET'])]
    public function delete(Request $request, LigneFraisHorsForfait $ligneFraisHorsForfait, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ligneFraisHorsForfait->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ligneFraisHorsForfait);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fiche_frais_index', [], Response::HTTP_SEE_OTHER);
    }
}
