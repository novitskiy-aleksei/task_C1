<?php

namespace Parser\Core\Preservers;


class CsvSaver implements StatisticPreserverInterface
{
    private $path = __DIR__ . '/../../../web/';

    /**
     * @inheritdoc
     */
    public function save($statistic)
    {
        $fileName = "statistic_" . time() . ".csv";
        $output = '';

        if (!$fileHandler = fopen($this->path . $fileName, 'w')) {
            return $output;
        }

        if (fputcsv($fileHandler, [$statistic])) {
            $output = "\r\nCSV file written in web/$fileName\r\n";
        }

        fclose($fileHandler);

        return $output;
    }
}
