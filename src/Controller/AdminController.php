<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Entity\Categorie;
use App\Form\ArticleType;
use App\Form\CategorieType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/admin", name:"admin_")]
class AdminController extends AbstractController
{

// ========= AJOUT NEW ARTICLE
        // créer un Param pour stocker les images dans service.yaml
        // parameters:
        //    photo_article: "%kernel.project_dir%/public/photoArticles"


    #[Route('/ajout-article', name: 'ajout_article')]
    public function ajout(Request $request,  EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() )
            {
                if($form->get('photoForm')->getData())
                {
                $file = $form->get('photoForm')->getData(); 
                //photo slug renomme l'image
                $fileName = $slugger->slug($article->getTitre()) . uniqid() . '.' . $file->guessExtension();

                try{
                    $file->move($this->getParameter('photo_article'), $fileName);
                }catch(fileExeption $e){}

                $article->setphoto($fileName);
                }

                $article->setDateEnregistrement(new DateTime("now"));
                $manager->persist($article);
                $manager->flush();

                return $this->redirectToRoute("admin_gestion_articles");
            }

        return $this->render('admin/formulaire.html.twig', [
            'formArticle' => $form->createView()
        ]);
    }

// ========= GESTION Articles

    #[Route("/gestion-articles", name:"gestion_articles")]
    public function gestionArticles(ArticleRepository $repo)
    {
        $articles = $repo->findAll();
        return $this->render("admin/gestion-articles.html.twig",[
        'articles' => $articles
        ]);
    }

// ========= Details Articles

    #[Route("/details-article-{id<\d+>}", name: "det_article")]
    public function detArticle($id, ArticleRepository $repo)
    {
        $article = $repo->find($id);
        return $this->render("admin/details-article.html.twig",
        ['article' => $article]
        );

    }

// ========= UPDATE Article

    #[Route("/update-article-{id<\d+>}", name:"update_article")]
    public function update($id, ArticleRepository $repo, Request $request, SluggerInterface $slugger)
    {
    $article = $repo->find($id);

    $form = $this->createForm(ArticleType::class, $article);
    $form->handleRequest($request);

    if ( $form->isSubmitted() && $form->isValid() )
        {
            //photo slug renomme l'image
            if($form->get('photoForm')->getData())
            {
            $file = $form->get('photoForm')->getData(); 
            $fileName = $slugger->slug($article->getTitre()) . uniqid() . '.' . $file->guessExtension();

            try{
                $file->move($this->getParameter('photo_article'), $fileName);
            }catch(fileExeption $e){}
            
            $article->setphoto($fileName);
            }
            
            $article->setDateEnregistrement(new DateTime("now"));

            $repo->add($article, 1);
            return $this->redirectToRoute("admin_gestion_articles");
        }

        return $this->render("admin/formulaire.html.twig", [
            'formArticle' => $form->createView()
        ]);

    }

// ========= EFFACER Article 

#[Route("/delete-article-{id<\d+>}", name:"delete_article")]

        public function delete($id, ArticleRepository $repo):Response
    {
        $article = $repo->find($id); 
        $repo->remove($article, 1); 
        // On utilise ici la fonction remove |-> ArticleRepository ligne 33 -> remove(Article $entity, bool $flush = false)
        // on passe le bolean $flush à true [1]

        return $this->redirectToRoute("admin_gestion_articles");
    } 

// =========  AJOUTER CATEGORIE

#[Route("/categorie-ajout", name:"ajout_categorie")]
        public function ajoutCategorie(Request $request, CategorieRepository $repoCat):Response
    {
        $categorie = new Categorie();

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid())
            {
                $repoCat->add($categorie, 1);
                return $this->redirectToRoute("app_home");
            }

        return $this->render("admin/formCategorie.html.twig", [
            "formCategorie" => $form->createView()
        ]);        

    }



// Fin de page
}
