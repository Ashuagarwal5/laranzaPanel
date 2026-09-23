<?php
namespace App\Http\Controllers\vendor;
use App\Http\Controllers\CodespurController;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Http\Request as valRquest;
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
use App\SellerPaymentTransaction;
use App\Helpers\datehelper;
use App\BMGSellerSetting;
class SellerPayoutsController extends CodespurController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
     
	function __construct()
	{
		$this->date=datehelper::dateformat();
		if (Sentinel::check())
		{ 
			$this->userId = Sentinel::getUser()->id;
		}
	}

    public function index()
    { 
		$paid =  SellerPaymentTransaction::paidBal($this->userId);
		$withdrawl = SellerPaymentTransaction::withdrawalBal($this->userId);
        return view('vendor.payouts.list',compact('withdrawl','paid','alltrans'));
    }
   
   public function getData($info)
   {
		$payment = SellerPaymentTransaction::select(DB::raw("(DATE_FORMAT(created_at,$this->date)) as tran_date"),'current_balance','description','deposit_info','withdrawn_info','status','spt_id','amount','action');
		if($info=='payment')
		{
			$payment->where('action','Add');
		}
		elseif($info=='withdrawn')
		{
			$payment->where('action','Remove');
		}
		
		$payment->orderBy('spt_id','desc')->where('seller_id',$this->userId)->get();
	
		  return Datatables::of($payment)
           ->make(true);
   }
   
   public function withdrawalAmount(valRquest $request)
   {
	    $minlimit =  BMGSellerSetting::select('min_withdrawal_limit')->first();
	    $transaction = SellerPaymentTransaction::select('current_balance')->where('seller_id',$this->userId)->orderBy('spt_id','desc')->first();
	    $this->validate($request, [
				'withdrawal_amount' => 'required',
		]);
		
		if(empty($transaction))
		$data = array('status'=>'error','message'=>'Your Avaliable balance is 0 so withdrawal is not possiable');
		else
		{
			$withdrawal_amount = $request->get('withdrawal_amount');
			$balance = $transaction->current_balance-$withdrawal_amount;
			
			if($withdrawal_amount>$transaction->current_balance)
				$data =array('status'=>'error','message'=>'Withdrawal amount cannot be greater than current balance');
			elseif($withdrawal_amount<$minlimit->min_withdrawal_limit)
				$data =array('status'=>'error','message'=>'Minimum limit of withdrawal is ('.$minlimit->min_withdrawal_limit.')');
			else
			{
						$trans = new SellerPaymentTransaction();
						$trans->amount = $withdrawal_amount;
						$trans->current_balance = $balance;
						$trans->action = 'Remove';
						$trans->seller_id = $this->userId;
						if($trans->save())
						{
								
								$withdrawl = SellerPaymentTransaction::withdrawalBal($this->userId);
								$data = array('status'=>'success','message'=>'Successfully Withdrawal','resetform'=>true,
								'current_balance'=>$trans->current_balance,
								
								'withdrawl'=>$withdrawl->amount,
								'effect'=>true);
						}
						else
							$data = array('status'=>'error','message'=>'Something Wrong Happend');
			}
		}	
		return json_encode($data);
   }
}
