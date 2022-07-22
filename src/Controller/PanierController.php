<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/panier', name: 'panier_')]

class PanierController extends AbstractController
{
// CREA PANIER 
    #[Route('/', name: 'show')]
    public function show(SessionInterface $session, ArticleRepository $repo): Response
    {
        $panier = $session->get("panier", []); // le panier de LA session
        //variables
        $dataPanier = [];
        $total = 0 ;

        foreach ( $panier as $id => $quantite )
        {
                $article = $repo->find($id);
                 // tableau vide Prod by Id et Quantité
                $dataPanier[]=
                [
                    "article" => $article,
                    "quantite" => $quantite
                ];
                $total += $article->getPrix() * $quantite;
                
            }       
            //dd($dataPanier);
        return $this->render('panier/index.html.twig', 
                [
                    'dataPanier' => $dataPanier,
                    'total' => $total
                ]);
    }

// AJOUTER au PANIER 

   #[Route('/add/{id<\d+>}', name: 'add')]
    public function add($id, SessionInterface $session)
        {
            $panier = $session->get('panier', []);

            if(empty($panier[$id])) 
            { 
                $panier[$id] = 1; 
            }else{
                $panier[$id]++;
            }  // Ou si existe déja article($id), ajoute +1 article($id) |-> $panier[$id]++;{ $panier[$id]++; }
            $session->set("panier", $panier);
            // dd($session->get("panier"));
            return $this->redirectToRoute("panier_show");
        
        }   

// SUPRIMER du PANIER

    #[Route('/delete/{id<\d+>}', name: 'delete_article')]
    public function delete($id, SessionInterface $session)
        {
            $panier = $session->get('panier', []);
        
            if( !empty($panier[$id]) )
            {unset($panier[$id]);}
            else
            {
            $this->addFlash('error', "L'article que vous essayez de retirer n'existe pas!");
            return $this->redirectToRoute("panier_show");
            }

            $session->set("panier", $panier);
        
            $this->addFlash('success', "L'article' a bien été retiré du panier.");
            return $this->redirectToRoute("panier_show");
        
        }    










}
