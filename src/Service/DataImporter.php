<?php
namespace Src\Service;

use Src\Interface\DataImporter as DataImporterInterface;

class DataImporter implements DataImporterInterface
{
    const FILENAME = 'input.csv'; // Dont want to hardcode but its just an test app.

    /**
     * TODO: Verify data fits mold.
     */
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

    // Adds an internal array index to each row. Either this or making a whole ORM which Id rather not.
    public static function indexArray(array $array):array {
        foreach($array as $key => $row) {
            $array[$key][6] = $key;
        }
        return $array;
    }

    // Exports user data from CSV. That is, assuming that a user can be EITHER private or public.
    public static function importUserData():array {
        $data = self::importData();

        $user_data = [];
        foreach($data as $row) {
            $user_data[$row[1]] = [
                'id' => $row[1],
                'type' => $row[2],
            ];
        }

        return $user_data;
    }
}