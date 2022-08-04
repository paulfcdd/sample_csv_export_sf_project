<?php

namespace App\Service;

use App\Entity\Timetracker;

class CsvExporter
{
    public function generateFile(array $data)
    {
        $outputBuffer = fopen("php://output", 'w');
        /** @var Timetracker $timetrack */
        foreach($data as $timetrack) {
            fputcsv($outputBuffer, [$timetrack->getCreatedAt()->format('d/m/Y'), $timetrack->getTimeSpent(), $timetrack->getDescription(), $timetrack->getUser()->getEmail()]);
        }
        fclose($outputBuffer);
        exit;
    }
}