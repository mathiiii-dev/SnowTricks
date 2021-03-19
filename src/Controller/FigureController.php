<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Entity\Figure;
use App\Entity\User;
use App\Form\DiscussionType;
use App\Form\FigureType;
use App\Repository\DiscussionRepository;
use App\Services\FlashService;
use App\Services\FormService;
use App\Services\MediaService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FigureController extends AbstractController
{
    private $entityManager;
    private $flash;
    private $mediaService;
    private $discussion;
    private $formDiscussion;

    public function __construct(EntityManagerInterface $entityManager, FlashService $flash, MediaService $mediaService, DiscussionRepository $discussion, DiscussionController $formDiscussion)
    {
        $this->entityManager = $entityManager;
        $this->flash = $flash;
        $this->mediaService = $mediaService;
        $this->discussion = $discussion;
        $this->formDiscussion = $formDiscussion;
    }

    /**
     * @Route("/figure/{figure}", name="snowtricks_figure")
     * @return Response
     */
    public function index(Figure $figure): Response
    {
        $formDiscussion = $this->formDiscussion->createFormDiscussion();

        if ($figure === null) {
            throw $this->createNotFoundException('La figure n\'a pas été trouvée');
        }

        return $this->render('figure/index.html.twig', [
            'figure' => $figure,
            'picture' => $figure->getPictures()->first(),
            'formDiscussion' => $formDiscussion
        ]);
    }

    /**
     * @Route("/create-figure", name="snowtricks_create_figure")
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @return Response
     */
    public function createFigure(Request $request): Response
    {
        $figure = new Figure();

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);
        $repository = $this->getDoctrine()->getRepository(User::class);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $repository->findOneBy(['username' => $this->getUser()->getUsername()]);
            $figure->setUser($user);

            $this->entityManager->persist($figure);
            $this->entityManager->flush();

            $this->flash->setFlashMessages(http_response_code(), 'Création de la figure avec succès !');

            return $this->redirectToRoute('snowtricks_figure', ['figure' => $figure->getId()]);

        }

        return $this->render('figure/formFigure.html.twig', [
            'formFigure' => $form->createView(),
            'editMode' => null
        ]);
    }

    /**
     * @Route("/edit-figure/{figure}", name="snowtricks_editfigure")
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @return Response
     */
    public function modifyFigure(Figure $figure, Request $request): Response
    {
        if ($figure === null) {
            throw $this->createNotFoundException('No figure found for id ' . $figure);
        }

        $originalPictures = $this->mediaService->originalMedia($figure->getPictures());
        $originalVideos = $this->mediaService->originalMedia($figure->getVideos());

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->mediaService->editMedia($figure->getPictures(), $originalPictures);
            $this->mediaService->editMedia($figure->getVideos(), $originalVideos);
            $this->entityManager->flush();

            $this->flash->setFlashMessages(http_response_code(), 'Modification de la figure avec succès !');

            return $this->redirectToRoute('snowtricks_figure', ['figure' => $figure->getId()]);
        }

        return $this->render('figure/formFigure.html.twig', [
            'formFigure' => $form->createView(),
            'editMode' => $figure->getId()
        ]);
    }

    /**
     * @Route("/delete-figure/{figure}", name="snowtricks_deletefigure")
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @return RedirectResponse
     */
    public function deleteFigure(Figure $figure): RedirectResponse
    {
        $this->mediaService->removeMedia($figure);

        $this->entityManager->remove($figure);
        $this->entityManager->flush();

        $this->flash->setFlashMessages(http_response_code(), 'Suppréssion de la figure avec succès !');

        return $this->redirectToRoute('snowtricks_home');
    }
}
