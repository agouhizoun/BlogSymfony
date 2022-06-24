<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Form\AuteurType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuteurController extends AbstractController
{
    #[Route('/ajout-auteur', name: 'auteur_ajout')]
         public function ajout(ManagerRegistry $doctrine, Request $request): Response
         {
            // on crée un objet auteur
           
            $auteur = new Auteur();
            // on crée le formulaire en liant le FormType à l'objet créé
            // Jre vais lier mon AuteurType à l'objet que je viens de créer
            $form = $this->createForm(AuteurType::class, $auteur);
            // on donne accès aux données du formulaire pour la validation des données
            $form->handleRequest($request);
            // si le formulaire est soumis et valide
            if ( $form->isSubmitted() && $form->isValid())
            {
                // je m'occupe d'affecter les données manquantes (qui ne proviennent pas du formulaire)
                // on récupère le manager de doctrine
                $manager = $doctrine->getManager();               
                // on persist l'objet
                $manager->persist($auteur);
                // puis on envoie en bdd
                $manager->flush();

                return $this->redirectToRoute("app_auteurs");
            }

            return $this->render("auteur/formulaire.html.twig", [
                'formAuteur' => $form->createView()
            ]);

         }

             #[Route('/auteurs', name: 'app_auteurs')]
         public function allAuteurs(ManagerRegistry $doctrine, Request $request): Response
    {
        $auteurs = $doctrine->getRepository(Auteur::class)->findAll();
        // dd($auteurs);

        return $this->render('auteur/allAuteurs.html.twig', [
            'auteurs' => $auteurs         
        ]);
    }

#[Route('/auteur_update_{id<\d+>}', name: 'auteur_update')]
         public function update(ManagerRegistry $doctrine, $id, Request $request) // $id aura 
         // comme valeur l'id passé en paramètre de la route
         {
            // on récupère l'auteur dont l'id est celui passé en paramètre de la fonction
            $auteur = $doctrine->getRepository(Auteur::class)->find($id);
            // dd($auteur);

            // on crée le formulaire en liant le FormType à l'objet créé
            $form = $this->createForm(AuteurType::class, $auteur);
            // on donne accès aux données du formulaire pour la validation des données
            $form->handleRequest($request);
            // si le formulaire est soumis et valide
            if ( $form->isSubmitted() && $form->isValid())
            {
                // je m'occupe d'affecter les données manquantes (qui ne proviennent pas du formulaire)
                $auteur->setDateDeModification(new DateTime("now"));
                // on récupère le manager de doctrine
                $manager = $doctrine->getManager();               
                // on persist l'objet
                $manager->persist($auteur);
                // puis on envoie en bdd
                $manager->flush();

                return $this->redirectToRoute("app_auteurs");
            }

            return $this->render("auteur/formulaire.html.twig", [
                'formAuteur' => $form->createView()
            ]);
         }

             // On crée une nlle route pour supprimer l'auteur:

      #[Route('/auteur_delete_{id<\d+>}', name: 'auteur_delete')]
       public function delete(ManagerRegistry $doctrine, $id) // $id aura 
         // comme valeur l'id passé en paramètre de la route
         {
            // on récupère l'auteur à supprimer
            $auteur = $doctrine->getRepository(Auteur::class)->find($id);
            // on récupère le manager de doctrine
            $manager = $doctrine->getManager();
            // on prépare la suppression de l'auteur
            $manager->remove($auteur);
            // on exécute l'action (suppression)
            $manager->flush();

            return $this->redirectToRoute("app_auteurs");             
            }

            // On crée une nlle route pour un auteur:

      #[Route('/auteur_{id<\d+>}', name: 'app_auteur')]
       public function unAuteur(ManagerRegistry $doctrine, $id) // $id aura 
         // comme valeur l'id passé en paramètre de la route
         {          
            $auteur = $doctrine->getRepository(Auteur::class)->find($id);
     
            return $this->render("auteur/unAuteur.html.twig", [
                'auteur' => $auteur
            ]);  

        }


}