<?php
namespace Src\Module;

use Src\Module\FeeCalculator;
use Src\Model\Transaction;
use Src\Service\CurrencyConverter;


final class PrivateFeeCalculator extends FeeCalculator
{
    const WITHDRAW_FEE = 0.3;
    const WEEKLY_FREE_AMOUNT = 1000; // Euro
    const WEEKLY_FREE_COUNT = 3;

    protected function calculateDepositFee(Transaction $transaction):float {
        $fee_percentage = self::DEPOSIT_FEE;
        $fee = round($transaction->amount * $fee_percentage) / 100;
        return $fee;
    }

    protected function calculateWithdrawFee(Transaction $transaction):float {
        $weekly_free_amount = $this->getRemainingWeeklyFree($transaction);
        $feeable_amount = $transaction->amount; // Amount we'll calculate fee for. It can be overwritten.
        
        if($weekly_free_amount > 0) { // If user hasnt used up their weekly free amount
            
            // In case the free amount is of a different currency, convert it to the transaction's
            if($transaction->currency !== CurrencyConverter::DEFAULT_CURRENCY) {
                $weekly_free_amount = CurrencyConverter::convertFromEuro($weekly_free_amount, $transaction->currency);
            }

            if($transaction->amount < $weekly_free_amount) { // If the transaction is within the weekly free amount
                return self::DEFAULT_FEE_AMOUNT;
            } else { // Else calculate the remainder that we'll need to tax normally
                $feeable_amount = $transaction->amount - $weekly_free_amount;
            }
        }

        $fee = round($feeable_amount * self::WITHDRAW_FEE) / 100;
        return $fee;
    }

    // 0.3 of 368.393 = 110.5179

    // Calculates remaining free of fee amount for the week
    // This ALWAYS returns in Euro
    private function getRemainingWeeklyFree(Transaction $transaction): float {
        $transaction_amount_this_week_eur = $this->balance->getTransactedAmountForWeekEur($transaction->date_timestamp, $transaction->action) ?? 0.00;
        $transactions_this_week = $this->balance->getTransactionHistoryForWeek($transaction->date_timestamp, $transaction->action);
        
        $weekly_free = 0.00;
        if($transaction_amount_this_week_eur < self::WEEKLY_FREE_AMOUNT ){
            if(count($transactions_this_week) < self::WEEKLY_FREE_COUNT) {
                $weekly_free = round(self::WEEKLY_FREE_AMOUNT - $transaction_amount_this_week_eur);
            } 
        } 
        
        return $weekly_free;
    }
}

// Deposit rule
//     All deposits are charged 0.03% of deposit amount.

// Private Clients
//     Commission fee - 0.3% from withdrawn amount.
//     1000.00 EUR for a week (from Monday to Sunday) is free of charge. Only for the first 3 withdraw operations per a week.
//     If total free of charge amount is exceeded them commission is calculated only for the exceeded amount (i.e. up to 1000.00 EUR no commission fee is applied).
//     For the second rule you will need to convert operation amount if it's not in Euros. Please use rates provided by https://api.exchangeratesapi.io/latest.