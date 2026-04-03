<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Favorite;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FavoriteController extends AbstractController
{
    #[Route('/favorite/toggle/{id}', name: 'app_favorite_toggle', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function toggle(
        Book $book,
        Request $request,
        FavoriteRepository $favoriteRepository,
        EntityManagerInterface $em,
    ): Response {
        if (!$this->isCsrfTokenValid('favorite' . $book->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $existing = $favoriteRepository->findByUserAndBook($this->getUser(), $book);

        if ($existing) {
            $em->remove($existing);
            $em->flush();
            $this->addFlash('success', 'Livre retiré de vos favoris.');
        } else {
            $favorite = new Favorite();
            $favorite->setUser($this->getUser());
            $favorite->setBook($book);
            $em->persist($favorite);
            $em->flush();
            $this->addFlash('success', 'Livre ajouté à vos favoris !');
        }

        return $this->redirectToRoute('app_book_show', ['slug' => $book->getSlug()]);
    }
}
