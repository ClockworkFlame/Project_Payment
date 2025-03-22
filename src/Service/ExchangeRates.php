<?php
namespace Src\Service;

class ExchangeRates
{

    // Havent spent too much time breaking up this function. Found it on stackoverflow. I guess this is what AI is used for?
    public static function fetch():array {
        $key = parse_ini_file("config/keys.ini")['exchangeragesapi']; // Fetch API key

        if(empty($key)) {
            throw new \Exception('No API key set for exchange rates.');    
        }

        $req_url = "https://v6.exchangerate-api.com/v6/$key/latest/EUR"; //Yes its locked to Euros.
        $response_json = file_get_contents($req_url);

        // Continuing if we got a result
        if(false !== $response_json) {

            // Try/catch for json_decode operation
            try {

                // Decoding
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

        return $response->conversion_rates;
    }
}