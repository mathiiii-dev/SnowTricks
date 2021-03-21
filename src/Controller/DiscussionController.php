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
     * @Route("/figure/{figure}/messages/send", name="snowtricks_messages_send")
     * @return JsonResponse
     */
    public function sendMessageFigure(Figure $figure, Request $request): JsonResponse
    {
        $discussion = new Discussion();

        $user = $this->userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $discussion->setUser($user);

        $discussion->setFigure($figure);
        $discussion->setMessage($request->getContent());

        $this->entityManager->persist($discussion);
        $this->entityManager->flush();

        return new JsonResponse([
            'code' => http_response_code(),
            'message' => $discussion->getMessage()
        ]);
    }

    /**
     * @Route("/figure/{figure}/messages/get/{offset}", name="snowtricks_messages_get")
     * @return Response
     */
    public function getLastMessages(Figure $figure, string $offset): Response
    {
        $messageArray = [];
        $messages = $this->discussionRepository->findBy(['figure' => $figure], ['id' => 'DESC'], 5, $offset);
        $messagesCount = count($this->discussionRepository->findBy(['figure' => $figure]));

        foreach ($messages as $message) {
            array_push($messageArray, [
                'message' => $message->getMessage(),
                'user' => $message->getUser()->getUsername(),
                'createdAt' => $message->getCreatedAt()->format("d-m-Y h:i:s"),
                'messagesCount' => $messagesCount,
                'profilePicture' => $message->getUser()->getProfilePictureName()
            ]);
        }

        return $this->json($messageArray);
    }

    /**
     * @Route("/figure/{figure}/messages/get-last", name="snowtricks_message_get_last")
     * @return Response
     */
    public function getLastSentMessage(Figure $figure): Response
    {
        $lastMessage = $this->discussionRepository->findOneBy(['figure' => $figure], ['id' => 'DESC']);

        return $this->json([
            'message' => $lastMessage->getMessage(),
            'user' => $lastMessage->getUser()->getUsername(),
            'createdAt' => $lastMessage->getCreatedAt()->format("d-m-Y h:i:s"),
            'profilePicture' => $lastMessage->getUser()->getProfilePictureName()
        ]);
    }
}
