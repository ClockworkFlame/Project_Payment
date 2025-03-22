<?php
namespace Src\Controller;

use Src\Service\DataImporter;
use Src\Module\Balance;

final class ProcessCommissions
{
    readonly array $csv_data;

    public function __construct() {
        try {
            $csv_data = DataImporter::importData();
        } catch (\Exception $e) {
            print $e->getMessage();
            exit;
        }
    }
}