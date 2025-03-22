<?php
namespace Src\Service;

class DataImporter
{
    const FILENAME = 'input.csv'; // Dont want to hardcode but its just an test app.

    public static function importData():array {
        if(!file_exists(self::FILENAME)) {
            throw new \Exception('CSV file not found');
        }

        if(empty(($csv = str_getcsv(file_get_contents('input.csv'), escape:"\\")))){
            throw new \Exception('CSV file empty');
        }

        return $csv;
    }
}