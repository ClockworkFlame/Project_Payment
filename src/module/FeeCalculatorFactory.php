<?php
namespace Src\Module;

use Src\Interface\FeeCalculatable;
use Src\Module\BusinessFeeCalculator;
use Src\Module\PrivateFeeCalculator;
use Src\Enum\UserType;

/**
 * There could be any nummber of client types that necessitate their own fee calculation classes.
 * Futureproofing with a factory.
 */
class FeeCalculatorFactory
{
    // This should dynamically call classes, but Im not sure if thats an antipattern or not so Ill keep it concrete for now.
    public static function getFeeCalculator(FeeCalculatable $balance) {
        if($balance->user_type === UserType::USER_BUSINESS->value) {
            return new BusinessFeeCalculator($balance);
        } elseif ($balance->user_type === UserType::USER_PRIVATE->value) {
            return new PrivateFeeCalculator($balance);
        } else {
            throw new \Exception('User type not found in UserType enum.');
        }
    }
}
