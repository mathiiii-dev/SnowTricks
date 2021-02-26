<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\User;
use App\Form\Figure\FigureType;
use App\Services\ErrorService;
use App\Services\FlashService;
use App\Services\FormService;
use App\Services\MediaService;
use App\Services\UrlService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FigureController extends AbstractController
{
    private $em;
    private $flash;
    private $checkForm;

    public function __construct(EntityManagerInterface $em, FlashService $flash, FormService $checkForm)
    {
        $this->em = $em;
        $this->flash = $flash;
        $this->checkForm = $checkForm;
    }

    /**
     * @Route("/figure/{id}", name="snowtricks_figure")
     * @return Response
     */
    public function index(int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Figure::class);

        $figure = $repository->find($id);

        if ($figure == null) {
            throw $this->createNotFoundException('La figure n\'a pas été trouvée');
        }

        $picture = $figure->getPictures()->first();

        return $this->render('figure/index.html.twig', [
            'figure' => $figure,
            'picture' => $picture
        ]);
    }

    /**
     * @Route("/create-figure", name="snowtricks_createfigure")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @return Response
     */
    public function createFigure(Request $request): Response
    {
        $figure = new Figure();

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);
        $repository = $this->getDoctrine()->getRepository(User::class);
        if ($form->isSubmitted() && $form->isValid() && $this->checkForm->checkFigure($figure, $form)) {
            $user = $repository->findOneBy(['username' => $this->getUser()->getUsername()]);
            $figure->setUser($user);

            $this->em->persist($figure);
            $this->em->flush();

            $this->flash->setFlashMessages(http_response_code(), 'Création réussite !');

            return $this->redirectToRoute('snowtricks_figure', ['id' => $figure->getId()]);

        }

        return $this->render('figure/formFigure.html.twig', [
            'formFigure' => $form->createView(),
            'editMode' => null
        ]);
    }

    /**
     * @Route("/edit-figure/{id}", name="snowtricks_editfigure")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @return Response
     */
    public function modifyFigure($id, Figure $figure, Request $request, MediaService $editMedia): Response
    {
        if (null === $figure = $this->em->getRepository(Figure::class)->find($id)) {
            throw $this->createNotFoundException('No figure found for id ' . $id);
        }

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->checkForm->checkFigure($figure, $form)) {

            $editMedia->editMedia($figure->getPictures());
            $editMedia->editMedia($figure->getVideos());

            $this->em->persist($figure);
            $this->em->flush();

            $this->flash->setFlashMessages(http_response_code(), 'Modification réussite !');

            return $this->redirectToRoute('snowtricks_figure', ['id' => $id]);
        }

        return $this->render('figure/formFigure.html.twig', [
            'formFigure' => $form->createView(),
            'editMode' => $figure->getId()
        ]);
    }

    /**
     * @Route("/delete-figure/{id}", name="snowtricks_deletefigure")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @return RedirectResponse
     */
    public function deleteFigure($id): RedirectResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $repository = $this->getDoctrine()->getRepository(Figure::class);

        $figure = $repository->find($id);
        foreach ($figure->getVideos() as $video) {
            $this->em->remove($video);
        }
        foreach ($figure->getPictures() as $picture) {
            $this->em->remove($picture);
        }
        $this->em->remove($figure);
        $this->em->flush();
        http_response_code(500);
        $this->flash->setFlashMessages(http_response_code(), 'Suppréssion réussite !');

        return $this->redirectToRoute('snowtricks_home');
    }
}
