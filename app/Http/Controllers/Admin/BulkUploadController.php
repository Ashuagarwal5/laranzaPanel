<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Products;
use App\Category;
use App\ProductImages;
use App\SellerDetails;
use Redirect;
use Sentinel; 
use View;
use DB;
use Illuminate\Support\Facades\Input;
use Response;
use Validator;
use Storage;
use App\Helpers\datehelper;


class BulkUploadController extends CodespurController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
	function __construct()
	{
		$this->date=datehelper::dateformat();
	} 
	public function index()
	{	
		$PARENT_ID=28;
		
		
		
		return view('admin.bulk.index',compact('PARENT_ID'));
	}
	
	
	public function get_all_cat()
	{	
		$PARENT_ID=28;
		
		$dataCSV = Category::select('id','category_name')->get()->toArray();
		
		
		$newStart					= date('d-m-Y');

			$fileName					= "Cat_data_".$newStart;
		
			$output = fopen('php://output', 'w');
				  
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename='.$fileName.'.csv');

			
			fputcsv($output, array('Id','Category Name'));  

			foreach($dataCSV as $d){
				fputcsv($output, $d);   
			  } 
				  
					fclose($output);  
					 
					exit;
					
					
		
	}
	
	public function get_all_seller()
	{	
		$PARENT_ID=28;
		
		$dataCSV = SellerDetails::select('user_id','shop_name')->where('status','Approve')->get()->toArray();
		
		
		$newStart					= date('d-m-Y');

			$fileName					= "Cat_data_".$newStart;
		
			$output = fopen('php://output', 'w');
				  
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename='.$fileName.'.csv');

			
			fputcsv($output, array('Id','Shop Name'));  

			foreach($dataCSV as $d){
				fputcsv($output, $d);   
			  } 
				  
					fclose($output);  
					 
					exit;
					
					
		
	}
	
	public function csv_to_array($filename, $delimiter=',', $enclosure='"', $escape = '\\')
{
    if(!file_exists($filename) || !is_readable($filename)) return false;
    $header = null;
    $data = array();
    $lines = file($filename);
    foreach($lines as $line) {
        $values = str_getcsv($line, $delimiter, $enclosure, $escape);
        if(!$header) 
            $header = $values;
        else 
            $data[] = array_combine($header, $values);
    }
return $data;
}
	public function getImageRawData($image_url) {
  if (function_exists('curl_init')) {
    $opts                                   = array();
    $http_headers                           = array();
    $http_headers[]                         = 'Expect:';
    
    $opts[CURLOPT_URL]                      = $image_url;
    $opts[CURLOPT_HTTPHEADER]               = $http_headers;
    $opts[CURLOPT_CONNECTTIMEOUT]           = 10;
    $opts[CURLOPT_TIMEOUT]                  = 60;
    $opts[CURLOPT_HEADER]                   = FALSE;
    $opts[CURLOPT_BINARYTRANSFER]           = TRUE;
    $opts[CURLOPT_VERBOSE]                  = FALSE;
    $opts[CURLOPT_SSL_VERIFYPEER]           = FALSE;
    $opts[CURLOPT_SSL_VERIFYHOST]           = 2;
    $opts[CURLOPT_RETURNTRANSFER]           = TRUE;
    $opts[CURLOPT_FOLLOWLOCATION]           = TRUE;
    $opts[CURLOPT_MAXREDIRS]                = 2;
    $opts[CURLOPT_IPRESOLVE]                = CURL_IPRESOLVE_V4;

    # Initialize PHP/CURL handle
    $ch = curl_init();
    curl_setopt_array($ch, $opts);
    $content = curl_exec($ch);
    
    # Close PHP/CURL handle
    curl_close($ch);
  }// use file_get_contents
  elseif (ini_get('allow_url_fopen')) {
    $content = file_get_contents($image_url);
  }

  # Return results
  return $content;
}
	
public function csv_upload_post(Request $request)
	{

		
			

			$rules['csv_file']			= "required|mimes:csv,txt,ods";
			
			
		
			$errorMsg						= "Opps ! Please fill required fields.";
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
			}
			else{
				
				
		    $file=$request->csv_file;
				
			$input=$request->all();
			$error =  false;
			$errorMsg = "";
			
			
			if($file = $request->file('csv_file')) 
			{
				
					if($file->getClientOriginalExtension() == 'csv' or $file->getClientOriginalExtension() == 'ods' or $file->getClientOriginalExtension() == 'xlsx'  )
					{

                     $fileName = $file->getClientOriginalName();
					//$extension = $file->getClientOriginalExtension() ?: 'csv';
					$extension = 'csv';
					$folderName = '/product_csv';
					$newCsvName = str_random(10) .time(). '.' . $extension;
					$safeName = $fileName;
					@mkdir($folderName,0777,true);
					@chmod($folderName,0777);
					Storage::disk('uploads')->putFileAs($folderName, $file,$newCsvName);
					$fileto  =  Storage::disk('uploads')->path($folderName.'/'.$newCsvName);
					
					
					$id = DB::table('bulk_upload')->insertGetId(
					[
					'csv_file' => $newCsvName,
					
					
					
					]
					);
					
					
					 $productArr = $this->csv_to_array($fileto);
					 
					 if(count($productArr) > 0)
					 {
						 
					 
						foreach($productArr as $key => $value)
						{



							if(isset($value['Product Title']) && isset($value['Seller ID']) && isset($value['Category ID']) && isset($value['Sub Category ID']) && isset($value['Price']) && isset($value['Description']) && isset($value['Stock Status']) && isset($value['Image URL']) )
							{ 


								if( $value['Product Title'] != ""  and $value['Seller ID'] != ""   and $value['Category ID'] != "" and $value['Sub Category ID'] != "" and $value['Price'] != ""  and $value['Stock Status'] != "" and $value['Image URL'] != ""   )
								{
									
									
									$seller_exist     = SellerDetails::where('user_id',$value['Seller ID'])->exists();
									
									$seller_data = SellerDetails::select('category')->where('user_id',$value['Seller ID'])->first();

		                             if($seller_data){
										  $where['category'] = $seller_data->category;
										  $input_upload['category'] = $seller_data->category;
									 }
		
									$sub_category_exist = Category::where('id',$value['Sub Category ID'])->where('parent_menu',$seller_data->category)->exists();
									

									$where['product_title']        = $value['Product Title'];
									$input_upload['product_title'] = $value['Product Title'];
									$where['seller_id']            = $value['Seller ID'];
									$input_upload['seller_id']     = $value['Seller ID'];
									 									
									
									if($seller_exist  && $seller_data && $sub_category_exist){
										
										 $input_upload['sub_category'] = $value['Sub Category ID'];
										 $where['sub_category']        = $value['Sub Category ID'];
										 $input_upload['sale_price']          = $value['Price'];
										 $input_upload['offer_price']         = $value['Price'];
										 $input_upload['product_description'] = $value['Description'];
										 $input_upload['stock_status']        = $value['Stock Status'];
										 $input_upload['unit']                = $value['Product Unit'];
										 
										 if($value['Image URL']) {
											 
											 
										  $a =  $value['Image URL'];
											 
											 
						if (strpos($a,'.jpg') !== false ||  strpos($a,'.JPG') !== false || strpos($a,'.jpeg') !== false || strpos($a,'.png') !== false || strpos($a,'.PNG') !== false) 
						{

                             //~ $file_headers = get_headers($a);
							
							//~ if(strpos($file_headers[0], '404') !== false){
								
							//~ } 
							//~ else {
								    //  ini_set('allow_url_fopen',1);
        
        
									$img = $this->getImageRawData( $a); 
									//$img = file_get_contents( $a); 
									
									//$path_parts = pathinfo($img);
									
									//~ echo  "<pre>"; print_r($path_parts); die;
									//~ echo  $path_parts['extension']; die;

									$data = $img; 
									
									
									
									//$size = getimagesize($a);
									//$extension = image_type_to_extension($size[2]);
									$extension = 'jpg';
     
									$folderName = '/products/';
									@mkdir(storage_path('app/uploads/').$folderName,0777,true);
									@chmod(storage_path('app/uploads/').$folderName,0777);
									$image = str_random(10) . $extension;

									file_put_contents(storage_path('app/uploads/').$folderName.'/'.$image,$data);
									
									$input_upload['product_image'] = $image;
									
									Products::updateOrCreate($where, $input_upload);
			                   
							        
							//}




                                                       }
                                                       else{
									$errorMsg = "Not valid Image URL";
									return response()->json(['error_msg'=>$errorMsg,'slideToTop'=>'yes']);
									}
							}
										
										//echo  "<pre>"; print_r($where);
										
										
										
									}
									
									else{
									$errorMsg = "Not valid data";
									return response()->json(['error_msg'=>$errorMsg,'slideToTop'=>'yes']);
									}
									
									



								} 
							}


						    }
						    
						    
						    
						    
						    
						    
				     }
				     else{

						$errorMsg = "Emty CSV";
						return response()->json(['error_msg'=>$errorMsg,'slideToTop'=>'yes']);

					}

					  //echo  "<pre>"; print_r($productArr); die;
					
					
                    }
					else{

					$errorMsg = "Please upload a CSV file ";
					return response()->json(['error_msg'=>$errorMsg,'slideToTop'=>'yes']);

					}
				
			}else{
				
				$errorMsg = "Please upload file ";
				return response()->json(['error_msg'=>$errorMsg,'slideToTop'=>'yes']);
				
				
			}
			
			
			$message = "Thank-You! Product Added Successfully.";
			
			$output['status']			= 'success';
			$output['success_msg']		= $message;
			$output['msg']				= $message;
			$output['msgHead']			= "Success ! ";
			$output['msgType']			= "success";
			$output['success']			= true;
			$output['slideToTop']		= true;
			//$output['url']				= route('admin.products.edit',['id'=>$pro->id]);
			$output['url']				= route('admin.products');
			
			return response()->json($output);
		}
	}
	 
 }
