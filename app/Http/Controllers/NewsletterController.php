<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\WebsiteSetting;

use App\Http\Requests\NewsletterRequest;
use App\Page;
use App\Newsletter;
use App\product;
use Lang;
use Mail;
use Redirect;
use Sentinel;
use View;

use DB;
use Datatables;
use App\Helpers\datehelper;


class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    function __construct()
    {
		//parent::__construct();
    	$this->date=datehelper::dateformat();
    } 
    public function index()
    {  

    	$PARENT_ID=81;
    	$totalRecord = Newsletter::count();
    	return view('admin.newsletter.list',compact('PARENT_ID','totalRecord'));
    }


    public function data()
    { 
    	$newsletter = Newsletter::select(['news_id','email',DB::raw("(DATE_FORMAT(created_at,'%d %M  %Y')) as add_date")])->orderBy('news_id', 'desc');
    	return Datatables::of($newsletter)
    	->add_column('actions', '')
    	->add_column('actions', ' <a class="btn btn-danger btn-xs purple" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/newsletter/$news_id/confirm-delete")}}" title="Delete Page">
    		<i class="fa fa-trash"></i>
    		Delete
    		</a>')
    	->rawColumns(['actions'])
    	->make(true);
    }


    public function listDeletedNewsletter()
    {
    	return view('admin.newsletter.deletedlist');
    } 

    public function deletedNewsData() 
    {

    	$newsletter = Newsletter::select(['news_id','email',
    		DB::raw("(DATE_FORMAT(created_at,$this->date)) as add_date")])
    	->orderBy('deleted_at', 'desc')->onlyTrashed()->get();
    	return Datatables::of($newsletter)
    	->add_column('actions', '<a data-toggle="modal" data-target="#modal-large" href="{{URL::to("admin/newsletter/$news_id/confirm-restore")}}" class="btn btn-xs btn-default">Restore</a>
    		<a data-toggle="modal" data-target="#modal-large" href="{{URL::to("admin/newsletter/$news_id/final-delete")}}" class="delval btn btn-xs btn-danger" title="Permanent Delete"><i class="fa fa-trash"></i></a>
    		')
    	->rawColumns(['actions'])
    	->make(true);
    }
 	//Delete
    public function getModalDelete($id = null)
    {
    	$model = 'Delete Subscriber';
    	$confirm_route = $error = null;
    	$confirm_route = route('delete/newsletter', ['id' => $id]);
    	return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
    }
    public function destroy($id)
    {
    	$news = Newsletter::where('news_id', $id)->forceDelete();

    	$success="Newsletter Deleted Successfully";
    	return Redirect::route('admin.newsletter')->with('success', $success);
    }

	//Restore
    public function getModalRestore($id = null)
    {
    	$model = 'Subscriber';
    	$confirm_route = $error = null;
    	$confirm_route = route('restore/newsletter', ['id' => $id]);
    	return View('admin/layouts/restore_modal_confirmation', compact('error', 'model', 'confirm_route'));
    }

    public function restoreDeletedBanner($banner_id)
    {
    	Newsletter::withTrashed()->find($banner_id)->restore();
    	$success="Newsletter Restore Successfully";
    	return Redirect::route('newsletter/deletednewslist')->with('success', $success);
    }		
	//Permanent Delete
    public function getModalFinalDelete($id = null)
    {
    	$model = 'Subscriber';
    	$confirm_route = $error = null;
    	$confirm_route = route('finaldelete/newsletter', ['id' => $id]);
    	return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
    }

    public function permanentDelete($id)
    {
    	Newsletter::where('news_id',$id)->withTrashed()->forceDelete();
    	$success="Newsletter Deleted Successfully";
    	return Redirect::route('newsletter/deletednewslist')->with('success', $success);
    }



    public function downloadCSVFile()
    {	
    	$dataCSV=Newsletter::select('email','created_at')->get();

    	$newData = array();
    	foreach($dataCSV as $key=>$value)
    	{
    		$newData[$key]['id'] = $key+1;
    		$newData[$key]['email'] = $value->email;
    		$newData[$key]['created_at'] = date('d-m-Y',strtotime($value->created_at));
    	}
    	$newStart					= date('d-m-Y');
    	$fileName					= "users_list_".$newStart;

         $filename = "data_export_" . date("Y-m-d") . ".csv";
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");
     
        // force download  
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
     
        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
     
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys(reset($newData)));
        foreach ($newData as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
    }
}
