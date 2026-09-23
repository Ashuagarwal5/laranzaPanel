<?php 
namespace App\Exports;
  
use App\User;
use App\Products;
use App\History;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
  
class UsersExport implements FromCollection
{

    protected $id;

    function __construct($id) {
       $this->id = $id;
    }
   
    public function collection()
    {
        $data1 = History::where('id',$this->id)->first();
		$get_id = explode(',',$data1->qr_code_id);
        $data = Products::select('id','reward_points','qr_value',DB::raw("(DATE_FORMAT(created_at,'%d %M %y')) as add_date"))->whereIn('id',$get_id)->get();
		foreach($data as $key => $history){
            $history->QR_Link = route('products.download.images',['name' => $history['qr_value']]);
		}
        return $data;
    }
}                                    
