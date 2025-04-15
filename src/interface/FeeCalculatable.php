<?php 
namespace Src\Interface;

// Interface guaranteeing all necessary data for calculating fees is present.
// Yes its bloated, but without ORMing the User data (due to project criteria), it would be a massive overcomplication to redesign how we pull UserId and UserType
interface FeeCalculatable
{
    const DEFAULT_CURRENCY = 'EUR';

    public function getTransactionHistoryForWeek(int $timestamp, string $action):array;
    public function getTransactedAmountForWeekEur(int $timestamp, string $action):float;
    public function getUserId():int;
    public function getUserType():string;
    public function getTransationHistory():array;
    public function getFees():array;
}