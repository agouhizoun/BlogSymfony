<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    // #[Route('/home', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        // on cherche le dernier article inséré en base de données 
        // en utilisant le repository de la class Article (ArticleRepository)
        $dernierArticle = $doctrine->getRepository(Article::class)->findOneBy([], ["dateDeCreation" => "DESC"]);
        
        // dd($dernierArticle);
        return $this->render('home/index.html.twig', [
            'dernierArticle' => $dernierArticle
        ]);              
       
    }
}
