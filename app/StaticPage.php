<?php

namespace App;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cache;

class StaticPage extends  EloquentUser  
{
       use Sluggable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'page';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable   = [];
    protected $primaryKey = 'page_id';
    protected $guarded = ['page_id'];
     
     public function sluggable(): array 
    {
        return [
            'slug' => [
                'source' => 'page_title'
            ]
        ];
    }
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
      protected $hidden = [''];
       use SoftDeletes;

    protected $dates = ['deleted_at'];


	function __construct()
    {   
        parent::__construct();
      	$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip());
      	
    }
    
    public static function staticPage()
    {
	   return StaticPage::get();
	}

    public static function get_page_content($slug)
    {
       return StaticPage::where('slug', $slug)->first();
    }
		
		
  //***********************API Functions*************************************************//
	public static function getpagedata($params)
	{
		try {
			$record = StaticPage::select('page_title as page_name','page_description as page_content')->where('slug',$params['slug'])->get()->first();
			$result['data'] = $record;
			$status = 'success';
			if($record)
			{
				$msg ="Success";
			}
			else
			{
				$msg = 'No Page Found';
			}
		}catch (\Illuminate\Database\QueryException $e){
			$status = 'error';
			$msg	= "Error IN Query : ".$e->getMessage();
		} catch (PDOException $e) {
			$status = 'error';
			$msg	= "Error IN Query : ".$e->getMessage();
		}catch (\Exception $e) {
			$status = 'error';
			$msg	= $e->getMessage();
		}
		$result['replyStatus']	= $status;
		$result['replyMessage'] = $msg;
		return $result;
	}

   
}
