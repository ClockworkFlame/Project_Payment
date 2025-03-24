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
            $this->csv_data = DataImporter::orderByUser(DataImporter::importData());
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

        $this->printFeesHtml();
    }

    public function printFeesHtml():void {
        //order $this->comissions array by $this->comissions['transaction_id']
        $commissions = $this->commissions;

        usort($commissions, function($a, $b) {
            return $a['transaction_id'] <=> $b['transaction_id'];
        });

        foreach($commissions as $commission) {
            print "Transaction ID: {$commission['transaction_id']} | Fee: " . number_format($commission['fee'], 2) . "<br>";
        }
    }

    public function printFees():void {
        //order $this->comissions array by $this->comissions['transaction_id']
        $commissions = $this->commissions;

        usort($commissions, function($a, $b) {
            return $a['transaction_id'] <=> $b['transaction_id'];
        });

        foreach($commissions as $commission) {
            echo number_format($commission['fee'], 2) . PHP_EOL;
        }
    }

}