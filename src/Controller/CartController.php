<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Film;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;


final class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(Security $security): Response
    {
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour voir le panier.');
        }

        $cart = $user->getCart();

        if (!$cart) {
            $cartItems = [];
        } else {
            $cartItems = $cart->getCartItems();
        }

        return $this->render('cart/index.html.twig', [
            'cartItems' => $cartItems,
        ]);
    }


    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function addToCart(
        Film $film,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response {
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour ajouter un film au panier.');
        }

        $cart = $user->getCart();

        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);
            $entityManager->persist($cart);
        }

        $existingItem = null;
        foreach ($cart->getCartItems() as $item) {
            if ($item->getFilm()->getId() === $film->getId()) {
                $existingItem = $item;
                break;
            }
        }

        if ($existingItem) {
            $existingItem->setQuantity($existingItem->getQuantity() + 1);
        } else {
            $cartItem = new CartItem();
            $cartItem->setFilm($film);
            $cartItem->setQuantity(1);
            $cartItem->setCart($cart);
            $entityManager->persist($cartItem);
        }

        $entityManager->flush();

        $this->addFlash('success', 'Film ajouté au panier !');

        return $this->redirectToRoute('app_home');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function removeFromCart(
        CartItem $cartItem,
        Security $security,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $security->getUser();

        if (!$user || $cartItem->getCart()->getUser() !== $user) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $entityManager->remove($cartItem);
        $entityManager->flush();

        $this->addFlash('success', 'Film retiré du panier.');

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/increase/{id}', name: 'app_cart_increase')]
    public function increaseQuantity(
        CartItem $cartItem,
        Security $security,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $security->getUser();

        if (!$user || $cartItem->getCart()->getUser() !== $user) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $cartItem->setQuantity($cartItem->getQuantity() + 1);
        $entityManager->flush();

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decreaseQuantity(
        CartItem $cartItem,
        Security $security,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $security->getUser();

        if (!$user || $cartItem->getCart()->getUser() !== $user) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $newQuantity = $cartItem->getQuantity() - 1;

        if ($newQuantity <= 0) {
            $entityManager->remove($cartItem);
        } else {
            $cartItem->setQuantity($newQuantity);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_cart');
    }


    #[Route('/cart/checkout', name: 'app_cart_checkout')]
    public function checkout(Security $security): Response
    {
        $user = $security->getUser();

        if (!$user || !$user->getCart() || $user->getCart()->getCartItems()->isEmpty()) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('app_cart');
        }

        return $this->render('cart/checkout.html.twig', [
            'cartItems' => $user->getCart()->getCartItems(),
        ]);
    }

    #[Route('/cart/confirm', name: 'app_cart_confirm', methods: ['POST'])]
    public function confirmOrder(
        Security $security,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $user = $security->getUser();
        $cart = $user->getCart();

        if (!$cart || $cart->getCartItems()->isEmpty()) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('app_cart');
        }

        $address = $request->request->get('address');
        $city = $request->request->get('city');
        $postal = $request->request->get('postal');

        // Ici tu pourrais créer une entité Order, l’enregistrer, etc.
        // Pour l'instant on peut juste vider le panier.

        foreach ($cart->getCartItems() as $item) {
            $em->remove($item);
        }

        $em->flush();

        return $this->redirectToRoute('app_cart_success');
    }

    #[Route('/cart/success', name: 'app_cart_success')]
    public function success(): Response
    {
        return $this->render('cart/success.html.twig');
    }
}
