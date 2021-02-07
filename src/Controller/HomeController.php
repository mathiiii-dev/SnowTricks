<?php

namespace App\Controller;

use App\Entity\Figure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="snowtricks_home")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Figure::class);

        $figures = $repository->findAll();

        return $this->render('home/index.html.twig', [
            'figures' => $figures
        ]);
    }

}
