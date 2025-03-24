<?php
namespace Src\Module;

use Src\Interface\FeeCalculatable;
use Src\Module\FeeCalculatorFactory;
use Src\Service\CurrencyConverter;

/**
 * Processes a user's operations. Acts as a cache for all of their transations so we can calculate fees further on.
 */
final class Balance implements FeeCalculatable
{
    private array $transaction_history = []; // Stores transations for future reference when determining commission
    private array $tax_history = []; // Stores commissions by transaction_id

    public function __construct(
        private readonly int $user_id,
        public readonly string $user_type
    ) {}

    // Does any transaction related operations (of which there are none, besides commission calculation)
    // Tempted to make a 'Transaction' model to pass around, but It would overly complicate this task imo.
    public function transact(array $transaction):bool {
        list($date, $user_id, $type, $action, $amount, $currency, $id) = $transaction;

        $tax_calculator = FeeCalculatorFactory::getFeeCalculator($this);
        // pre($transaction);
        // pre($tax_calculator);

        $this->addToTaxHistory($id, $tax_calculator->getFee($action, $amount, $currency), $currency);
        $this->addToTransactionHistory($id, $action, $amount, $currency, $date);
        return true;
    }

    public function getUserId():int {
        return $this->user_id;
    }

    public function getUserType():string {
        return $this->user_type;
    }

    public function getTransationHistory():array {
        return $this->transaction_history;
    }

    public function getCommissions():array {
        return $this->tax_history;
    }

     
    

    // Gets us the transaction total amount
    public function getTransactionHistoryTotalEur():float {
        $sum = 0;
        foreach($this->transaction_history as $transaction) {
            $start_of_week = strtotime('monday this week', strtotime($transaction['date']))

            if($transaction['currency'] !== Balance::DEFAULT_CURRENCY) {
                $converted_amount = CurrencyConverter::convertToEuro($transaction['currency'], $transaction['amount']);
                $sum += $converted_amount;
            } else {
                $amount = $transaction['amount'];
            }

            $sum += $amount;
        }
        return $sum;
    }

    public function addToTaxHistory(int $id, float $amount, string $currency):void {
        $this->tax_history[] = [
            'transaction_id' => $id,
            'amount' => $amount,
            'currency' => $currency 
        ];
        pre($this->tax_history);
    }

    private function addToTransactionHistory(int $id, string $action, float $amount, string $currency, string $date):void {
        $this->transaction_history[] = [
            'id' => $id,
            'action' => $action,
            'amount' => $amount,
            'date' => $date,
            'currency' => $currency
        ];
    }
}