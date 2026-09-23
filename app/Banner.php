<?php

namespace App;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cache;
use Cart;
use DB;
use URL;
use App\Category;
use App\Helpers\Thumbnail;

class Banner extends EloquentUser
{
	// use Sluggable;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'banners';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [];
	protected $primaryKey = 'id';
	protected $guarded = ['id'];

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
		$this->attributes = array('ip' => Helpers\Thumbnail::getclientip());

	}

	public static function get_banner($page_name)
	{
		$data = Banner::where('deleted_at', null)
			->where('page_name', $page_name)
			->first();
		return $data;
	} 
} 