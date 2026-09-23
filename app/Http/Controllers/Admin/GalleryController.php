<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use View;
use App\Gallery;
use App\WebsiteSetting;
use App\Products;
use Validator;
use DB;
use Datatables;
use Illuminate\Support\Facades\Storage;
use Redirect;
use App\Helpers\datehelper;

class GalleryController extends Controller
{

    function __construct()
	{
	  // // $this->date=datehelper::dateformat();
		 // $this->date_format   = WebsiteSetting::select('dateformat')->first();

	}	
	//================== Admin   View Function ==================//

	public function index()
	{

		$PARENT_ID=86;
		return view('admin.gallery.list',compact('PARENT_ID'));
	}

	 public function data()
    {
    	// $date_format  =  $this->date_format->dateformat ;

        $data= Gallery::select(['gallery.*', 'products.product_title as poduct_title',DB::raw("DATE_FORMAT(tbl_gallery.created_at,'%d %M %Y') as add_date")] )
        ->join('products', 'products.id', 'gallery.product_id')
        ->get();

		 return Datatables::of($data)
		         ->addColumn('actions', '<div class="btn-group">    
					

					<a class="btn btn-primary" href="{{URL::to("admin/gallery/edit/$id")}}" title="Edit"><i class="fa fa-edit"></i></a>

					<a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/gallery/$id/confirm-delete")}}" title="Delete"><i class="fa fa-trash"></i></a>
		       	</div>
              		')
       ->rawColumns(['actions'])

       ->make(true);

       /*<a class="btn btn-default" href="{{URL::to("admin/gallery/show/$id")}}" title="View" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-eye"></i> </a> */

    }

	 public function create($ID=NULL)
    {
		$PARENT_ID=86;
		$products = Products::where('deleted_at', null)->orderby('id', 'desc')->get();
	      if($ID)
		{
				$route=route('updated.gallery', ['id' => $ID]);
			$data=Gallery::find($ID);
			if(count($data)==0)
			{
				$messgae="This is not valid action, data not found.";
				return redirect('admin.gallery')->with('error', trans($messgae));
			}
			$gallery =  Gallery::where('id',$ID)->first();
			return View('admin.gallery.create',compact('gallery','route','PARENT_ID', 'products'));
		}
		$route=route('store.gallery');
   		return View('admin.gallery.create',compact('route','PARENT_ID', 'products'));
    }	

   public function store(Request $request,$ID=NULL)
    {
		$input=$request->all();	
	    $rules['product_id'] = 'required|numeric';
	    $rules['display_order'] = 'numeric';

		$records= Gallery::where('id',$ID)->first();
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
            $folderName = '/gallery';
            $safeName = str_random(10) . '.' . $extension;
            Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
            $input['image'] = $safeName;
			 if(count($records)>0 && ($records->image!='') && ($input['image']!=''))
            {
				$folderName = '/gallery';
				$filedir = $folderName .'/'. $records->image;
                Storage::disk('uploads')->delete($filedir);
            }
		}else{
			$input['image'] = $records->image;
		}		

		if($ID!='')
		{
			$data=Gallery::find($ID);
			if(count($data)==0){
			$messgae="This is not valid action, data not found.";
			return redirect('admin.gallery')->with('error', trans($messgae));
			}			
		}

        $input['display_order'] = empty($request->display_order) ? 0 : $request->display_order;


		$gallery = Gallery::updateOrCreate(['id' => $ID],$input);
		if($ID!=NULL)
		$messgae="Gallery Image Updated Successfully";
		 else
		$messgae="Gallery Image Created Successfully";

		if ($gallery->save()) 
		{
	        $output['status']			= 'success';
			$output['success_msg']		= $messgae;
			$output['msg']				= $messgae;
			$output['msgHead']			= "Success ! ";
			$output['msgType']			= "success";
			$output['success']			= true;
			$output['slideToTop']		= true;
			$output['url']				= route('admin.gallery');
			echo json_encode($output);
		} 
		else 
		{
            $errorMsg = "Error ! Gallery Image Can't be Add";
			return response()->json(['error_msg'=>$errorMsg,'slideToTop'=>'yes']);
		}		

    }	

	public function show($ID)
    {
    	$date_format  =  $this->date_format->dateformat ;

	$detail =  Gallery::Select('id','image','type','title','vedio','ip'
	,DB::raw("DATE_FORMAT(created_at,'$date_format') as add_date")
	,DB::raw("DATE_FORMAT(updated_at,'$date_format') as update_date"))->where('id',$ID)->first();

			return View('admin.gallery.show', compact('detail'));

	 }

	 public function getModalDelete($id =NULL)
	 {

	 	$model = 'Gallery';

		$confirm_route = $error = null;

		$confirm_route = route('deleted.gallery', ['id' => $id]);

		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));

	 }

	 public function destroy($id=NULL)

	 {
	 	$gallery= Gallery::find($id);
	 	if(!empty($gallery->image))
	 	{
			$folderName = '/gallery';
			$filedir = $folderName.'/'.$gallery->image;
            Storage::disk('uploads')->delete($filedir);
		}
	 	Gallery::where('id', $id)->forceDelete();
 		$success ="Image Deleted Succesfully";
 		return Redirect::route('admin.gallery')->with('success', $success);
	 }

	 public function listDeletedPages()

	 {

	 		return view('admin.gallery.deletedlist');

	 }

	  public function listDeletedData()

	 {
    	$date_format  =  $this->date_format->dateformat ;

$data = Gallery::select(['id','type','image','vedio',DB::raw("DATE_FORMAT(deleted_at,'$date_format')as deleted_date")] )->orderBy('deleted_at', 'desc')->onlyTrashed()->get();

		 return Datatables::of($data)

       ->addColumn('actions', '<a data-toggle="modal" data-target="#modal-large" href="{{URL::to("admin/gallery/$id/confirm-restore")}}"class="btn btn-small btn-default"title="Restore"><i class="fa fa-undo"> Restore</i></a>

			<a data-toggle="modal" data-target="#modal-large" href="{{URL::to("admin/gallery/$id/final-delete")}}" class="delval btn btn-small btn-danger" title="Permanent Delete"><i class="fa fa-trash-o"> Delete</i></a>')

      ->rawColumns(['actions'])

       ->make(true);

	 }

	 public function getModalRestore($id =NULL)

	 {

	 $model = 'Gallery';

	 $confirm_route = $error = null;

	 $confirm_route = route('restore.gallery', ['id' => $id]);

	 return View('admin/layouts/restore_modal_confirmation', compact('error', 'model', 'confirm_route'));

	 }

	 public function restoreDeletedPages($id =NULL)

	 {

	 Gallery::withTrashed()->find($id)->restore();

	 $success ="Event Restored Succesfully";

	 return Redirect::route('gallerylist.deletedgallery')->with('success', $success);

	 }

 public function getModalFinalDelete($id = null)
    {

		$model = 'Gallery';

		$confirm_route = $error = null;

		$confirm_route = route('finaldelete.gallery', ['id' => $id]);

        return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));

    }

	 public function permanentDelete($id)
	{
		$data=Gallery::where('id',$id)->withTrashed()->first();

		//===================== Delete News Images When Delete News ===========//

		if(!empty($data->image)){

			$folderName = '/gallery';

			$filedir = $folderName .'/'. $data->image;

            Storage::disk('uploads')->delete($filedir);

		}

		//=====================================================================//

		Gallery::where('id',$id)->withTrashed()->forceDelete();

	   $success ="Gallery Permanently Deleted Succesfully";

		 return Redirect::route('gallerylist.deletedgallery')->with('success', $success);

	}



	
}
