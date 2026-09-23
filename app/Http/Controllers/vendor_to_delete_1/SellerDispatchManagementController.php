<?php
namespace App\Http\Controllers\vendor;
use App\Http\Controllers\CodespurController;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Http\Request;
use Lang;
use Redirect;
use Sentinel;
use View;
use DB;
use Auth;
use Arrays;
use Response;
use Datatables;
use Mail;
use Session;


class SellerDispatchManagementController extends CodespurController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
     var $orderstatus='';

  
	function __construct()
	{
		if (Sentinel::check())
		{ 
			$this->userId = Sentinel::getUser()->id;
		}
	}

    public function index($slug = '')
    { 
        return view('vendor.dispatch.list');
    }
   
}
