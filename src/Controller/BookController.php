<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookSearchType;
use App\Repository\BookRepository;
use App\Repository\FavoriteRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/books')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index')]
    public function index(Request $request, BookRepository $bookRepository): Response
    {
        $form = $this->createForm(BookSearchType::class);
        $form->handleRequest($request);

        $books = $bookRepository->findBySearchCriteria(
            $form->get('query')->getData(),
            $form->get('category')->getData(),
            $form->get('author')->getData(),
        );

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'searchForm' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_book_show')]
    public function show(
        #[MapEntity(mapping: ['slug' => 'slug'])] Book $book,
        FavoriteRepository $favoriteRepository,
        ReviewRepository $reviewRepository,
    ): Response {
        $isFavorited = false;
        $hasReviewed = false;

        if ($this->getUser()) {
            $isFavorited = $favoriteRepository->findByUserAndBook($this->getUser(), $book) !== null;
            $hasReviewed = $reviewRepository->findByUserAndBook($this->getUser(), $book) !== null;
        }

        return $this->render('book/show.html.twig', [
            'book' => $book,
            'isFavorited' => $isFavorited,
            'hasReviewed' => $hasReviewed,
        ]);
    }
}
