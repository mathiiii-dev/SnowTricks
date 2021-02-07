<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\User;
use App\Form\Figure\FigureType;
use App\Form\Figure\FigureValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FigureController extends AbstractController
{
    /**
     * @Route("/figure/{id}", name="snowtricks_figure")
     */
    public function index($id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Figure::class);

        $figure = $repository->find($id);

        return $this->render('figure/index.html.twig', [
            'figure' => $figure
        ]);
    }

    /**
     * @Route("/create-figure", name="snowtricks_createfigure")
     * @Route("/edit-figure/{id}", name="snowtricks_editfigure")
     * @param Figure|null $figure
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function formFigure(Figure $figure = null, Request $request, EntityManagerInterface $em): Response
    {
        if(!$figure) {
            $figure = new Figure();
        }

        $form = $this->createForm(FigureType::class,$figure);
        $form->handleRequest($request);

        $formValidator = new FigureValidator();
        $repository = $this->getDoctrine()->getRepository(User::class);
        if ($formValidator->validator($form, $figure, $em, $repository)) {
            $id = $figure->getId();
            return $this->redirectToRoute('snowtricks_figure', ['id' => $id]);
        }

        return $this->render('figure/formFigure.html.twig', [
            'formFigure' => $form->createView(),
            'editMode' => $figure->getId() !== null
        ]);
    }

    /**
     * @Route("/delete-figure/{id}", name="snowtricks_deletefigure")
     * @param $id
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function deleteFigure($id, EntityManagerInterface $em)
    {
        $repository = $this->getDoctrine()->getRepository(Figure::class);

        $figure = $repository->find($id);
        $em->remove($figure);
        $em->flush();

        return $this->redirectToRoute('snowtricks_home');
    }
}
