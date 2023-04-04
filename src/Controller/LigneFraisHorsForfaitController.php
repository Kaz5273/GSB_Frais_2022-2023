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
}
