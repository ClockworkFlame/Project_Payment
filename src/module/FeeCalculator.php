<?php
namespace Src\Module;

use Src\Interface\FeeCalculatable;
use Src\Model\Transaction;
use Src\Interface\FeeCalculator as FeeCalculatorInterface;

/**
 * Abstract class for calculating transaction fees.
 * Logically we wont need more than 2 child classes as all transactions are either deposit/withdraw, right?
 */
abstract class FeeCalculator implements FeeCalculatorInterface
{
    const DEFAULT_FEE_AMOUNT = 0.00;
    const DEPOSIT_FEE = 0.03;

    public function __construct(readonly FeeCalculatable $balance) {}

    public function getDefaultFee():float {
        return self::DEFAULT_FEE_AMOUNT;
    }

    public function getFee(Transaction $transaction):float {
        $fee = match($transaction->action) {
            'deposit' => $this->calculateDepositFee($transaction),
            'withdraw' => $this->calculateWithdrawFee($transaction),
        };

        //throw exception if fee isnt float
        if(!is_float($fee)) {
            //dump method arguments for debugging
            throw new \Exception('Fee must be a float!');
        }

        return $fee;
    }

    abstract protected function calculateDepositFee(Transaction $transaction):float;

    abstract protected function calculateWithdrawFee(Transaction $transaction):float;
}