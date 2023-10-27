<?php

namespace App\Controller;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AuthorRepository;
use App\Entity\Author;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AuthorType;




class AuthorController extends AbstractController
{
    public function index(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findAll();

        return $this->render('author/index.html.twig', [
            'authors' => $authors,
        ]);
    }
        public function addAuthorStatic()
        {
            $entityManager = $this->getDoctrine()->getManager();
    
            $author = new Author();
            $author->setUsername('test');

            $author->setEmail('test@example.com');
    
            $entityManager->persist($author);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_author');
        }
    

        public function addAuthorF(Request $req, ManagerRegistry $doctrine): Response
        {
            $a=new Author(); //notre objet est vide
            
            //instancier le formulaire
            $form=$this->createForm(AuthorType::class,$a);
            //récupérer les données
            /* $a->setUsername($req->get('username'));
            $a->setEmail($req->get('email'));*/
            $form->handleRequest($req);
            //si on a cliqué sur le bouton submit
            if ($form->isSubmitted()){
            $em=$doctrine->getManager();
            //3- préparer la requête d'ajout
            $em->persist($a);
            //4- exécuter la requête
            $em->flush();
            return $this->redirectToRoute("app_author");
            }
            //renvoyer le form vers la vue
          //  return $this->render("author/add.html.twig", ["myForm"=>$form->createView()]);
            return $this->renderForm("author/add.html.twig", ["myForm"=>$form]);
        }

        public function UpdateAuthor(ManagerRegistry $doctrine, Request $request, AuthorRepository $rep, $id): Response
        {
           $author = $rep->find($id);
           $form=$this->createForm(AuthorType::class,$author);
           $form->handleRequest($request);
           if($form->isSubmitted()){
               $em= $doctrine->getManager();
               $em->persist($author);
               $em->flush();
               return $this-> redirectToRoute('app_author');
           }
           return $this->render('author/edit.html.twig',['myForm'=>$form->createView(),
           ]);
        }
     public function deleteAuthor($id, AuthorRepository $rep, ManagerRegistry $doctrine): Response
     {
         $em= $doctrine->getManager();
         $author= $rep->find($id);
         $em->remove($author);
         $em->flush();
         return $this-> redirectToRoute('app_author');
     }
    }
    
