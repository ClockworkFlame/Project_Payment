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

        foreach($this->csv_data as $user_id => $transactions) {
            $user_balance = new Balance($user_id, $this->user_data[$user_id]['type']);

            foreach($transactions as $transaction) {
                $user_balance->transact($transaction);
            }

            if(!empty(($commissions = $user_balance->getCommissions()))) {
                foreach($commissions as $commission) {
                    $this->commissions[$commission['transaction_id']] = $commission;
                }
            }
        }

        $this->printFeesById();
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

    public function printFeesById():void {
        //order $this->comissions array by $this->comissions['transaction_id']
        $commissions = $this->commissions;

        usort($commissions, function($a, $b) {
            return $a['transaction_id'] <=> $b['transaction_id'];
        });

        foreach($commissions as $commission) {
            print "Transaction ID: {$commission['transaction_id']} | Fee: {$commission['amount']} {$commission['currency']}<br>";
        }
    }

}