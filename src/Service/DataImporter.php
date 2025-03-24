<?php
namespace Src\Service;

use Src\Interface\DataImporter as DataImporterInterface;
use Src\Model\Transaction;

class DataImporter implements DataImporterInterface
{
    const FILENAME = 'input.csv'; // Dont want to hardcode but its just a test app.

    // Imports transaction data from CSV
    public static function importData():array {
        if(!file_exists(self::FILENAME)) {
            throw new \Exception('CSV file not found');
        }

        $handle = fopen('input.csv','r');
        $csv_data = [];
        $id_i = 0;
        while(($data = fgetcsv($handle, escape:"\\") ) !== FALSE ) {
            $csv_data[] = new Transaction(
                id: $id_i,
                date: $data[0],
                user_id: $data[1],
                user_type: $data[2],
                action: $data[3],
                amount: $data[4],
                currency: $data[5],
            );

            $id_i++;
        }

        if(empty($csv_data)){
            throw new \Exception('CSV file empty');
        }

        return $csv_data;
    }

    // Imports user data from transactions
    public static function importUserData():array {
        $data = self::importData();

        $user_data = [];
        foreach($data as $transaction) {
            $user_data[$transaction->user_id]= [
                'id' => $transaction->user_id,
                'type' => $transaction->user_type,
            ];
        }

        return $user_data;
    }

    // Orders transactions by user_id for easier processing
    public static function orderByUser(array $array):array {
        $return = array();
        foreach($array as $transaction) {
            $return[$transaction->user_id][] = $transaction;
        }
        return $return;
    }
}