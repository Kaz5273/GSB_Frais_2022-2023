<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Entity\LigneFraisForfait;
use App\Entity\LigneFraisHorsForfait;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class DataImportsController extends AbstractController
{
    #[Route('/dataimports', name: 'app_data_imports')]
    public function index(ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $file = './visiteur.json';
        $data = file_get_contents($file);
        $users = json_decode($data);


        foreach($users as $user) {
            $newUser = new User();
            $newUser->setOldId($user->id);
            $newUser->setNom($user->nom);
            $newUser->setPrenom($user->prenom);
            $newUser->setLogin($user->login);
            $newUser->setPassword($user->mdp);
            $newUser->setAdresse($user->adresse);
            $newUser->setCp($user->cp);
            $newUser->setVille($user->ville);
            $newUser->setDateEmbauche(new \DateTime($user->dateEmbauche));


            $plaintextpassword = $user->mdp; //on stocke le mot de passe en clair dans une variable
            $hashedpassword = $passwordHasher->hashPassword($newUser, $plaintextpassword); //on hache le mot de passe
            //grace à la méthode hashPassword()
            $newUser->setPassword($hashedpassword); //j'affecte le mot de passe haché à l'attribut Password de mon objet

            //Faire persister l'objet créé = l'enregistrer en base de données gràce à l'ORM Doctrine
            $doctrine->getManager()->persist($newUser); //je fais persister l'objet $newUser en base de données
            $doctrine->getManager()->flush();
        }
        return $this->render('data_imports/index.html.twig', [
            'controller_name' => 'DataImportsController',
        ]);
    }
    #[Route('/dataimportsfichefrais', name: 'app_data_importsfichefrais')]
    public function index2(ManagerRegistry $doctrine): Response
    {


        $file = './fichefrais.json';
        $data = file_get_contents($file);
        $fichesfrais = json_decode($data);


        foreach($fichesfrais as $ficheFrais) {
            $newFicheFrais = new FicheFrais();
            $user = $doctrine->getRepository(User::class)->findOneBy(['oldId' => $ficheFrais->idVisiteur]);
            $newFicheFrais->setUser($user);
            $newFicheFrais->setMois($ficheFrais->mois);
            $newFicheFrais->setMontant($ficheFrais->montantValide);
            $newFicheFrais->setDateModif(new \DateTime($ficheFrais->dateModif));
            $newFicheFrais->setNbJustificatifs($ficheFrais->nbJustificatifs);

            switch ($ficheFrais->idEtat)
            {
                case 'CL':
                    $etat = $doctrine->getRepository(Etat::class)->find(1);
                    break;
                case 'CR':
                    $etat = $doctrine->getRepository(Etat::class)->find(2);
                    break;
                case 'RB':
                    $etat = $doctrine->getRepository(Etat::class)->find(3);
                    break;
                case 'VA':
                    $etat = $doctrine->getRepository(Etat::class)->find(4);
                    break;


            }
            $newFicheFrais->setEtat($etat);
            $doctrine->getManager()->persist($newFicheFrais); //je fais persister l'objet $newUser en base de données
            $doctrine->getManager()->flush();
        }
        return $this->render('data_imports/index.html.twig', [
            'controller_name' => 'DataImportsController',
        ]);
    }

    #[Route('/dataimportslignefraisforfait', name: 'app_data_importslignefraisforfait')]
    public function index3(ManagerRegistry $doctrine): Response
    {


        $file = './lignefraisforfait.json';
        $data = file_get_contents($file);
        $lignefraisforfait = json_decode($data);


        foreach($lignefraisforfait as $LigneFraisForfait) {
            $newLigneFraisForfait = new LigneFraisForfait();
            $user = $doctrine->getRepository(User::class)->findOneBy(['oldId' => $LigneFraisForfait->idVisiteur]);
            $ficheFrais = $doctrine->getRepository(FicheFrais::class)->findOneBy(['user' => $user, 'mois' => $LigneFraisForfait->mois]);
            $newLigneFraisForfait->setFicheFrais($ficheFrais);

            switch ($LigneFraisForfait->idFraisForfait)
            {
                case 'ETP':
                    $frais = $doctrine->getRepository(FraisForfait::class)->find(1);
                    break;
                case 'KM':
                    $frais = $doctrine->getRepository(FraisForfait::class)->find(2);
                    break;
                case 'NUI':
                    $frais = $doctrine->getRepository(FraisForfait::class)->find(3);
                    break;
                case 'REP':
                    $frais = $doctrine->getRepository(FraisForfait::class)->find(4);
                    break;
            }
            $newLigneFraisForfait->setFraisForfait($frais);
            $newLigneFraisForfait->setQuantite($LigneFraisForfait->quantite);
            $doctrine->getManager()->persist($newLigneFraisForfait); //je fais persister l'objet $newUser en base de données
            $doctrine->getManager()->flush();
        }

        return $this->render('data_imports/index.html.twig', [
            'controller_name' => 'DataImportsController',
        ]);
    }

    #[Route('/dataimportslignefraishorsforfait', name: 'app_data_importslignefraisforfait')]
    public function index4(ManagerRegistry $doctrine): Response
    {


        $file = './lignefraishorsforfait.json';
        $data = file_get_contents($file);
        $lignefraishorsforfait = json_decode($data);


        foreach($lignefraishorsforfait as $LigneFraisHorsForfait) {
            $newLigneFraisHorsForfait = new LigneFraisHorsForfait();
            $user = $doctrine->getRepository(User::class)->findOneBy(['oldId' => $LigneFraisHorsForfait->idVisiteur]);
            $ficheFrais = $doctrine->getRepository(FicheFrais::class)->findOneBy(['user' => $user, 'mois' => $LigneFraisHorsForfait->mois]);
            $newLigneFraisHorsForfait->setFicheFrais($ficheFrais);
            $newLigneFraisHorsForfait->setLibelle($LigneFraisHorsForfait->libelle);
            $newLigneFraisHorsForfait->setMontant($LigneFraisHorsForfait->montant);
            $newLigneFraisHorsForfait->setDate(new \DateTime($LigneFraisHorsForfait->date));

            $doctrine->getManager()->persist($newLigneFraisHorsForfait); //je fais persister l'objet $newUser en base de données
            $doctrine->getManager()->flush();

        }

        return $this->render('data_imports/index.html.twig', [
            'controller_name' => 'DataImportsController',
        ]);
    }
//
//
//
//    #[Route('/dataimportsfraisforfait', name: 'app_data_importsfraisforfait')]
//    public function index5(ManagerRegistry $doctrine): Response
//    {
//
//
//        $file = './fraisforfait.json';
//        $data = file_get_contents($file);
//        $fraisforfait = json_decode($data);
//
//
//        foreach($fraisforfait as $FraisForfait) {
//           $newFraisForfait = new FraisForfait();
//
//           $newFraisForfait->setLibelle($FraisForfait->libelle);
//           $newFraisForfait->setMontant($FraisForfait->montant);
//            var_dump($newFraisForfait);
//        }
//
//        return $this->render('data_imports/index.html.twig', [
//            'controller_name' => 'DataImportsController',
//        ]);
//    }
}
