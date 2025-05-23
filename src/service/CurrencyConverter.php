<?php
namespace Src\Service;

class CurrencyConverter
{
    const DEFAULT_CURRENCY = "EUR";
    const TEST_RATES = [
        "JPY" => 129.53,
        "USD" => 1.1497,
    ];

    // Limiting usage just to euro due to this being just a side-service to the test app.
    public static function convertToEuro(string $currency, float $amount): float {
        $rates = self::getRates();

        if(empty($rates[$currency])){
            throw new \Exception("Currency $currency doesnt exist in exchange table");
        }

        return round(($amount / $rates["$currency"]), 3);
    }

    public static function convertFromEuro(float $amount, string $currency): float {
        $rates = self::getRates();

        if(empty($rates[$currency])){
            throw new \Exception("Currency $currency doesnt exist in exchange table");
        }

        $converted = round(($amount * $rates["$currency"]), 3);

        return $converted;
    }

    // Fetches rates from cache or API
    public static function getRates() {
        $use_api = !empty(parse_ini_file("config/keys.ini")['exchangeragesapi']);

        unset ($_SESSION['rates']);
        if($use_api === false || session_status() === PHP_SESSION_NONE || empty($_SESSION['rates'])){
            if($use_api === true){ 
                $_SESSION['rates'] = self::fetchRates();
            } else {
                $_SESSION['rates'] = self::TEST_RATES;
            }
        }

        $rates = $_SESSION['rates'];

        return $rates;
    }

    // Havent spent too much time on this module. Found most off it on stackoverflow. I guess this is what Copilot would help with?
    private static function fetchRates():array {
        $key = parse_ini_file("config/keys.ini")['exchangeragesapi']; // Fetch API key

        if(empty($key)) {
            throw new \Exception('No API key set for exchange rates.');    
        }

        $req_url = "https://v6.exchangerate-api.com/v6/$key/latest/" . CurrencyConverter::DEFAULT_CURRENCY; // Yes its locked to Euros.
        $response_json = file_get_contents($req_url);

        if(false !== $response_json) {
            try {
                $response = json_decode($response_json);

                // Check for success
                if('success' !== $response->result) {
                    throw new \Exception('Couldnt fetch exchange rates.');    
                }
            }
            catch(Exception $e) {
                throw new \Exception('Couldnt decode rates JSON');
            }
        }

        return (array) $response->conversion_rates;
    }
}