<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Picture;
use App\Entity\User;
use App\Form\Figure\FigureType;
use App\Services\ErrorService;
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
    /**
     * @Route("/figure/{id}", name="snowtricks_figure")
     * @param $id
     * @return Response
     */
    public function index(int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Figure::class);

        $figure = $repository->find($id);
        if ($figure == null) {
            throw $this->createNotFoundException('La figure n\'a pas été trouvée');
        }

        return $this->render('figure/index.html.twig', [
            'figure' => $figure
        ]);
    }

    /**
     * @Route("/create-figure", name="snowtricks_createfigure")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UrlService $urlCheck
     * @param ErrorService $error
     * @return Response
     */
    public function createFigure(Request $request, EntityManagerInterface $em, UrlService $urlCheck, ErrorService $error): Response
    {
        $figure = new Figure();

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);
        $repository = $this->getDoctrine()->getRepository(User::class);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $repository->findOneBy(['username' => $this->getUser()->getUsername()]);
            $figure->setUser($user);

            if(!$urlCheck->checkImageUrl($figure)) {
                return $error->errorForm('pictures', $form, 'Les liens données ne sont pas des images.');
            }

            $videos = $urlCheck->checkVideoUrl($figure);

            if(!$videos) {
                return $error->errorForm('videos', $form, 'Les vidéos données ne proviennent pas de Youtube.');
            }
            $figure->setVideos($videos);

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
    public function modifyFigure($id, Figure $figure, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (null === $figure = $em->getRepository(Figure::class)->find($id)) {
            throw $this->createNotFoundException('No figure found for id '.$id);
        }

        $originalPictures = new ArrayCollection();

        foreach ($figure->getPictures() as $picture) {
            $originalPictures->add($picture);
        }

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($originalPictures as $picture) {
                if (false === $figure->getPictures()->contains($picture)) {
                    $picture->setFigure(null);

                    $em->persist($picture);
                }
            }

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
