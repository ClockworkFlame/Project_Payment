<?php
namespace Src\Module;

/**
 * Processes a user's operations. Acts as a cache for all of their transations so we can calculate fees further on.
 */
final class Balance
{
    private array $transation_history; // Stores transations for future reference when determining commission
    private array $commissions_history; // Stores commissions by transaction_id

    public function __construct(
        private readonly int $user_id,
        private readonly string $user_type
    ) {}

    public function transact(string $action, float $amount, string $currency):float {
        // make child classes of fee calculators, call dynamically from here, and record commission amount.

        return 0.0;
    }

    public function getUserId():int {
        return $this->user_id;
    }

    public function getUserType():string {
        return $this->user_type;
    }

    public function getTransationHistory():array {
        return $this->transation_history;
    }
}