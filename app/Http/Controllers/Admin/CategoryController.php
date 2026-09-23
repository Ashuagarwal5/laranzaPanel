<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use View;
use App\Category;
use Validator;
use DB;
use Datatables;
use Illuminate\Support\Facades\Storage;
use Redirect;
use URL;
use App\Helpers\datehelper;

class CategoryController extends Controller
{
    function __construct()
	{
	 $this->date=datehelper::dateformat();
	}	
	//=================================================================//
	//================== Admin   View Function ==================//
	public function index(){
		$PARENT_ID=28;
		return view('admin.category.list',compact('PARENT_ID'));
	}
	
	 public function data()
    {
        $data = Category::select(['id','category_name','category_code','cat_description','category_icon','created_at','parent_menu','type'
        ,DB::raw("DATE_FORMAT(created_at,'%d %M  %Y') as add_date")] )
        ->where('parent_menu','=','0')
        ->orderBy('id', 'desc')->get();
         foreach($data as $key=>$value)
		 {
			if($value->parent_menu>0)
			{
				$catName=Category::select('category_name')->where('id',$value->parent_menu)->first();
				$data[$key]->parent_name=$catName['category_name'];
			}
			else
			{
				$data[$key]->parent_name="N/A";
			}
		 }
		 return Datatables::of($data)
		         ->addColumn('actions', '<div class="btn-group">
					@if($parent_menu==0)
					   
					<a class="btn btn-primary" href="{{URL::to("cpmin/category/edit/$id")}}" title="Edit"><i class="fa fa-edit"></i> </a>
					@else
					<a class="btn btn-primary" href="{{URL::to("admin/category/edit/sub/$parent_menu/$id")}}" title="Edit"><i class="fa fa-edit"></i> Edit</a>
					@endif
					<a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{route("confirm-delete/category", ["id" => $id])}}" title="Delete"><i class="fa fa-trash"></i> </a>
		       	</div>
              		')
       ->rawColumns(['actions'])
       ->make(true);
    }
    
    public function subindex($id){
		 //echo $id; die;
		 $PARENT_ID=60;
		 $menuTitle = Category::find($id)->category_name;
		 return view('admin.category.list_sub',compact('id','menuTitle','PARENT_ID'));	
	}
	
	 public function subdata($id)
    {
		//echo $id; die;
        $data = Category::select(['id','category_name','cat_description','created_at','parent_menu','type'
        ,DB::raw("DATE_FORMAT(created_at,'%d %M  %Y') as add_date")] )
        ->where('parent_menu',$id)
        ->get();
         //print_r ($data); die;
         foreach($data as $key=>$value)
		 {
			if($value->parent_menu>0)
			{
				$catName=Category::select('category_name')->where('id',$value->parent_menu)->first();
				$data[$key]->parent_name=$catName['category_name'];
			}
			else
			{
				$data[$key]->parent_name="N/A";
			}
		 }
		 return Datatables::of($data)
		         ->addColumn('actions', '<div class="btn-group">
					<a class="btn btn-success" href="{{URL::to("admin/category/sub-list/$id")}}" title="Sub Category"><i class="fa fa-edit"></i> Sub Category</a>   
					<a class="btn btn-primary" href="{{URL::to("admin/category/edit/sub/$parent_menu/$id")}}" title="Edit"><i class="fa fa-edit"></i> Edit</a>
					<a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/category/$id/confirm-delete")}}" title="Delete"><i class="fa fa-trash"></i> Delete</a>
		       	</div>
              		')
       ->rawColumns(['actions'])
       ->make(true);
    }
    
   
	 public function create($ID=NULL)
    {
		$PARENT_ID=60;
		$type="main";
		$menuTitle = '';
	      if($ID)
		{
		    $route=route('updated.category', ['ID' => $ID,'type'=>$type]);
			$data=Category::find($ID);
			if(count(array($data)) == 0)
			{
				$messgae="This is not valid action, data not found.";
				return redirect('admin.header')->with('error', trans($messgae));
			}
			$data =  Category::Select('id','category_name','category_code','cat_description','category_icon')->where('id',$ID)->first();
			return View('admin.category.create',compact('data','route','type','menuTitle','PARENT_ID'));
		}
			$route=route('store.category' , ['type' => $type]);
	   		return View('admin.category.create',compact('route','type','menuTitle','PARENT_ID'));
    }
    	
	public function store(Request $request,$ID=NULL)
    {
				if($ID!=NULL)
				{
					$this->validate($request,[
							'category_name' => 'required',
							'cat_description' => 'required',
							'category_code' => 'required|alpha|max:3',
						]);
				}
				else{
					$this->validate($request,[
							'category_name' => 'required',
							'cat_description' => 'required',
							'category_code' => 'required|alpha|max:3',
						]);
				}
		    
		    $input = $request->all();
		    
		    
		    /*
			$file=$request->category_banner;
			
			$records= Category::Select('category_banner')->where('id',$ID)->first();   	
	
			if (!empty($file))
			{	
				
				$extension = $file->extension();
				$folderName = '/category';
				$safeName = str_random(10) . '.' . $extension;
				@mkdir($folderName,0777,true);
				@chmod($folderName,0777);
				Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
				$input['category_banner'] = $safeName;
				
				 if(count($records)>0 && ($records->category_banner!='') && ($input['category_banner']!=''))
				{
					$folderName = '/category';
					$filedir = $folderName .'/'. $records->category_banner;
					Storage::disk('uploads')->delete($filedir);
				}
				
			}
			else
			{
				$input['category_banner'] = $records['category_banner'];
			}
			
			
			 */
			
			
		$input['parent_menu'] = 0;
		$input['type'] 		  = "main"     ;
	
		if($ID!=''){
			$data=Category::find($ID);
				if(count(array($data))==0){
				$messgae="This is not valid action,Header data not found.";
				return redirect('admin.category')->with('error', trans($messgae));
				}			
		}
		$header = Category::updateOrCreate(['id' => $ID],$input);
		if($ID!=NULL)
		$messgae="Category Updated Successfully";
		else
		$messgae="Category Created Successfully";
		
		$notification = array(
			'message' =>  $messgae, 
			'alert-type' => 'success'
			);		
			return Redirect::route('admin.category')->withInput()->with('error', trans('event/message.error.create'));
    }	
    
    public function createsubmenu($parent_menu=null,$ID=NULL)
    {	
		$PARENT_ID=60;
		$main = Category::find($parent_menu)->category_name;		
		$type="sub";
		$menuTitle = Category::find($parent_menu)->category_name;
		$icon =   Category::find($parent_menu)->category_icon;
		if($ID)
		{
			$route=route('edit.submenus', ['parent_menu' => $parent_menu,'ID'=>$ID]);
			//$route = URL::to('admin/header/edit/sub/2/6');
			$data=Category::find($ID);
			if(count($data)==0)
			{
				$messgae="This is not valid action, data not found.";
				return redirect('admin.category')->with('error', trans($messgae));
			}
			$data =  Category::Select('id','category_name','type','cat_description','category_icon')->where('id',$ID)->first();
			return View('admin.category.create_sub',compact('data','route','type','menuTitle','icon','ID','parent_menu','PARENT_ID'));
		}
		$route=route('store.submenu' , ['parent_menu' => $parent_menu]);
		return View('admin.category.create_sub',compact('route','type','menuTitle','parent_menu','PARENT_ID'));
    }
    
    public function storeSubMenu(Request $request,$parent_menu=null,$ID=NULL)
    {
		if($ID!=NULL)
				{
					$this->validate($request,[
							'category_name' => 'required',
							'cat_description' => 'required',
						]);
				}
				else{
					$this->validate($request,[
							'category_name' => 'required',
							'cat_description' => 'required',
						]);
				}
		  
		  
		  $input = $request->all();
		  
		  /*
			$file=$request->category_banner;
			
			$records= Category::Select('category_banner')->where('id',$ID)->first();   	
	
			if (!empty($file))
			{	
				
				$extension = $file->extension();
				$folderName = '/category';
				$safeName = str_random(10) . '.' . $extension;
				@mkdir($folderName,0777,true);
				@chmod($folderName,0777);
				Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
				$input['category_banner'] = $safeName;
				
				 if(count($records)>0 && ($records->category_banner!='') && ($input['category_banner']!=''))
				{
					$folderName = '/category';
					$filedir = $folderName .'/'. $records->category_banner;
					Storage::disk('uploads')->delete($filedir);
				}				
			}
			else
			{
				$input['category_banner'] = $records['category_banner'];
				}
				
				*/
				
		
		$input['parent_menu'] = $parent_menu;
		$input['type'] = "sub";
	
		if($ID!=''){		
			$data=Category::find($ID);
			if(count($data)==0){
				$messgae="This is not valid action,Header data not found.";
				return redirect('admin.category')->with('error', trans($messgae));
			}			
			}
			$header = Category::updateOrCreate(['id' => $ID],$input);
			if($ID!=NULL)
         $messgae="Category Sub Menu Updated Successfully";
         else
         $messgae="Category Sub Menu Created Successfully";
         
		$notification = array(
			'message' =>  $messgae, 
			'alert-type' => 'success'
			);							
			return Redirect::route('data.category',['parent_menu'=>$parent_menu])->with('error', trans('event/message.error.create'));	
    }	
    	
 
	public function getModalDelete($id = null)
    {	
		$model = 'Category';
		$confirm_route = $error = null;
		$branch= Category::where('id', $id)->first();
		if (empty($branch)) {
			return Redirect::route('info');
		}
		else
		{
			$confirm_route = route('delete/category', ['banner_id' => $id]);
			return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
		}
    }

	public function destroy($id)
	{
		$branch = Category::find($id);
		$res=$branch->delete();
		return Redirect::route('admin.category');
	}
	
	
	public function setorderlist()
	{
		$header=Category::get();
		return view('admin.category.orderlist',compact('header'));
	
	}
	public function setOrder()
	{
	echo"ds";die;
     	$order=Input::get('order');

        foreach($order as $key=> $value){

             DB::table('header_links')
						 ->where('id', $value['id'])  // find your user by their email
						->update(array('shownig_order' => $value['val']));
			}
    }
}
