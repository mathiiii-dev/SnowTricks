<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Entity\Figure;
use App\Repository\FigureRepository;
use App\Repository\UserRepository;
use App\Services\FlashService;
use App\Services\FormService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiscussionController extends AbstractController
{
    private $entityManager;
    private $userRepository;
    private $figureRepository;
    private $flash;
    private $formService;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, FormService $formService, FigureRepository $figureRepository, FlashService $flash)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->figureRepository = $figureRepository;
        $this->flash = $flash;
        $this->formService = $formService;
    }

    /**
     * @Route("/send-message/{figure}", name="snowtricks_send_message")
     * @return Response
     */
    public function sendMessage(Figure $figure, Request $request): Response
    {
        if ($request->getMethod() === Request::METHOD_POST) {

            $discussion = new Discussion();

            $message = $request->request->get('message');
            $checkDiscussion = $this->formService->checkDiscussion($message);
            if ($checkDiscussion) {
                $user = $this->userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);

                $discussion->setMessage($message);
                $discussion->setUser($user);
                $discussion->setFigure($figure);

                $this->entityManager->persist($discussion);
                $this->entityManager->flush();

                $this->flash->setFlashMessages(http_response_code(), 'Message envoyé !');

                return $this->redirectToRoute('snowtricks_figure', [
                    'figure' => $figure->getId()
                ]);
            }

            return $this->redirectToRoute('snowtricks_figure', [
                'figure' => $figure->getId()
            ]);

        }
    }
}