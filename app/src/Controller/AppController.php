<?php

namespace App\Controller;

use App\Entity\Timetracker;
use App\Service\CsvExporter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CsvExporter $csvExporter;
    public function __construct(EntityManagerInterface $entityManager, CsvExporter $csvExporter)
    {
        $this->entityManager = $entityManager;
        $this->csvExporter = $csvExporter;
    }

    #[Route('/', name: 'app.index')]
    public function indexPage(Request $request)
    {
        if (!empty($request->query->all())) {
            $startDateTimeObject = \DateTime::createFromFormat('d/m/Y', $request->query->get('start'));
            $endDateTimeObject = \DateTime::createFromFormat('d/m/Y', $request->query->get('end'));
            $timetrackerEntries = $this->entityManager->getRepository(Timetracker::class)->getFilteredEntries($this->getUser(), $startDateTimeObject, $endDateTimeObject);
        } else {
            $timetrackerEntries = $this->getUser() ? $this->entityManager->getRepository(Timetracker::class)->findBy(['user' => $this->getUser()->getId()]) : null;
        }

        return $this->render('app/index.html.twig', [
            'timetrackerEntries' => $timetrackerEntries
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

    #[Route('/data/export/csv', name: 'app.data.export.csv', methods: ['get'])]
    public function exportDataToCsv(Request $request)
    {
        $data = $this->entityManager->getRepository(Timetracker::class)->findBy(['user' => $this->getUser()->getId()]);
        $this->csvExporter->generateFile(
            $data,
            'timesheet_report'
        );
        return new JsonResponse();
    }
}