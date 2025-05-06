<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        FilmRepository $filmRepository,
        CategorieRepository $categorieRepository
    ): Response {
        $categorieId = $request->query->get('categorie');
        $categories = $categorieRepository->findAll();

        if ($categorieId) {
            $films = $filmRepository->findBy(['categorie' => $categorieId]);
        } else {
            $films = $filmRepository->findAll();
        }

        return $this->render('home/index.html.twig', [
            'films' => $films,
            'categories' => $categories,
            'selectedCategorie' => $categorieId,
        ]);
    }
}
