<?php

namespace App\Controller;

use App\Entity\Timetracker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app.index')]
    public function indexPage()
    {
        return $this->render('app/index.html.twig', [
            'timetrackerEntries' => $this->getUser() ? $this->entityManager->getRepository(Timetracker::class)->findBy(['user' => $this->getUser()->getId()]) : null,
        ]);
    }

    #[Route('/timesheet/save', name: 'app.timesheet.save', methods: ['post'])]
    public function saveTimesheetEntry(Request $request)
    {
        $timetracker = new Timetracker();
        $timetracker->setDescription($request->request->get('description'));
        $timetracker->setTimeSpent($request->request->get('loggedTime'));
        $timetracker->setUser($this->getUser());

        $this->entityManager->persist($timetracker);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Entry saved',
            'redirectPath' => $this->generateUrl('app.index')
        ]);
    }
}