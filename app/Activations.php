<?php
namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Activations extends Authenticatable
{
    use Notifiable;
    protected $table = 'activations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id';
    protected $fillable = [
        
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
   // use SoftDeletes;
  
   // protected $dates = ['deleted_at'];
     
    function __construct()
    {
        parent::__construct();
      	//$this->attributes=array('ip' =>Helpers\Thumbnail::getclientip()); 	
    }
}
