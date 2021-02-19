<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\User;
use App\Form\Figure\FigureType;
use App\Form\FormValidator;
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
     * @param $id
     * @return Response
     */
    public function index(int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Figure::class);

        $figure = $repository->find($id);

        if($figure == null){
            http_response_code(404);
        }

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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $figure = new Figure();

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        $formValidator = new FormValidator();
        $repository = $this->getDoctrine()->getRepository(User::class);
        if ($formValidator->validator($form)) {
            $figure->setCreatedAt(new \DateTime());
            $user = $repository->findOneBy(['username' => $this->getUser()->getUsername()]);
            $figure->setUser($user);
            $em->persist($figure);
            $em->flush();

            $this->addFlash(
                'success',
                'Création réussite !'
            );

            return $this->redirectToRoute('snowtricks_figure', ['id' => $figure->getId()]);
        }

        return $this->render('figure/formFigure.html.twig', [
            'formFigure' => $form->createView(),
            'editMode' => null
        ]);
    }

    /**
     * @Route("/edit-figure/{id}", name="snowtricks_editfigure")
     * @param Figure|null $figure
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function modifyFigure(Figure $figure, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        $formValidator = new FormValidator();

        if ($formValidator->validator($form)) {
            $id = $figure->getId();
            $figure->setModifiedAt(new \DateTime());
            $em->persist($figure);
            $em->flush();
            $this->addFlash(
                'success',
                'Modification enregistrée !'
            );

            return $this->redirectToRoute('snowtricks_figure', ['id' => $id]);
        }

        return $this->render('figure/formFigure.html.twig', [
            'formFigure' => $form->createView(),
            'editMode' => $figure->getId()
        ]);
    }

    /**
     * @Route("/delete-figure/{id}", name="snowtricks_deletefigure")
     * @param $id
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function deleteFigure($id, EntityManagerInterface $em): RedirectResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $repository = $this->getDoctrine()->getRepository(Figure::class);

        $figure = $repository->find($id);
        $em->remove($figure);
        $em->flush();

        $this->addFlash(
            'success',
            'Suppresion réussite !'
        );

        return $this->redirectToRoute('snowtricks_home');
    }
}
