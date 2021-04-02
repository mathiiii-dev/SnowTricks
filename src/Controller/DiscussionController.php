<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Entity\Figure;
use App\Entity\Report;
use App\Form\DiscussionType;
use App\Form\ReportType;
use App\Repository\DiscussionRepository;
use App\Repository\UserRepository;
use App\Services\FlashService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiscussionController extends AbstractController
{
    /** @var EntityManagerInterface **/
    private $entityManager;

    /** @var UserRepository **/
    private $userRepository;

    /** @var DiscussionRepository **/
    private $discussionRepository;

    /** @var FlashService **/
    private $flash;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        DiscussionRepository $discussionRepository,
        FlashService $flash
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->discussionRepository = $discussionRepository;
        $this->flash = $flash;
    }

    public function createFormDiscussion(): FormView
    {
        $discussion = new Discussion();

        $formDiscussion = $this->createForm(DiscussionType::class, $discussion);

        return $formDiscussion->createView();
    }

    /**
     * @Route("/figure/{figure}/messages/send",
     *      name="snowtricks_messages_send",
     *      requirements={"figure"="\d+"},
     *      methods={"POST"})
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
     * @Route("/figure/{figure}/messages/get/{offset}",
     *      name="snowtricks_messages_get",
     *      methods={"GET"})
     */
    public function getLastMessages(Figure $figure, string $offset): Response
    {
        $messageArray = [];
        $messages = $this->discussionRepository->findBy(['figure' => $figure], ['id' => 'DESC'], 10, $offset);
        $messagesCount = count($this->discussionRepository->findBy(['figure' => $figure]));

        foreach ($messages as $message) {
            array_push($messageArray, [
                'message' => $message->getMessage(),
                'user' => $message->getUser()->getUsername(),
                'createdAt' => $message->getCreatedAt()->format("d-m-Y h:i:s"),
                'messagesCount' => $messagesCount,
                'profilePicture' => $message->getUser()->getProfilePictureName(),
                'message_id' => $message->getId()
            ]);
        }

        return $this->json($messageArray);
    }

    /**
     * @Route("/figure/{figure}/messages/get-last",
     *      name="snowtricks_message_get_last",
     *      requirements={"figure"="\d+"},
     *      methods={"GET"})
     * @return Response
     */
    public function getLastSentMessage(Figure $figure): Response
    {
        $lastMessage = $this->discussionRepository->findOneBy(['figure' => $figure], ['id' => 'DESC']);

        return $this->json([
            'message' => $lastMessage->getMessage(),
            'user' => $lastMessage->getUser()->getUsername(),
            'createdAt' => $lastMessage->getCreatedAt()->format("d-m-Y h:i:s"),
            'profilePicture' => $lastMessage->getUser()->getProfilePictureName(),
            'message_id' => $lastMessage->getId()
        ]);
    }

    /**
     * @Route("report/messages/{discussion}", name="snowtricks_message_report")
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function report(Discussion $discussion, Request $request): Response
    {
        $report = new Report();

        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reportMessage = $request->request->get('report');

            $report->setMessage($reportMessage['message']);
            $report->setDiscussion($discussion);
            $report->setUser($this->userRepository->findOneBy(['username' => $this->getUser()->getUsername()]));
            $report->setFigure($discussion->getFigure());
            $report->setCreatedAt();

            $this->entityManager->persist($report);
            $this->entityManager->flush();

            $this->flash->setFlashMessages(http_response_code(), 'Message signalé !');

            return $this->redirectToRoute('snowtricks_figure', ['figure' => $discussion->getFigure()->getId()]);
        }

        return $this->render('figure/report.html.twig', [
            'message' => $discussion->getMessage(),
            'figure' => $discussion->getFigure()->getId(),
            'formReport' => $form->createView(),
        ]);
    }
}
