<?php
namespace Src\Module;

use Src\Interface\FeeCalculatable;
use Src\Module\FeeCalculatorFactory;
use Src\Service\CurrencyConverter;
use Src\Model\Transaction;

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
    public function transact(Transaction $transaction):bool {
        $tax_calculator = FeeCalculatorFactory::getFeeCalculator($this);
        $fee = $tax_calculator->getFee($transaction);

        $this->addToTaxHistory($transaction, $fee);
        $this->addToTransactionHistory($transaction);
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

     
    

    // Gets us the transaction total amount for the given day of the week
    public function getTransactedAmountForWeek(int $timestamp):float {
        $sum = 0;
        foreach($this->transaction_history as $transaction) {
            $start_of_week = strtotime('monday this week', strtotime($transaction['date']));

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

    public function addToTaxHistory(Transaction $transaction):void {
        $this->tax_history[] = [
            'transaction_id' => $$transaction->id,
            'amount' => $$transaction->amount,
            'currency' => $$transaction->currency 
        ];
        pre($this->tax_history);
    }

    private function addToTransactionHistory(Transaction $transaction):void {
        $this->transaction_history[] = [
            'id' => $transaction->id,
            'action' => $transaction->action,
            'amount' => $transaction->amount,
            'date' => $transaction->date,
            'currency' => $transaction->currency
        ];
    }
}