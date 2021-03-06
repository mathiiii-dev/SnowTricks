<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\User;
use App\Form\FigureType;
use App\Repository\DiscussionRepository;
use App\Repository\FigureRepository;
use App\Services\FlashService;
use App\Services\FigureManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class FigureController extends AbstractController
{
    private $entityManager;
    private $flash;
    private $figureManager;
    private $discussion;
    private $formDiscussion;
    private $figureRepository;
    private $slugger;

    public function __construct(
        EntityManagerInterface $entityManager,
        FlashService $flash,
        FigureManager $figureManager,
        DiscussionRepository $discussion,
        DiscussionController $formDiscussion,
        FigureRepository $figureRepository,
        SluggerInterface $slugger
    ) {
        $this->entityManager = $entityManager;
        $this->flash = $flash;
        $this->figureManager = $figureManager;
        $this->discussion = $discussion;
        $this->formDiscussion = $formDiscussion;
        $this->figureRepository = $figureRepository;
        $this->slugger = $slugger;
    }

    /**
     * @Route("/figure/{slug}",
     *     name="snowtricks_figure",
     *     methods={"GET"})
     */
    public function index(string $slug): Response
    {
        $slug = str_replace('-', ' ', $slug);

        $formDiscussion = $this->formDiscussion->createFormDiscussion();

        $figure = $this->figureRepository->findOneBy(['name' => $slug]);

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

            return $this->redirectToRoute('snowtricks_figure', ['slug' => $this->slugger->slug($figure->getName())]);

        }

        return $this->render('figure/formFigure.html.twig', [
            'formFigure' => $form->createView(),
            'editMode' => null
        ]);
    }

    /**
     * @Route("/figure/edit/{slug}",
     *     name="snowtricks_figure_edit",
     *     methods={"POST","GET"})
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function modifyFigure(string $slug, Request $request): Response
    {
        $slug = str_replace('-', ' ', $slug);

        $figure = $this->figureRepository->findOneBy(['name' => $slug]);
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

            return $this->redirectToRoute('snowtricks_figure', ['slug' => $this->slugger->slug($figure->getName())]);
        }

        return $this->render('figure/formFigure.html.twig', [
            'formFigure' => $form->createView(),
            'editMode' => $figure->getId()
        ]);
    }

    /**
     * @Route("/figure/delete/{slug}",
     *     name="snowtricks_figure_delete",
     *     methods={"POST","GET"})
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function deleteFigure(string $slug): RedirectResponse
    {
        $slug = str_replace('-', ' ', $slug);

        $figure = $this->figureRepository->findOneBy(['name' => $slug]);
        if ($figure === null) {
            throw $this->createNotFoundException('No figure found for id ' . $figure);
        }

        $this->figureManager->removeMedia($figure);

        $this->entityManager->remove($figure);
        $this->entityManager->flush();

        $this->flash->setFlashMessages(http_response_code(), 'Suppression de la figure avec succès !');

        return $this->redirectToRoute('snowtricks_home');
    }
}
