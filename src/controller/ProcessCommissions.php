<?php
namespace Src\Controller;

use Src\Service\DataImporter;
use Src\Module\Balance;

final class ProcessCommissions
{
    readonly array $csv_data;
    readonly array $user_data;

    public private(set) array $commissions;

    public function __construct() {
        try {
            $this->csv_data = $this->orderByUser(DataImporter::indexArray(DataImporter::importData()));
            $this->user_data = DataImporter::importUserData();
        } catch (\Exception $e) {
            print $e->getMessage();
            exit;
        }

        foreach($this->csv_data as $user_id => $transations) {
            pre($transations);
            $user_balance = new Balance($user_id, $this->user_data[$user_id]['type']);

            foreach($transations as $transation) {
                $user_balance->transact();
            }

            foreach($user_balance->getCommissions() as $commission) {
                $this->commissions[$commission['transaction_id']] = $commission;
            }
        }

    }

    // Not sure where to put this really... But Id rather focus on the rest of the task due to my limited time.
    // This just groups the raw CSV data by user.
    private function orderByUser(array $array):array {
        $return = array();
        foreach($array as $val) {
            $return[$val[1]][] = $val;
        }
        return $return;
    }
}