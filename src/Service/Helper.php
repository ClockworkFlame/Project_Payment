<?php
namespace Src\Service;

static class Helper
{
    public static function getStartOfWeek(int $timestamp):array {
        return date("Y-m-d", strtotime('monday this week', $timestamp);
    }

    public static function getEndOfWeek(int $timestamp):array {
        return strtotime('sunday this week', strtotime($timestamp));
    }
}