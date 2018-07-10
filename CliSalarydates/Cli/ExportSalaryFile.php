<?php

namespace CliSalarydates\Cli;

use \CliSalarydates\Exporter as Exporter;

/**
 * Export salary file
 */
class ExportSalaryFile extends Exporter\AbstractSource
{
    /**
     * Export data
     */
    public function _exportData()
    {
        $header = $this->getCsvHeader($this->_monthsSalaryDates, true);
        array_unshift($this->_monthsSalaryDates, "Salary Date");
        array_unshift($this->_monthsBonusDates, "Bonus Date");
        $csvData = [
            $header,
            $this->_monthsSalaryDates,
            $this->_monthsBonusDates
        ];
        
        $file = "export_".rand().".csv";
        $this->downloadCsv($csvData, 'downloads', $file, ",");
    }
    
    /**
     * Download as a CSV
     *
     * @param array $array
     * @param string $filename
     * @param string $delimiter
     * @param string $enclosure
     */
    public function downloadCsv(
        $array,
        $filePath,
        $filename = "export.csv", 
        $delimiter=",", 
        $enclosure = '"'
    ) {
        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'";');
        $filename = $filePath.$GLOBALS['DS'].$filename;
        $f = fopen($filename, 'w');
        foreach ($array as $line) {
            fputcsv($f, $line, $delimiter, $enclosure);
        }
        fclose($f);
        echo "Downloaded csv file path : {$filename}";
    }
    
    /**
     * Download as a CSV
     *   
     * @param array $data
     * @param bool $hasRowTitle
     */
    public function getCsvHeader($data, $hasRowTitle)
    {
        $header = array_keys($data);
        ($hasRowTitle)?array_unshift($header, " "):$header;
        return $header; 
    }
}
