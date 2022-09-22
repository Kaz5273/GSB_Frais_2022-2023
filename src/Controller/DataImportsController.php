<?php

namespace App\Controller;

use App\Entity\FicheFrais;
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
        $fichefrais = json_decode($data);


        foreach($fichefrais as $ficheFrais) {
            $newFicheFrais = new FicheFrais();
            $user = $doctrine->getRepository(User::class)->findOneBy(['oldId' => $ficheFrais->idVisiteur]);
            $newFicheFrais->setUser($user);
            $newFicheFrais->setMois($ficheFrais->mois);
            $newFicheFrais->setMontant($ficheFrais->montantValide);
            $newFicheFrais->setDateModif(new \DateTime($ficheFrais->dateModif));
            $newFicheFrais->setEtat($ficheFrais->idEtat);





//            $plaintextpassword = $user->mdp; //on stocke le mot de passe en clair dans une variable
//            $hashedpassword = $passwordHasher->hashPassword($newUser, $plaintextpassword); //on hache le mot de passe
//            //grace à la méthode hashPassword()
//            $newUser->setPassword($hashedpassword); //j'affecte le mot de passe haché à l'attribut Password de mon objet
//
//            //Faire persister l'objet créé = l'enregistrer en base de données gràce à l'ORM Doctrine
//            $doctrine->getManager()->persist($newUser); //je fais persister l'objet $newUser en base de données
//            $doctrine->getManager()->flush();
        }
        return $this->render('data_imports/index.html.twig', [
            'controller_name' => 'DataImportsController',
        ]);
    }
}
