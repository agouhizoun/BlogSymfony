<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]
    public function allArticles(ManagerRegistry $doctrine): Response
    {
        $articles = $doctrine->getRepository(Article::class)->findAll();

        // dd($articles);

        return $this->render('article/allArticles.html.twig', [
            'articles' => $articles         
        ]);
    }

    // On crée une nlle route:

      #[Route('/ajout-article', name: 'article_ajout')]
         public function ajout(ManagerRegistry $doctrine, Request $request)
         {
            // on crée un objet article
            $article = new Article();
            // on crée le formulaire en liant le FormType à l'objet créé
            $form = $this->createForm(ArticleType::class, $article);
            // on donne accès aux données du formulaire pour la validation des données
            $form->handleRequest($request);
            // si le formulaire est soumis et valide
            if ( $form->isSubmitted() && $form->isValid())
            {
                // je m'occupe d'affecter les données manquantes (qui ne parviennent pas du formulaire)
                $article->setDateDeCreation(new DateTime("now"));
                // on récupère le manager de doctrine
                $manager = $doctrine->getManager();               
                // on persist l'objet
                $manager->persist($article);
                // puis on envoie en bdd
                $manager->flush();

                return $this->redirectToRoute("app_articles");
            }

            return $this->render("article/formulaire.html.twig", [
                'formArticle' => $form->createView()
            ]);

         }
         
               #[Route('/update-article/{id}', name: 'article_update')]
         public function update(ManagerRegistry $doctrine, $id, Request $request) // $id aura 
         // comme valeur l'id passé en paramètre de la route
         {
            // on récupère l'article dont l'id est celui passé en paramètre de la fonction
            $article = $doctrine->getRepository(Article::class)->find($id);
            // dd($article);

            // on crée le formulaire en liant le FormType à l'objet créé
            $form = $this->createForm(ArticleType::class, $article);
            // on donne accès aux données du formulaire pour la validation des données
            $form->handleRequest($request);
            // si le formulaire est soumis et valide
            if ( $form->isSubmitted() && $form->isValid())
            {
                // je m'occupe d'affecter les données manquantes (qui ne parviennent pas du formulaire)
                $article->setDateDeModification(new DateTime("now"));
                // on récupère le manager de doctrine
                $manager = $doctrine->getManager();               
                // on persist l'objet
                $manager->persist($article);
                // puis on envoie en bdd
                $manager->flush();

                return $this->redirectToRoute("app_articles");
            }

            return $this->render("article/formulaire.html.twig", [
                'formArticle' => $form->createView()
            ]);
         }

             // On crée une nlle route pour supprimer l'article:

      #[Route('/delete_article_{id}', name: 'article_delete')]
       public function delete(ManagerRegistry $doctrine, $id) // $id aura 
         // comme valeur l'id passé en paramètre de la route
         {
            // on récupère l'article à supprimer
            $article = $doctrine->getRepository(Article::class)->find($id);
            // on récupère le manager de doctrine
            $manager = $doctrine->getManager();
            // dd($article);

            // on prépare la suppression de l'article
            $manager->remove($article);
            // on exécute l'action (suppression)
            $manager->flush();

            return $this->redirectToRoute("app_articles"); 

            
            }

            // {id<\d+>} => id en accolade est un décimal

        #[Route('/article_{id<\d+>}', name: 'app_article')]
       public function showArticle(ManagerRegistry $doctrine, $id) // $id aura 
         // comme valeur l'id passé en paramètre de la route
         {
            $article = $doctrine->getRepository(Article::class)->find($id);
            // Je passe l'article à la vue
            return $this->render("article/unArticle.html.twig", [
                'article' => $article
            ]);

         }


}

