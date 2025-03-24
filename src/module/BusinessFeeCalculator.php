<?php
namespace Src\Module;

use Src\Module\FeeCalculator;

final class BusinessFeeCalculator extends FeeCalculator
{
    const WITHDRAW_FEE = 0.5;

    protected function calculateDepositFee(float $amount):float {
        $fee_percentage = BusinessFeeCalculator::DEPOSIT_FEE;
        $fee = round($amount * $fee_percentage) / 100;
        return $fee;
    }

    protected function calculateWithdrawFee(float $amount):float {
        $fee_percentage = BusinessFeeCalculator::WITHDRAW_FEE;
        $fee = round($amount * $fee_percentage) / 100;
        return $fee;
    }
}

// Deposit rule
//     All deposits are charged 0.03% of deposit amount.
// Business Clients
//     Commission fee - 0.5% from withdrawn amount.