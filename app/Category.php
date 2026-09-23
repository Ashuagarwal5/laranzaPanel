<?php namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model as Eloquent;
use DB;
use URL;
use App\Helpers\Thumbnail;

class Category extends Eloquent   {
    use Sluggable;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'category';

	/**
	 * The attributes to be fillable from the model.
	 *
	 * A dirty hack to allow fields to be fillable by calling empty fillable array
	 *
	 * @var array
	 */
	protected $primaryKey = 'id';
	protected $fillable = [];
	protected $guarded = ['id'];
   
   public function sluggable():array
    {
        return [
            'slug' => [
                'source' => 'category_name'
            ]
        ];
    }
   
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [''];

	/**
	* To allow soft deletes
	*/
	use SoftDeletes;
  
    protected $dates = ['deleted_at'];
    
    
    
  
  function __construct()
    {
        
        parent::__construct();
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip(),'site_id'=>config('constants.siteinfo.site_id'));
      	
    }

	public static function getAllMainCat()
	{		
	  return Category::select('id','category_name')->where('parent_menu',0)->orderBy('category_name','asc')->get();	  
	}	
	public static function getAllSubCat($id)
	{		
	  return Category::select('id','category_name')->where('parent_menu',$id)->orderBy('category_name','asc')->get();	  
	}
	public static function getCatNameById($id)
	{		
	  $cat = Category::select('id','category_name')->where('id',$id)->first();	
	  return $cat->category_name;
	}
	
/************************************** Api function Starts ****************************************/
	
	public static function category($params)
	{ //die('dasdsa');
		$result = array();
		$status = 'error';
		$msg = '';
		try {
		if(isset($params['parent_id']))
		{
			$data = DB::table('category')->select('id','category_name','category_icon',
			DB::raw("IF(STRCMP(category_banner,''),CONCAT('" . URL::to(Thumbnail::image("category","25","30","f")) . "/"."',category_banner),'') as category_banner", false)
			)->where('parent_menu',$params['parent_id'])->get()->toArray();
			
			foreach($data as $key =>  $value){
				$pro_count = Products::where('category',$value->id)->count();

				 if($pro_count == 0)
				 unset($data[$key]);
			}
			
			$result['data'] = array_values($data);
			$status = 'success';
			$msg 	= 'Category Found Successfully';
		}
		elseif(isset($params['category_id'])){
			$data = Category::select('id','category_name','category_icon',
			DB::raw("IF(STRCMP(category_banner,''),CONCAT('" . URL::to(Thumbnail::image("category","25","30","f")) . "/"."',category_banner),'') as category_banner", false)
			)->where('id',$params['category_id'])->first();
			
			$result['data'] = $data;
			$status = 'success';
			$msg 	= 'Category Found Successfully';
		}
		else{
			$data = Category::select('id','category_name','category_icon',
			DB::raw("IF(STRCMP(category_banner,''),CONCAT('" . URL::to(Thumbnail::image("category","25","30","f")) . "/"."',category_banner),'') as category_banner", false)
			)->get();
			
			
			
			
			
			$result['data'] = $data;
			$status = 'success';
			$msg 	= 'Category Found Successfully';
		}
		
		}catch (\Illuminate\Database\QueryException $e){
		$status = 'error';
		$msg = "Error IN Query : ".$e->getMessage();
		} catch (PDOException $e) {
		$status = 'error';
		$msg = "Error IN Query : ".$e->getMessage();
		} catch (\Exception $e) {
		$status = 'error';
		$msg = $e->getMessage();
		}
		
		$result['replyStatus']	= $status;
		$result['replyMessage'] = $msg;

		return $result;
	}
	

}
