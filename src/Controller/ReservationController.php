<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Service\ReservationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reservation')]
#[IsGranted('ROLE_USER')]
class ReservationController extends AbstractController
{
    #[Route('/new/{id}', name: 'app_reservation_new')]
    public function create(
        Book $book,
        Request $request,
        ReservationService $reservationService,
    ): Response {
        if ($book->getAvailableCopies() <= 0) {
            $this->addFlash('error', 'Ce livre n\'est plus disponible.');
            return $this->redirectToRoute('app_book_show', ['slug' => $book->getSlug()]);
        }

        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationService->createReservation(
                $this->getUser(),
                $book,
                $reservation->getStartDate(),
                $reservation->getEndDate(),
            );

            $this->addFlash('success', 'Votre réservation a été enregistrée !');
            return $this->redirectToRoute('app_profile_reservations');
        }

        return $this->render('reservation/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/cancel', name: 'app_reservation_cancel', methods: ['POST'])]
    public function cancel(
        Reservation $reservation,
        Request $request,
        ReservationService $reservationService,
    ): Response {
        if ($reservation->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($reservation->getStatus() !== Reservation::STATUS_PENDING) {
            $this->addFlash('error', 'Seules les réservations en attente peuvent être annulées.');
            return $this->redirectToRoute('app_profile_reservations');
        }

        if (!$this->isCsrfTokenValid('cancel' . $reservation->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $reservationService->cancelReservation($reservation);
        $this->addFlash('success', 'Réservation annulée.');

        return $this->redirectToRoute('app_profile_reservations');
    }
}
