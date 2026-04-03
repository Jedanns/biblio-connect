<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Review;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ReviewController extends AbstractController
{
    #[Route('/review/new/{bookId}', name: 'app_review_new', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(
        int $bookId,
        Request $request,
        ReviewRepository $reviewRepository,
        EntityManagerInterface $em,
    ): Response {
        $book = $em->getRepository(Book::class)->find($bookId);
        if (!$book) {
            throw $this->createNotFoundException();
        }

        if ($reviewRepository->findByUserAndBook($this->getUser(), $book)) {
            $this->addFlash('error', 'Vous avez déjà laissé un avis pour ce livre.');
            return $this->redirectToRoute('app_book_show', ['slug' => $book->getSlug()]);
        }

        if (!$this->isCsrfTokenValid('review' . $book->getId(), $request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $rating = (int) $request->request->get('rating');
        if ($rating < 1 || $rating > 5) {
            $this->addFlash('error', 'La note doit être entre 1 et 5.');
            return $this->redirectToRoute('app_book_show', ['slug' => $book->getSlug()]);
        }

        $review = new Review();
        $review->setUser($this->getUser());
        $review->setBook($book);
        $review->setRating($rating);

        $comment = $request->request->get('comment');
        if ($comment) {
            $review->setComment(substr(strip_tags($comment), 0, 2000));
        }

        $em->persist($review);
        $em->flush();

        $this->addFlash('success', 'Votre avis a été soumis et sera visible après modération.');

        return $this->redirectToRoute('app_book_show', ['slug' => $book->getSlug()]);
    }
}
