<?php
namespace Src\Model;

class Transaction
{
    // for ease of use later on.
    readonly int $date_timestamp;

    public function __construct(
        readonly int $id,
        readonly int $user_id,
        readonly string $user_type,
        readonly string $date,
        readonly float $amount,
        readonly string $currency,
        readonly string $action
    ) {
        $this->date_timestamp = strtotime($this->date);
    }
}