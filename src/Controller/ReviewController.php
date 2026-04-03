<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Review;
use App\Repository\BookRepository;
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
        BookRepository $bookRepository,
        ReviewRepository $reviewRepository,
        EntityManagerInterface $em,
    ): Response {
        $book = $bookRepository->find($bookId) ?? throw $this->createNotFoundException();
        $redirect = $this->redirectToRoute('app_book_show', ['slug' => $book->getSlug()]);

        if ($reviewRepository->findByUserAndBook($this->getUser(), $book)) {
            $this->addFlash('error', 'Vous avez déjà laissé un avis pour ce livre.');
            return $redirect;
        }

        if (!$this->isCsrfTokenValid('review' . $book->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $rating = max(1, min(5, (int) $request->request->get('rating')));
        $comment = $request->request->get('comment');

        $review = new Review();
        $review->setUser($this->getUser());
        $review->setBook($book);
        $review->setRating($rating);
        $review->setComment($comment ? mb_substr(strip_tags($comment), 0, 2000) : null);

        $em->persist($review);
        $em->flush();

        $this->addFlash('success', 'Votre avis a été soumis et sera visible après modération.');

        return $redirect;
    }
}
