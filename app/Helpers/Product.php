<?php

namespace App\Helpers;

class Product {
	public static function priceRatio($max_value,$min_value) {
		$value=$max_value-$min_value;
		$ratio=floor($value/3);
		return $ratio;
	}
	
	public static function discount($saleprice,$price) {
		
      $discount = round((($price-$saleprice)/$price)*100);
      return $discount;
	}
	public static function discountonSale($discount,$price) {
		
      $sale_price=$price-(($price*$discount)/100);
      return $sale_price;
	}
	
	public static function unitPrice($product_sale_price,$discount)
	{
		$unitprice = $product_sale_price-(($product_sale_price*$discount)/100);
		return $unitprice;
	}
	public static function averageRating($reviewDetail)
	{
		$rate=0;
		foreach($reviewDetail as $value)
		{
			$rate=$rate+$value->rate;
		}
		return round($rate/count($reviewDetail),2);
	}
	
	public static function admincommission($total_price,$discount)
	{
		$comm = ($total_price*$discount)/100;
		return $comm;
	}
	public static function parseStringToInt($price){
		
		$prices=str_replace(',','',$price);
		
		return $prices;
	}
	
	public static function discountonCart($discount,$price) {

		$discountprice =(($price*$discount)/100);
		return $discountprice;
	}
	

}
