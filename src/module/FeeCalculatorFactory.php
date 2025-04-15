<?php
namespace Src\Module;

use Src\Interface\FeeCalculatable;
use Src\Module\BusinessFeeCalculator;
use Src\Module\PrivateFeeCalculator;
use Src\Enum\UserType;
use Src\Interface\FeeCalculator as FeeCalculatorInterface;

/**
 * Factory class for creating fee calculators based on user type.
 * Just one method, however I think it should be its separate class as I can see it being futher developed in the future.
 */
class FeeCalculatorFactory
{
    public static function getFeeCalculator(FeeCalculatable $balance): FeeCalculatorInterface {
        if($balance->getUserType() === UserType::USER_BUSINESS->value) {
            return new BusinessFeeCalculator($balance);
        } elseif ($balance->getUserType() === UserType::USER_PRIVATE->value) {
            return new PrivateFeeCalculator($balance);
        } else {
            throw new \Exception('User type not found in UserType enum.');
        }
    }
}
