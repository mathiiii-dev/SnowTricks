<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Entity\Figure;
use App\Form\DiscussionType;
use App\Repository\DiscussionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiscussionController extends AbstractController
{
    private $entityManager;
    private $userRepository;
    private $discussionRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, DiscussionRepository $discussionRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->discussionRepository = $discussionRepository;
    }

    public function createFormDiscussion(): FormView
    {
        $discussion = new Discussion();

        $formDiscussion = $this->createForm(DiscussionType::class, $discussion);

        return $formDiscussion->createView();
    }

    /**
     * @Route("/figure/{idFigure}/send-message", name="snowtricks_send_message")
     * @return JsonResponse
     */
    public function sendMessageFigure(Figure $idFigure, Request $request): JsonResponse
    {
        $discussion = new Discussion();

        $user = $this->userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $discussion->setUser($user);

        $discussion->setFigure($idFigure);
        $discussion->setMessage($request->getContent());

        $this->entityManager->persist($discussion);
        $this->entityManager->flush();

        return new JsonResponse([
                'code' => http_response_code(),
                'message' => $discussion->getMessage()
            ]);
    }

    /**
     * @Route("/figure/{idFigure}/get-message/{offset}", name="snowtricks_get_message")
     * @return Response
     */
    public function getLastMessage(Figure $idFigure, string $offset): Response
    {
        $messageArray = [];
        $messages = $this->discussionRepository->findBy(['figure' => $idFigure], ['id' => 'DESC'], 5, $offset);
        $messagesCount = count($this->discussionRepository->findBy(['figure' => $idFigure]));
        foreach ($messages as $message) {
            array_push($messageArray, [
                'message' => $message->getMessage(),
                'user' => $message->getUser()->getUsername(),
                'createdAt' => $message->getCreatedAt(),
                'messagesCount' => $messagesCount,
                'profilePicture' => $message->getUser()->getProfilePicture()
                ]);
        }

        return $this->json($messageArray);
    }

    /**
     * @Route("/figure/{idFigure}/get-last-message", name="snowtricks_get_last_message")
     * @return Response
     */
    public function getLastSentMessage(Figure $idFigure): Response
    {
        $lastMessage = $this->discussionRepository->findOneBy(['figure' => $idFigure], ['id' => 'DESC']);

        return $this->json([
            'message' => $lastMessage->getMessage(),
            'user' => $lastMessage->getUser()->getUsername(),
            'createdAt' => $lastMessage->getCreatedAt(),
            'profilePicture' => $lastMessage->getUser()->getProfilePicture()
        ]);
    }
}
