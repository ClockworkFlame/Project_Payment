<?php
namespace Src\Controller;

use Src\Service\DataImporter;
use Src\Module\Balance;

final class ProcessFees
{
    readonly array $csv_data;
    readonly array $user_data;

    public private(set) array $fees;

    public function __construct() {
        try {
            $this->csv_data = DataImporter::orderByUser(DataImporter::importData());
            $this->user_data = DataImporter::importUserData();
        } catch (\Exception $e) {
            print $e->getMessage();
            exit;
        }

        $this->process();
    }

    /**
     * Does all of the commission 'fee' processing.
     */
    private function process():void {
        foreach($this->csv_data as $user_id => $transactions) {
            $user_balance = new Balance($user_id, $this->user_data[$user_id]['type']);

            foreach($transactions as $transaction) {
                $user_balance->transact($transaction);
            }

            if(!empty(($fees = $user_balance->getFees()))) {
                foreach($fees as $fee) {
                    $this->fees[$fee['transaction_id']] = $fee;
                }
            }
        }
    }

    public function printFeesHtml():void {
        $fees = $this->fees;

        usort($fees, function($a, $b) {
            return $a['transaction_id'] <=> $b['transaction_id'];
        });

        foreach($fees as $fee) {
            print "Transaction ID: {$fee['transaction_id']} | Fee: " . number_format($fee['fee'], 2) . "<br>";
        }
    }

    public function printFees():void {
        $fees = $this->fees;

        usort($fees, function($a, $b) {
            return $a['transaction_id'] <=> $b['transaction_id'];
        });

        foreach($fees as $fee) {
            echo number_format($fee['fee'], 2) . PHP_EOL;
        }
    }

}