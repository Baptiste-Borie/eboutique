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
}
