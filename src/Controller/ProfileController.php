<?php

namespace App\Controller;

use App\Repository\FavoriteRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[Route('', name: 'app_profile')]
    public function index(
        ReservationRepository $reservationRepository,
        FavoriteRepository $favoriteRepository,
    ): Response {
        $user = $this->getUser();

        return $this->render('profile/index.html.twig', [
            'reservations' => $reservationRepository->findByUserWithBooks($user),
            'favorites' => $favoriteRepository->findByUserWithBooks($user),
        ]);
    }

    #[Route('/reservations', name: 'app_profile_reservations')]
    public function reservations(ReservationRepository $reservationRepository): Response
    {
        return $this->render('profile/reservations.html.twig', [
            'reservations' => $reservationRepository->findByUserWithBooks($this->getUser()),
        ]);
    }

    #[Route('/favorites', name: 'app_profile_favorites')]
    public function favorites(FavoriteRepository $favoriteRepository): Response
    {
        return $this->render('profile/favorites.html.twig', [
            'favorites' => $favoriteRepository->findByUserWithBooks($this->getUser()),
        ]);
    }
}
