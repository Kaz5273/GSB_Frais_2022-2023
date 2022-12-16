<?php

namespace App\Controller;

use App\Entity\LigneFraisHorsForfait;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LigneFraisForfaitController extends AbstractController
{
    #[Route('/lignefraisforfait', name: 'app_ligne_frais_forfait')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $repository = $doctrine->getRepository(LigneFraisHorsForfait::class);
        $lignesFraisForfait = $repository->findBy(['ficheFrais' => $user]);
        return
            $this->render('ligne_frais_forfait/index.html.twig.', [
            'lignesfraisforfait' => $lignesFraisForfait,
        ]);
    }
}
