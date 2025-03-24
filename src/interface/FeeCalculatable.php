<?php 
namespace Src\Interface;

// Interface guaranteeing all necessary data for calculating fees is present
interface FeeCalculatable
{
    const DEFAULT_CURRENCY = 'EUR';

    public function getTransactionHistoryForWeek(int $timestamp, string $action):array;

    public function getTransactedAmountForWeekEur(int $timestamp, string $action):float;
}