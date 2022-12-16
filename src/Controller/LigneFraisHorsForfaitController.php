<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LigneFraisHorsForfaitController extends AbstractController
{
    #[Route('/ligne/frais/hors/forfait', name: 'app_ligne_frais_hors_forfait')]
    public function index(): Response
    {
        return $this->render('ligne_frais_hors_forfait/index.html.twig', [
            'controller_name' => 'LigneFraisHorsForfaitController',
        ]);
    }
}
