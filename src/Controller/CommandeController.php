<?php

namespace App\Controller;

use DateTime;
use App\Entity\Commande;
use App\Entity\CommandeDetail;
use App\Repository\ArticleRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(): Response
    {
        return $this->render('commande/index.html.twig', 
        [
            'controller_name' => 'CommandeController',
        ]);
    }


    #[Route('/choix-commande', name: 'commande_choix')]
    public function commander(SessionInterface $session, 
                                ArticleRepository $repoPro, 
                                CommandeRepository $repoCom, 
                                CommandeDetailRepository $repoDet, 
                                EntityManagerInterface $manager): Response

// FUNCTION 

    {
        $commande = new Commande();
        $panier = $session->get('panier', []); // Recup  PANIER
        $user = $this->getUser(); // Recup de l'USER

// si user non connecté -> ne peux pas passer commande        
            if(!$user) 
                {
                $this->addFlash("error", "veuillez vous connecter ou vous inscrire !");
                return $this->redirectToRoute("app_login");
                }
        
            if (empty($panier)) 
                {
                $this->addFlash("error", "veuillez vous connecter ou vous inscrire !");
                return $this->redirectToRoute("article_all");
                }
// FONCTION    
            foreach ( $panier as $id => $quantite )   
                {
                    $article = $repoArt->find($id);
// tableau vide dans lequel il y aura le Prod et sa Quantité
                    $dataPanier[] = 
                    [
                        "article" => $article,
                        "quantite" => $quantite,
                        "sousTotal" => $article->getPrix() * $quantite,
                    ]; 
// La variable - $total recup les sous-totaux et add
                    $total += $article->getPrix() * $quantite;
                };  

// On parcours le tableau - $dataPanier[]                
                $commande->setUser($user)
                        ->setDateDeCommande(new DateTime ("now"))
                        ->setMontant($total);

                $repoCom->add($commande); 
                
            foreach ( $dataPanier as $key  => $value ){

                    $commandeDetail = new CommandeDetail();

                    $article = $value["article"];
                    $quantite = $value["quantite"];
                    $sousTotal = $value["sousTotal"];


                    $commandeDetail->setCommande($commande)
                                    ->setArticle($article)
                                    ->setQuantite($quantite)
                                    ->setPrix($sousTotal);
                    
                    $repoDet->add($commandeDetail);  // On persiste la commandeDetail sans l'envoyer FLUSH
                }

            $manager->flush(); // On Envoye le Panier FLUSH avec la commande $manager
                    
            $session->remove("panier"); // On retire le Panier
                    
            $this->addFlash("success", "Votre selection est validée - Merci de votre confiance");
            return $this->redirectToRoute("app_home");
   
    }
}
