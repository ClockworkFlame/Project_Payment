<?php

declare(strict_types=1);

namespace Project\Tests;

use PHPUnit\Framework\TestCase;
use Src\Controller\ProcessFees;

class ProcessFeesTest extends TestCase
{
    public function testFeeCalculation()
    {
        $pc = new ProcessFees();

        $result = [ 
            0 => [ 
                "transaction_id" => 0,
                "amount" => 1200,
                "fee" => 0.6,
                "currency" => "EUR",
            ],
            1 => [ 
                "transaction_id" => 1,
                "amount" => 1000,
                "fee" => 3,
                "currency" => "EUR",
            ],
            2 => [ 
                "transaction_id" => 2,
                "amount" => 1000,
                "fee" => 0,
                "currency" => "EUR",
            ],
            3 => [ 
                "transaction_id" => 3,
                "amount" => 200,
                "fee" => 0.06,
                "currency" => "EUR",
            ],
            5 => [ 
                "transaction_id" => 5,
                "amount" => 30000,
                "fee" => 0,
                "currency" => "JPY",
            ],
            6 => [ 
                "transaction_id" => 6,
                "amount" => 1000,
                "fee" => 0.7,
                "currency" => "EUR",
            ],
            7 => [ 
                "transaction_id" => 7,
                "amount" => 100,
                "fee" => 0.3,
                "currency" => "USD",
            ],
            8 => [ 
                "transaction_id" => 8,
                "amount" => 100,
                "fee" => 0.3,
                "currency" => "EUR",
            ],
            11 => [ 
                "transaction_id" => 11,
                "amount" => 300,
                "fee" => 0,
                "currency" => "EUR",
            ],
            4 => [ 
                "transaction_id" => 4,
                "amount" => 300,
                "fee" => 1.5,
                "currency" => "EUR",
            ],
            9 => [ 
                "transaction_id" => 9,
                "amount" => 10000,
                "fee" => 3,
                "currency" => "EUR",
            ],
            10 => [ 
                "transaction_id" => 10,
                "amount" => 1000,
                "fee" => 0,
                "currency" => "EUR",
            ],
            12 => [ 
                "transaction_id" => 12,
                "amount" => 3000000,
                "fee" => 8611.41,
                "currency" => "JPY",
            ],
        ];

        $this->assertEquals($result,$pc->fees);
    }
}
