<?php
namespace App\Http\Middleware;
use App\ProductPrice;
use Closure;
use App;
use Config;
use Session;
use App\Helpers\IPInfoDB;

class Currency
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
   public function handle($request, Closure $next)
   {
      $currency =  $request->session()->get('currency');
      if( !isset($currency) )
      {
        $apiKey='d67d7e7486eb5eb5804eb053e54e09e8c301ef910cef3390bd60610c7015ad04';
        $ip = App\Helpers\Thumbnail::getclientip();
        $ipData = new IPInfoDB($apiKey);

        $country_data = $ipData->getCountry($ip);

        $city_data = $ipData->getCity($ip);

        $countryCode = is_object($country_data) && isset($country_data->countryCode) ? $country_data->countryCode : null;

        $data = $countryCode
                    ? ProductPrice::select('product_prices.alphacode', 'product_prices.symbol')
                        ->join('country', 'country.cntry_id', 'product_prices.country')
                        ->where('country.code', $countryCode)
                        ->first()
                    : null;

        //if we will get current country_data into product_prices table then set data to session
        $crrency = isset($data->alphacode) ? $data->alphacode : 'INR';
        $currency_symbol = isset($data->symbol) ? $data->symbol : '₹';
        $country_code = isset($data->alphacode) ? $countryCode  : 'IN';

        session()->put('currency', $crrency);
        session()->put('currency_symbol', $currency_symbol);
        session()->put('country_code', $country_code);

        // print_r($country_data);
        // die;

      } 

      return $next($request);
   }
 }
?>