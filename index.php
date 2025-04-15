<?php
session_start();
require_once 'vendor/autoload.php';

use Src\Controller\ProcessFees;

$pc = new ProcessFees;

if(php_sapi_name() == "cli") {
    $pc->printFees();
} else {
    $pc->printFeesHtml();
}

// Helper function for dumping
function pre($q) {
    echo '<pre>';
    print_r($q);
    echo '</pre>';
}


