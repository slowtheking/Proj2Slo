<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Controller\HomeController;
use App\Repository\PhotoRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name:'app_home')]
    // pour afficher les [8] derniers Article en ordre descendant [DESC] sur la Home
    public function index(ArticleRepository $repo,PhotoRepository $repoPhoto): Response
    {
        $dernierArticles = $repo->findBy([], ["dateEnregistrement" => "DESC"], 8);
        // dd($dernierArticles);

// pour le carousel        
        $photos = $repoPhoto-> findBy(['type'=>'ambiance']);
        return $this->render('home/index.html.twig', 
        [ 
            'articles' => $dernierArticles,
            'photos' => $photos
        ]);
// fin carousel 

    }
}

// ================
