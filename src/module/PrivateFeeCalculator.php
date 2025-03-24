<?php
namespace Src\Module;

use Src\Module\FeeCalculator;

final class PrivateFeeCalculator extends FeeCalculator
{
    const WITHDRAW_FEE = 0.3;
    const WEEKLY_FREE_AMOUNT = 1000; // Euro
    const WEEKLY_FREE_COUNT = 3;

    protected function calculateDepositFee(float $amount):float {
        $fee_percentage = BusinessFeeCalculator::DEPOSIT_FEE;
        $fee = round($amount * $fee_percentage) / 100;
        return $fee;
    }

    protected function calculateWithdrawFee(float $amount):float {
        $this->hasRemainingWeeklyFree();

        $fee_percentage = BusinessFeeCalculator::WITHDRAW_FEE;



        $fee = round($amount * $fee_percentage) / 100;
        return $fee;
    }


    private function hasRemainingWeeklyFree(): float {
        $transaction_history = $this->balance->getTransationHistory();
        $tramsaction_history_total_eur = $this->balance->getTransactionHistoryTotalEur();
        pre($tramsaction_history_total_eur);


        if($tramsaction_history_total_eur < PrivateFeeCalculator::WEEKLY_FREE_AMOUNT 
            && count($transaction_history) < PrivateFeeCalculator::WEEKLY_FREE_COUNT
        ){
            return PrivateFeeCalculator::WEEKLY_FREE_AMOUNT;
        }

        return false;
    }
}

// Deposit rule
//     All deposits are charged 0.03% of deposit amount.

// Private Clients
//     Commission fee - 0.3% from withdrawn amount.
//     1000.00 EUR for a week (from Monday to Sunday) is free of charge. Only for the first 3 withdraw operations per a week.
//     If total free of charge amount is exceeded them commission is calculated only for the exceeded amount (i.e. up to 1000.00 EUR no commission fee is applied).
//     For the second rule you will need to convert operation amount if it's not in Euros. Please use rates provided by https://api.exchangeratesapi.io/latest.