<?php

namespace App\Service;

use App\Entity\Timetracker;

class CsvExporter
{
    public function generateFile(array $data, string $filename)
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '_' . date('Ymd') . '.csv"');
        header("Pragma: no-cache");
        header("Expires: 0");
        $outputBuffer = fopen("php://output", 'w');
        /** @var Timetracker $timetrack */
        foreach($data as $timetrack) {
            fputcsv($outputBuffer, [$timetrack->getCreatedAt()->format('d/m/Y'), $timetrack->getTimeSpent(), $timetrack->getDescription(), $timetrack->getUser()->getEmail()]);
        }
        fclose($outputBuffer);
        exit;
    }
}