<?php
namespace Src\Module;

use Src\Interface\FeeCalculatable;
use Src\Service\CurrencyConverter;

/**
 * Abstract class for calculating transaction fees.
 * Logically we wont need more than 2 child classes as all transactions are either deposit/withdraw, right?
 */
abstract class FeeCalculator
{
    const DEFAULT_FEE_AMOUNT = 0.00;
    const DEPOSIT_FEE = 0.03;

    public function __construct(readonly FeeCalculatable $balance) {}

    public function getDefaultFee():float {
        return FeeCalculator::DEFAULT_FEE_AMOUNT;
    }

    public function getFee(string $action, float $amount, string $currency):float {
        $fee = match($action) {
            'deposit' => $this->calculateDepositFee($amount),
            'withdraw' => $this->calculateWithdrawFee($amount, $currency),
        };

        //throw exception if fee isnt float
        if(!is_float($fee)) {
            //dump method arguments for debugging
            throw new \Exception('Fee must be a float!');
        }

        return $fee;
    }

    abstract protected function calculateDepositFee(float $amount):float;

    abstract protected function calculateWithdrawFee(float $amount):float;
}