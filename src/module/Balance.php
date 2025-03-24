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
    protected array $transaction_history = []; // Stores transations for future reference when determining commission
    protected array $fee_history = []; // Stores commissions by transaction_id

    public function __construct(
        protected readonly int $user_id,
        protected readonly string $user_type
    ) {}

    // Does any transaction related operations (of which there are none, besides commission calculation)
    public function transact(Transaction $transaction):bool {
        $fee_calculator = FeeCalculatorFactory::getFeeCalculator($this);
        $fee = $fee_calculator->getFee($transaction);

        $this->addToFeeHistory($transaction, $fee);
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

    public function getFees():array {
        return $this->fee_history;
    }

     
    public function getTransactionHistoryForWeek(int $timestamp, string $action):array {
        $start_of_week = strtotime('monday this week', $timestamp);
        $start_of_following_week = strtotime('monday next week', $timestamp);

        // Filter transactions so we have only the ones for the wanted week
        $transactions_this_week = array_filter($this->transaction_history, function($transaction) use ($start_of_week, $start_of_following_week, $action) {
            return $action === $transaction['action'] && $transaction['date_timestamp'] >= $start_of_week && $transaction['date_timestamp'] < $start_of_following_week;
        });

        return $transactions_this_week;
    }

    // Finds previous transactions for given week and returns the amount transacted in EUR
    public function getTransactedAmountForWeekEur(int $timestamp, string $action):float {
        // Filter transactions so we have only the ones for the wanted week
        $transactions_this_week = $this->getTransactionHistoryForWeek($timestamp, $action);
        
        if(empty($transactions_this_week)) {
            return 0.00;
        }
        
        $sum = 0;
        // Sum up the total amount transacted for wanted week, in euro.
        foreach($transactions_this_week as $transaction) {
            if($transaction['currency'] !== Balance::DEFAULT_CURRENCY) {
                $converted_amount = CurrencyConverter::convertToEuro($transaction['currency'], $transaction['amount']);
                $sum += $converted_amount;
            } else {
                $sum += $transaction['amount'];
            }
        }

        return $sum;
    }

    // Record fees to print out in the future
    public function addToFeeHistory(Transaction $transaction, float $fee):void {
        $this->fee_history[] = [
            'transaction_id' => $transaction->id,
            'amount' => $transaction->amount,
            'fee' => $fee,
            'currency' => $transaction->currency 
        ];
    }

    // Record past transactions as an array so we can calculate fees later on in an optimized way
    private function addToTransactionHistory(Transaction $transaction):void {
        $this->transaction_history[] = [
            'id' => $transaction->id,
            'action' => $transaction->action,
            'amount' => $transaction->amount,
            'date' => $transaction->date,
            'date_timestamp' => $transaction->date_timestamp,
            'currency' => $transaction->currency
        ];
    }
}