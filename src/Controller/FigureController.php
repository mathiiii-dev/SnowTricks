<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\User;
use App\Form\Type\FigureType;
use App\Form\Type\FigureValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FigureController extends AbstractController
{
    /**
     * @Route("/figure/{id}", name="snowtricks_figure")
     */
    public function index(int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Figure::class);

        $figure = $repository->find($id);

        return $this->render('figure/index.html.twig', [
            'figure' => $figure
        ]);
    }

    /**
     * @Route("/create-figure", name="snowtricks_createfigure")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function createFigure(Request $request, EntityManagerInterface $em): Response
    {
        $figure = new Figure();

        $form = $this->createForm(FigureType::class,$figure);
        $form->handleRequest($request);

        $formValidator = new FigureValidator();
        $repository = $this->getDoctrine()->getRepository(User::class);
        if ($formValidator->validator($form, $figure, $em, $repository)) {
            return $this->redirectToRoute('snowtricks_home');
        }

        return $this->render('figure/createFigure.html.twig', [
            'formFigure' => $form->createView()
        ]);
    }
}
