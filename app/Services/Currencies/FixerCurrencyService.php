<?php


namespace App\Services\Currencies;


use App\Services\Currencies\Contracts\Currency;
use App\Services\Currencies\Contracts\CurrencyContract;
use App\Services\Currencies\Contracts\CurrencyException;
use Debugbar;
use Illuminate\Support\Facades\Cache;

class FixerCurrencyService implements CurrencyContract
{
    protected $cacheTime = 120;
    public function __construct()
    {
    }

    public function callApi(string $endpoint, $params = [])
    {
        $access_key = config("currency.fixer.api_key");
        $base_url = config("currency.fixer.base_url");

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "".$base_url."".$endpoint."",
            
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/plain",
                "apikey: ".$access_key
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    /**
     * @inheritDoc
     */
    public function convert(string $from, string $to, float $sum): float
    {
        $data = $this->callApi("convert?to=".$to."&from=".$from."&amount=".$sum);
        $result = $data['result'];
        return round($result, 2);
    }

    /**
     * @inheritDoc
     */
    public function list(): array
    {
        $res = Cache::get("fixer_symbols");
        if (!$res){
            $data = $this->callApi('symbols');
            $res =  $data['symbols'];
            Cache::set("fixer_symbols", $res , $this->cacheTime);
        }

        return $res;
    }
}