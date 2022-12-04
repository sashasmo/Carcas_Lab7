<?php


namespace App\Http\Controllers;


use App\Http\Requests\ConvertRequest;
use App\Services\Currencies\Contracts\CurrencyContract;
use Debugbar;

class ConverterController extends Controller
{
    /**
     * @var CurrencyContract
     */
    protected $currencyService;

    public function __construct(CurrencyContract $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function index()
    {
        return view("converter.index", [
            "currencies" => $this->currencyService->list(),
        ]);
    }

    public function convert(ConvertRequest $request)
    {
        $data;
        try {
            $result = $this->currencyService->convert($request->currency_from, $request->currency_to, $request->sum);
            var_dump($result);
            $response = redirect(route("converter.index"));
            $request->session()->flash("Converted", $result);
            $request->session()->flash("currency_from", $request->currency_from);
            $request->session()->flash("currency_to", $request->currency_to);
            $request->session()->flash("sum", $request->sum);
        } catch (\Exception $exception) {
            var_dump($exception);
            $response = redirect(route("converter.index"));
        }

        return  $response;
    }

}
