<?php
namespace Src\Service;

use Src\Interface\DataImporter as DataImporterInterface;

class DataImporter implements DataImporterInterface
{
    const FILENAME = 'input.csv'; // Dont want to hardcode but its just an test app.

    public static function importData():array {
        if(!file_exists(self::FILENAME)) {
            throw new \Exception('CSV file not found');
        }

        $handle = fopen('input.csv','r');
        $csv_data = [];
        while(($data = fgetcsv($handle, escape:"\\") ) !== FALSE ) {
            $csv_data[] = $data;
        }

        if(empty($csv_data)){
            throw new \Exception('CSV file empty');
        }

        return $csv_data;
    }
}