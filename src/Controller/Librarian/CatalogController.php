<?php

namespace App\Controller\Librarian;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Form\AuthorType;
use App\Form\BookType;
use App\Form\CategoryType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Repository\ReservationRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/librarian')]
#[IsGranted('ROLE_LIBRARIAN')]
class CatalogController extends AbstractController
{
    #[Route('', name: 'app_librarian_dashboard')]
    public function dashboard(
        BookRepository $bookRepository,
        ReservationRepository $reservationRepository,
    ): Response {
        return $this->render('librarian/dashboard.html.twig', [
            'totalBooks' => $bookRepository->count(),
            'pendingReservations' => $reservationRepository->findPendingReservations(),
        ]);
    }


    #[Route('/books', name: 'app_librarian_books')]
    public function bookList(BookRepository $bookRepository): Response
    {
        return $this->render('librarian/book/index.html.twig', [
            'books' => $bookRepository->findBy([], ['title' => 'ASC']),
        ]);
    }

    #[Route('/books/new', name: 'app_librarian_book_new')]
    public function bookNew(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        FileUploader $fileUploader,
    ): Response {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book->setSlug(strtolower($slugger->slug($book->getTitle())));
            $book->setAvailableCopies($book->getTotalCopies());

            $coverFile = $form->get('coverImageFile')->getData();
            if ($coverFile) {
                $book->setCoverImage($fileUploader->upload($coverFile));
            }

            $em->persist($book);
            $em->flush();

            $this->addFlash('success', 'Livre ajouté avec succès.');
            return $this->redirectToRoute('app_librarian_books');
        }

        return $this->render('librarian/book/new.html.twig', ['form' => $form]);
    }

    #[Route('/books/{id}/edit', name: 'app_librarian_book_edit')]
    public function bookEdit(
        Book $book,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        FileUploader $fileUploader,
    ): Response {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book->setSlug(strtolower($slugger->slug($book->getTitle())));
            $book->setUpdatedAt(new \DateTimeImmutable());

            $coverFile = $form->get('coverImageFile')->getData();
            if ($coverFile) {
                if ($book->getCoverImage()) {
                    $fileUploader->remove($book->getCoverImage());
                }
                $book->setCoverImage($fileUploader->upload($coverFile));
            }

            $em->flush();

            $this->addFlash('success', 'Livre modifié avec succès.');
            return $this->redirectToRoute('app_librarian_books');
        }

        return $this->render('librarian/book/edit.html.twig', [
            'form' => $form,
            'book' => $book,
        ]);
    }

    #[Route('/books/{id}/delete', name: 'app_librarian_book_delete', methods: ['POST'])]
    public function bookDelete(
        Book $book,
        Request $request,
        EntityManagerInterface $em,
        FileUploader $fileUploader,
    ): Response {
        if (!$this->isCsrfTokenValid('delete' . $book->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        if ($book->getCoverImage()) {
            $fileUploader->remove($book->getCoverImage());
        }

        $em->remove($book);
        $em->flush();

        $this->addFlash('success', 'Livre supprimé.');
        return $this->redirectToRoute('app_librarian_books');
    }


    #[Route('/authors', name: 'app_librarian_authors')]
    public function authorList(AuthorRepository $authorRepository): Response
    {
        return $this->render('librarian/author/index.html.twig', [
            'authors' => $authorRepository->findBy([], ['lastName' => 'ASC']),
        ]);
    }

    #[Route('/authors/new', name: 'app_librarian_author_new')]
    public function authorNew(Request $request, EntityManagerInterface $em): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($author);
            $em->flush();
            $this->addFlash('success', 'Auteur ajouté.');
            return $this->redirectToRoute('app_librarian_authors');
        }

        return $this->render('librarian/author/new.html.twig', ['form' => $form]);
    }

    #[Route('/authors/{id}/edit', name: 'app_librarian_author_edit')]
    public function authorEdit(Author $author, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Auteur modifié.');
            return $this->redirectToRoute('app_librarian_authors');
        }

        return $this->render('librarian/author/edit.html.twig', [
            'form' => $form,
            'author' => $author,
        ]);
    }


    #[Route('/categories', name: 'app_librarian_categories')]
    public function categoryList(CategoryRepository $categoryRepository): Response
    {
        return $this->render('librarian/category/index.html.twig', [
            'categories' => $categoryRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    #[Route('/categories/new', name: 'app_librarian_category_new')]
    public function categoryNew(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
    ): Response {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Catégorie ajoutée.');
            return $this->redirectToRoute('app_librarian_categories');
        }

        return $this->render('librarian/category/new.html.twig', ['form' => $form]);
    }

    #[Route('/categories/{id}/edit', name: 'app_librarian_category_edit')]
    public function categoryEdit(
        Category $category,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
    ): Response {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->flush();
            $this->addFlash('success', 'Catégorie modifiée.');
            return $this->redirectToRoute('app_librarian_categories');
        }

        return $this->render('librarian/category/edit.html.twig', [
            'form' => $form,
            'category' => $category,
        ]);
    }


    #[Route('/reservations', name: 'app_librarian_reservations')]
    public function reservationHistory(ReservationRepository $reservationRepository): Response
    {
        return $this->render('librarian/reservations.html.twig', [
            'reservations' => $reservationRepository->findAllWithRelations(),
        ]);
    }
}
