<?php
session_start();
require_once 'vendor/autoload.php';

use Src\Controller\ProcessCommissions;
use Src\Service\CurrencyConverter;

$pc = new ProcessCommissions;

// pre(CurrencyConverter::convertToEuro('USD', 100));

// Helper function for dumping
function pre($q) {
    echo '<pre>';
    print_r($q);
    echo '</pre>';
}


