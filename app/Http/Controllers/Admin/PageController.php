<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Requests\StaticPageRequest;
use App\StaticPage;
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

class PageController extends CodespurController
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
		$PARENT_ID=11;
		$totalRecord	= StaticPage::count();
		return view('admin.page.list',compact('PARENT_ID','totalRecord'));
	}
	//=============== All List Data Function ==========================//
	public function data(){
		
		 $data = StaticPage::select('*',DB::raw("(DATE_FORMAT(`tbl_page`.created_at,'%d %M  %Y')) as add_date"))->get();
		 return Datatables::of($data)
            ->addColumn('actions', '<a title="View" class="btn btn-success btn-xs purple" href="{{route(\'admin.page.show\',$page_id)}}"data-toggle="modal" data-target="#modal-email">
				<i class="fa fa-eye"></i>
				
				</a>
				<a class="delval btn btn-xs btn-primary" title="Edit" href="{{route(\'admin.page.edit\',$page_id)}}">
				<i class="fa fa-edit"></i>
				
				</a>
				
				
				<a data-toggle="modal" data-target="#modal-regular" href="{{route(\'confirm-delete/page\',$page_id)}}" class="delval btn btn-xs btn-danger"  title="Delete">
				<i class="fa fa-trash"></i>
				
				</a>
				
              ')
			->rawColumns(['actions'])
            ->make(true);
		
	}
	public function create($page_id=null){
		$PARENT_ID=98;
		$data	= StaticPage::find($page_id);
		return view('admin.page.edit',compact('PARENT_ID','data'));
	}
	public function store(Request $request,$page_id=null)
	{
		if($page_id!=null)
		{
			$detail	= StaticPage::where('page_title',$request->page_title)->where('page_id','!=',$page_id)->count();
			if($detail>0)
			{
				$this->validate($request,[
				'page_title' => 'required|unique:page,page_title',
				//'page_sub_title' => 'required',
				'page_description' => 'required',
				//'html_title' => 'required',
				//'meta_description' => 'required',
			]);
			}
			else
			{
				$this->validate($request,[
					'page_title' => 'required',
					//'page_sub_title' => 'required',
					'page_description' => 'required',
					//'html_title' => 'required',
					//'meta_description' => 'required',
				]);
			}
		}
		else
		{
		$this->validate($request,[
			'page_title' => 'required|unique:page,page_title',
			//'page_sub_title' => 'required',
            'page_description' => 'required',
            //'html_title' => 'required',
            //'meta_description' => 'required',
		]);
		}
		
		$input = $request->all();

		if($page_id!=null)
		$message="Static Page Updated Successfully";
		else
		$message="Static Page Created Successfully";
		StaticPage::updateOrCreate(['page_id' => $page_id], $input);
		
		$notification = array(
			'message' =>  $message, 
			'alert-type' => 'success'
			);
		return redirect('cpmin/static_pages')->with($notification);
	}
	public function view($page_id){
		
		$PARENT_ID=98;
		$detail	= StaticPage::select(['*',DB::raw("(DATE_FORMAT(tbl_page.created_at,'%d %M  %Y')) as add_date"),DB::raw("(DATE_FORMAT(tbl_page.updated_at,'%d %M  %Y')) as update_date")])->find($page_id);
		if(empty($detail))
		{
			$notification = array(
			'message' =>  'Sorry Static Page Not Found.', 
			'alert-type' => 'warning'
			);
			return redirect('cpmin/page')->with($notification);
		}
		return view('admin.page.view',compact('PARENT_ID','detail'));
	}
	
	public function getModalDelete($page_id = null)
    {
	
		$model = 'Page';
		$confirm_route = $error = null;
		$page= StaticPage::where('page_id', $page_id)->first();
		if (empty($page)) {
			return Redirect::route('info');
		}
		else
		{
			$confirm_route = route('delete/page', ['page_id' => $page_id]);
			return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
		}
    }




	public function destroy($page_id)
	{
		$page = StaticPage::find($page_id);
		$res=$page->delete();
		return Redirect::route('admin.page');
	}

 }
