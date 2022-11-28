<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FicheFraisController extends AbstractController
{
    #[Route('/fichefrais', name: 'app_fiche_frais')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(FicheFrais::class);
        $fichesfrais = $repository->findAll();
        return $this->render('fiche_frais/index.html.twig', [
            'fichesfrais' => $fichesfrais,
        ]);
    }
}
