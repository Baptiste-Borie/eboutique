<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin/users')]
final class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index')]
    public function index(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_user_delete', methods: ['POST'])]
    public function delete(User $user, EntityManagerInterface $em, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->getUser() === $user) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            return $this->redirectToRoute('app_user_index');
        }

        if ($this->isCsrfTokenValid('delete-user-' . $user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'Utilisateur supprimé.');
        }

        return $this->redirectToRoute('app_user_index');
    }

    #[Route('/{id}/toggle-admin', name: 'app_user_toggle_admin', methods: ['POST'])]
    public function toggleAdmin(User $user, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $roles = $user->getRoles();
        if (in_array('ROLE_ADMIN', $roles)) {
            $roles = array_filter($roles, fn($r) => $r !== 'ROLE_ADMIN');
            $this->addFlash('warning', 'Utilisateur rétrogradé.');
        } else {
            $roles[] = 'ROLE_ADMIN';
            $this->addFlash('success', 'Utilisateur promu admin.');
        }

        $user->setRoles($roles);
        $em->flush();

        return $this->redirectToRoute('app_user_index');
    }
}
