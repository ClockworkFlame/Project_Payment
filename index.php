<?php
session_start();
require_once 'vendor/autoload.php';

use Src\Controller\FeeProcessor;

$fp = new FeeProcessor;

if(php_sapi_name() == "cli") {
    $fp->printFees();
} else {
    $fp->printFeesHtml();
}
