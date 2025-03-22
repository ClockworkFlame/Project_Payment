<?php
require_once 'vendor/autoload.php';

use Src\Controller\ProcessCommissions;
use Src\Service\ExchangeRates;

$pc = new ProcessCommissions;

ExchangeRates::fetch();



// Helper function for dumping
function pre($q) {
    echo '<pre>';
    print_r($q);
    echo '</pre>';
}


