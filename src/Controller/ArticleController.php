<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController

{
// Detail Article pour le visiteur

#[Route('/article/{id<\d+>}', name:"article_show")]
public function show($id, ArticleRepository $repo){

    $article = $repo->find($id);

    return $this->render("article/show.html.twig",[
        'article'=>$article
    ]);
}

// TOUS Articles pour le visiteur

#[Route('/articles', name:"articles_all")]
public function all(ArticleRepository $repoArt, CategorieRepository $repoCat){

    $articles = $repoArt->findAll();
    $categories = $repoCat->findAll();

    return $this->render("article/collection.html.twig",[
        'articles'=>$articles,
        'categories' => $categories
    ]);
}

// Articles par categorie

#[Route('/categorie/{id<\d+>}', name:"articles_categorie")]
public function categorieArticles($id, CategorieRepository $repo){
    // Recup la categorie sur laquelle on a cliqué pour accéder au produit lié
    $categorie = $repo->find($id);
    // recup liste de tts les categories pour les Aff dans la liste sur la page
    $categories = $repo->findAll();

    return $this->render("article/collection.html.twig",[
        //Recup les article de la categorie grace à la RELATION
        'articles' => $categorie->getArticles(),
        'categories' => $categories
    ]);
}













// Fin de page
}
