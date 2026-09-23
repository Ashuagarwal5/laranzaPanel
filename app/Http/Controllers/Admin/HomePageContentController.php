<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\HomePageContent;
use App\Products;
use Redirect;
use Sentinel;
use Session;
use View;
use DB;
use Datatables;
use Input;
use Response;
use Validator;
use Storage;
use App\Helpers\datehelper;

class HomePageContentController extends CodespurController
{
        function __construct()
	{
	  // // $this->date=datehelper::dateformat();
		 // $this->date_format   = WebsiteSetting::select('dateformat')->first();

	}	
	//================== Admin   View Function ==================//

	public function index()
	{

		$PARENT_ID= 98;
		return view('admin.home-page-content.list',compact('PARENT_ID'));
	}

	 public function data()
    {

        $data= HomePageContent::select('home_page_content.*', 'products.product_title as poduct_title',
        						DB::raw("DATE_FORMAT(tbl_home_page_content.created_at,'%d %M %Y') as add_date"))
						        ->join('products', 'products.id', 'home_page_content.product_id')
						        ->get();

		 return Datatables::of($data)
		         ->addColumn('actions', '<div class="btn-group">    
					
                    <a class="btn btn-default" href="{{URL::to("admin/home-page-content/show/$id")}}" title="View" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-eye"></i> </a> 
					<a class="btn btn-primary" href="{{URL::to("admin/home-page-content/edit/$id")}}" title="Edit"><i class="fa fa-edit"></i></a>

					<a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/home-page-content/$id/confirm-delete")}}" title="Delete"><i class="fa fa-trash"></i></a>
		       	</div>')
       ->rawColumns(['actions'])
       ->make(true);
    }

	 public function create($ID=NULL)
    {
		$PARENT_ID=98;
		$products = Products::where('deleted_at', null)->orderby('id', 'desc')->get();
	     if($ID)
		{
			$route=route('updated.home-page-content', ['id' => $ID]);
			$data =  HomePageContent::where('id',$ID)->first();
			if($data == null)
			{
				$messgae="This is not valid action, data not found.";
				return redirect('admin.home-page-content')->with('error', trans($messgae));
			}
			return View('admin.home-page-content.create',compact('data','route','PARENT_ID', 'products'));
		}

		$route=route('store.home-page-content');
   		return View('admin.home-page-content.create',compact('route','PARENT_ID', 'products'));
    }	

   public function store(Request $request,$ID=NULL)
    {
		$input=$request->all();	
	    $rules['product_id'] = 'required|numeric';
	    $rules['title'] = 'required';
	    $rules['display_order'] = 'required|numeric';
	    $rules['description'] = 'required';

		$records= HomePageContent::where('id',$ID)->first();
		if($ID!=null)
		{
			if(isset($input['image']))
			$rules['image'] = 'required|mimes:jpg,jpeg,png';
		}
		else
		{
			$rules['image'] = 'required|mimes:jpg,jpeg,png';
		}	

		$errorMsg="Opps ! Some Error Occured. Please Try Again.";

		$validator = Validator::make($input, $rules);

		if ($validator->fails()) {

			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);

		}	
		
		if ($file = $request->file('image'))
		{
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $folderName = '/home-page-content';
            $safeName = str_random(10) . '.' . $extension;
            Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
            $input['image'] = $safeName;
			 if(count($records)>0 && ($records->image!='') && ($input['image']!=''))
            {
				$folderName = '/home-page-content';
				$filedir = $folderName .'/'. $records->image;
                Storage::disk('uploads')->delete($filedir);
            }
		}else{
			$input['image'] = $records->image;
		}		

		if($ID!='')
		{
			$data=HomePageContent::find($ID);
			if(count($data)==0){
			$messgae="This is not valid action, data not found.";
			return redirect('admin.home-page-content')->with('error', trans($messgae));
			}			
		}


		$gallery = HomePageContent::updateOrCreate(['id' => $ID],$input);
		if($ID!=NULL)
		$messgae="Home Page Content Updated Successfully";
		 else
		$messgae="Home Page Content Created Successfully";

		if ($gallery->save()) 
		{
	        $output['status']			= 'success';
			$output['success_msg']		= $messgae;
			$output['msg']				= $messgae;
			$output['msgHead']			= "Success ! ";
			$output['msgType']			= "success";
			$output['success']			= true;
			$output['slideToTop']		= true;
			$output['url']				= route('admin.home-page-content');
			echo json_encode($output);
		} 
		else 
		{
            $errorMsg = "Error ! Home Page Content Can't be Add";
			return response()->json(['error_msg'=>$errorMsg,'slideToTop'=>'yes']);
		}		

    }	

	public function view($ID)
    {

	    $detail =  HomePageContent::Select('home_page_content.*', 'products.product_title')
	            ->join('products', 'products.id', 'home_page_content.product_id')
				->where('home_page_content.id',$ID)
				->first();

		return View('admin.home-page-content.show', compact('detail'));

	 }

	 public function getModalDelete($id =NULL)
	 {

	 	$model = 'Home Page Content';

		$confirm_route = $error = null;

		$confirm_route = route('deleted.home-page-content', ['id' => $id]);

		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));

	 }

	 public function destroy($id=NULL)

	 {
	 	$data = HomePageContent::find($id);
	 	if(!empty($data->image))
	 	{
			$folderName = '/home-page-content';
			$filedir = $folderName.'/'.$data->image;
            Storage::disk('uploads')->delete($filedir);
		}
	 	HomePageContent::where('id', $id)->forceDelete();
 		$success ="Image Deleted Succesfully";
 		return Redirect::route('admin.home-page-content')->with('success', $success);
	 }


	
}
