<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Book;
use App\Repository\BookRepository;
use App\Form\BookType;


class BookController extends AbstractController
{
    public function list(Request $request, BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();
        $publishedCount = $bookRepository->countPublishedBooks();
        $unpublishedCount = $bookRepository->countUnpublishedBooks();

        return $this->render('book/list.html.twig', [
            'books' => $books,
            'publishedCount' => $publishedCount,
            'unpublishedCount' => $unpublishedCount,
        ]);
    }
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
     
            if ($form->isSubmitted()){
                $em=$doctrine->getManager();
                $author = $book->getAuthor();
                $author->setNbbookss($author->getNbbookss() + 1);
             $bookexist=$doctrine->getRepository(persistentObject: Book::class)->findOneBy(criteria:['ref'=>$book->getRef()]);
                if($bookexist){
                 return new Response (content :'book exist');
     
                }else{
                 $em->persist($book);
                 $em->flush();
                 return $this->redirectToRoute('book_list');
                 
                }

        }
            return $this->renderForm("new.html.twig", ["form"=>$form]); 
    }
    public function UpdateBook(ManagerRegistry $doctrine, Request $request, BookRepository $rep, $ref): Response
    {
        $book = $rep->find($ref);
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();

            if (!$book->isPublished()) {
                $em->persist($book);
                $em->flush();
                return $this->redirectToRoute('book_list');
            } else {
                $this->addFlash('error', 'Cannot update a published book.');
                return $this->redirectToRoute('book_list');
            }
        }

        return $this->render('new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    public function DeleteBook($ref, BookRepository $rep, ManagerRegistry $doctrine): Response {

        $em= $doctrine->getManager();
         $author= $rep->find($ref);
         $em->remove($author);
         $em->flush();
         return $this-> redirectToRoute('book_list');
    }
    public function show($ref, BookRepository $bookRepository): Response
    {
        $book = $bookRepository->find($ref);
    
        if (!$book) {
            throw $this->createNotFoundException('Livre non trouvé');
        }
    
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }
   
}
