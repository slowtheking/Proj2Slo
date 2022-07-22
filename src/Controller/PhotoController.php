<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Form\PhotoType;
use App\Form\RegistrationFormType;
use App\Controller\PhotoController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhotoController extends AbstractController
{
    #[Route('/photo', name: 'add_photo')]
    public function index(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())


            if($form->get('photo')->getData())
        {
                $file = $form->get('photo')->getData(); 
                //photo slug renomme l'image
                $fileName = uniqid() . '.' . $file->guessExtension();

                try{
                    $file->move($this->getParameter('photo_type'), $fileName);
                }catch(fileExeption $e){}

                $photo->setName($fileName);

            $manager->persist($photo);
            $manager->flush(); 

            $this->addFlash('success', "Photo Upload réussit");
        }

        return $this->render('photo/photo.html.twig', [
            'photoIll' => $form->createView(),
        ]);        
    }

}
