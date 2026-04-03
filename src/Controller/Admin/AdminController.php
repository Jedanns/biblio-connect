<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use App\Entity\Review;
use App\Entity\User;
use App\Form\Admin\UserEditType;
use App\Repository\BookRepository;
use App\Repository\ReservationRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use App\Service\ReservationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('', name: 'app_admin_dashboard')]
    public function dashboard(
        BookRepository $bookRepository,
        UserRepository $userRepository,
        ReservationRepository $reservationRepository,
        ReviewRepository $reviewRepository,
    ): Response {
        return $this->render('admin/dashboard.html.twig', [
            'totalBooks' => $bookRepository->count(),
            'totalUsers' => $userRepository->count(),
            'pendingReservations' => $reservationRepository->findPendingReservations(),
            'unapprovedReviews' => $reviewRepository->findUnapprovedReviews(),
        ]);
    }

    // ========== USERS ==========

    #[Route('/users', name: 'app_admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('admin/users/index.html.twig', [
            'users' => $userRepository->findBy([], ['lastName' => 'ASC']),
        ]);
    }

    #[Route('/users/{id}/edit', name: 'app_admin_user_edit')]
    public function userEdit(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Utilisateur modifié.');
            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/users/edit.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }

    // ========== REVIEWS MODERATION ==========

    #[Route('/reviews', name: 'app_admin_reviews')]
    public function reviews(ReviewRepository $reviewRepository): Response
    {
        return $this->render('admin/reviews/index.html.twig', [
            'reviews' => $reviewRepository->findUnapprovedReviews(),
        ]);
    }

    #[Route('/reviews/{id}/approve', name: 'app_admin_review_approve', methods: ['POST'])]
    public function reviewApprove(Review $review, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('approve' . $review->getId(), $request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $review->setIsApproved(true);
        $em->flush();
        $this->addFlash('success', 'Avis approuvé.');

        return $this->redirectToRoute('app_admin_reviews');
    }

    #[Route('/reviews/{id}/reject', name: 'app_admin_review_reject', methods: ['POST'])]
    public function reviewReject(Review $review, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('reject' . $review->getId(), $request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $em->remove($review);
        $em->flush();
        $this->addFlash('success', 'Avis supprimé.');

        return $this->redirectToRoute('app_admin_reviews');
    }

    // ========== STOCK ==========

    #[Route('/stock', name: 'app_admin_stock')]
    public function stock(BookRepository $bookRepository, ReservationRepository $reservationRepository): Response
    {
        return $this->render('admin/stock.html.twig', [
            'books' => $bookRepository->findBy([], ['title' => 'ASC']),
            'pendingReservations' => $reservationRepository->findPendingReservations(),
        ]);
    }

    // ========== RESERVATIONS ==========

    #[Route('/reservations', name: 'app_admin_reservations')]
    public function reservations(ReservationRepository $reservationRepository): Response
    {
        return $this->render('admin/reservations.html.twig', [
            'reservations' => $reservationRepository->findAllWithRelations(),
        ]);
    }

    #[Route('/reservations/{id}/activate', name: 'app_admin_reservation_activate', methods: ['POST'])]
    public function reservationActivate(
        Reservation $reservation,
        Request $request,
        ReservationService $reservationService,
    ): Response {
        if (!$this->isCsrfTokenValid('activate' . $reservation->getId(), $request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $reservationService->activateReservation($reservation);
        $this->addFlash('success', 'Réservation activée.');

        return $this->redirectToRoute('app_admin_reservations');
    }

    #[Route('/reservations/{id}/return', name: 'app_admin_reservation_return', methods: ['POST'])]
    public function reservationReturn(
        Reservation $reservation,
        Request $request,
        ReservationService $reservationService,
    ): Response {
        if (!$this->isCsrfTokenValid('return' . $reservation->getId(), $request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $reservationService->returnBook($reservation);
        $this->addFlash('success', 'Livre retourné.');

        return $this->redirectToRoute('app_admin_reservations');
    }
}
