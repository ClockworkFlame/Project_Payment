<?php
namespace Src\Service;

class CurrencyConverter
{
    // Limiting usage just to euro due to this being just a side-service to the test app.
    public static function convertToEuro(string $currency, float $amount): float {
        $rates = self::getRates();
        pre($rates[$currency]);

        if(empty($rates[$currency])){
            throw new \Exception("Currency $currency doesnt exist in exchange table");
        }

        return round(($amount * $rates["$currency"]), 3);
    }

    public static function getRates() {
        if(session_status() === PHP_SESSION_NONE || empty($_SESSION['rates'])){
            self::cacheRates();
            echo 1;
        }

        return $_SESSION['rates'];
    }
    
    private static function cacheRates():void {
        $_SESSION['rates'] = self::fetchRates();
    }

    // Havent spent too much time on this module. Found most off it on stackoverflow. I guess this is what AI is used for?
    private static function fetchRates():array {
        $key = parse_ini_file("config/keys.ini")['exchangeragesapi']; // Fetch API key

        if(empty($key)) {
            throw new \Exception('No API key set for exchange rates.');    
        }

        $req_url = "https://v6.exchangerate-api.com/v6/$key/latest/EUR"; // Yes its locked to Euros.
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