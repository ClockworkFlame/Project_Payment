<?php
namespace Src\Module;

use Src\Module\FeeCalculator;
use Src\Model\Transaction;

final class BusinessFeeCalculator extends FeeCalculator
{
    const WITHDRAW_FEE = 0.5;

    protected function calculateDepositFee(Transaction $transaction):float {
        $fee_percentage = self::DEPOSIT_FEE;
        $fee = round($transaction->amount * $fee_percentage) / 100;
        return $fee;
    }

    protected function calculateWithdrawFee(Transaction $transaction):float {
        $fee_percentage = self::WITHDRAW_FEE;
        $fee = round($transaction->amount * $fee_percentage) / 100;
        return $fee;
    }
}

// Deposit rule
//     All deposits are charged 0.03% of deposit amount.
// Business Clients
//     Commission fee - 0.5% from withdrawn amount.