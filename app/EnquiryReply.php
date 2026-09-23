<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model as Eloquent;
use DB;

class EnquiryReply extends  Eloquent
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'enquiry_reply';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable   = [];
    protected $primarykey = ['enq_r_id'];
    protected $guarded = ['enq_r_id'];
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
}
