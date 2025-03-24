<?php
session_start();
require_once 'vendor/autoload.php';

use Src\Controller\ProcessCommissions;

$pc = new ProcessCommissions;

// Helper function for dumping
function pre($q) {
    echo '<pre>';
    print_r($q);
    echo '</pre>';
}


