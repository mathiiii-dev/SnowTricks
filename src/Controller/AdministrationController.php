<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Entity\Report;
use App\Repository\ReportRepository;
use App\Services\FlashService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdministrationController extends AbstractController
{
    private $reportRepository;
    private $entityManager;
    private $flash;

    public function __construct(
        ReportRepository $reportRepository,
        EntityManagerInterface $entityManager,
        FlashService $flash
    ) {
        $this->reportRepository = $reportRepository;
        $this->entityManager = $entityManager;
        $this->flash = $flash;
    }

    /**
     * @Route("/administration", name="snowtricks_administration")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(): Response
    {
        return $this->render('administration/index.html.twig', [
            'reports' => $this->reportRepository->findAll(),
        ]);
    }

    /**
     * @Route("/administration/validate/message/{discussion}",
     *     name="snowtricks_administration_validate_message",
     *     requirements={"discussion"="\d+"},
     *     methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function validate(Discussion $discussion): RedirectResponse
    {
        $reports = $this->entityManager->getRepository(Report::class)->findBy(['discussion' => $discussion->getId()]);

        foreach ($reports as $report) {
            $this->entityManager->remove($report);
        }

        $this->entityManager->flush();

        $this->flash->setFlashMessages(http_response_code(), 'Message validé !');

        return $this->redirectToRoute('snowtricks_administration');
    }

    /**
     * @Route("/administration/delete/message/{discussion}",
     *     name="snowtricks_administration_delete_message",
     *     requirements={"discussion"="\d+"},
     *     methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Discussion $discussion): RedirectResponse
    {
        $reports = $this->entityManager->getRepository(Report::class)->findBy(['discussion' => $discussion->getId()]);

        foreach ($reports as $report) {
            $this->entityManager->remove($report);
        }

        $this->entityManager->remove($discussion);

        $this->entityManager->flush();

        $this->flash->setFlashMessages(http_response_code(), 'Message supprimé !');

        return $this->redirectToRoute('snowtricks_administration');
    }
}
