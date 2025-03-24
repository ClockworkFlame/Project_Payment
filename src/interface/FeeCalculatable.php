<?php 
namespace Src\Interface;

// Interface guaranteeing all necessary data for calculating fees is present
interface FeeCalculatable
{
    const DEFAULT_CURRENCY = 'EUR';

    public function getUserId():int;
    public function getUserType():string;
    public function getTransationHistory():array;
}