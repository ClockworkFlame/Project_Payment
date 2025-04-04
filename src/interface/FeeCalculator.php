<?php 
namespace Src\Interface;

use Src\Model\Transaction;

interface FeeCalculator
{
    public function getDefaultFee():float;

    public function getFee(Transaction $transaction):float;
}