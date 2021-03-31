<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\User;
use App\Form\FigureType;
use App\Repository\DiscussionRepository;
use App\Services\FlashService;
use App\Services\FigureManager;
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
    private $figureManager;
    private $discussion;
    private $formDiscussion;

    public function __construct(
        EntityManagerInterface $entityManager,
        FlashService $flash,
        FigureManager $figureManager,
        DiscussionRepository $discussion,
        DiscussionController $formDiscussion
    ) {
        $this->entityManager = $entityManager;
        $this->flash = $flash;
        $this->figureManager = $figureManager;
        $this->discussion = $discussion;
        $this->formDiscussion = $formDiscussion;
    }

    /**
     * @Route("/figure/{figure}",
     *     name="snowtricks_figure",
     *     requirements={"figure"="\d+"},
     *     methods={"GET"})
     */
    public function index(Figure $figure): Response
    {
        $formDiscussion = $this->formDiscussion->createFormDiscussion();

        if ($figure === null) {
            throw $this->createNotFoundException('La figure n\'a pas été trouvée');
        }
        $picture = "/image/img-header.jpg";
        if($figure->getPictures()->first()) {
            $picture = $figure->getPictures()->first()->getLink();
        }

        return $this->render('figure/index.html.twig', [
            'figure' => $figure,
            'picture' =>  $picture,
            'formDiscussion' => $formDiscussion,
        ]);
    }

    /**
     * @Route("/create-figure",
     *     name="snowtricks_figure_create",
     *     methods={"POST","GET"})
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
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
     * @Route("/figure/edit/{figure}",
     *     name="snowtricks_figure_edit",
     *     requirements={"figure"="\d+"},
     *     methods={"POST","GET"})
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function modifyFigure(Figure $figure, Request $request): Response
    {
        if ($figure === null) {
            throw $this->createNotFoundException('No figure found for id ' . $figure);
        }

        $originalPictures = $this->figureManager->originalMedia($figure->getPictures());
        $originalVideos = $this->figureManager->originalMedia($figure->getVideos());

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->figureManager->editMedia($figure->getPictures(), $originalPictures);
            $this->figureManager->editMedia($figure->getVideos(), $originalVideos);
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
     * @Route("/figure/delete/{figure}",
     *     name="snowtricks_figure_delete",
     *     methods={"POST","GET"})
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function deleteFigure(Figure $figure): RedirectResponse
    {
        $this->figureManager->removeMedia($figure);

        $this->entityManager->remove($figure);
        $this->entityManager->flush();

        $this->flash->setFlashMessages(http_response_code(), 'Suppression de la figure avec succès !');

        return $this->redirectToRoute('snowtricks_home');
    }
}
