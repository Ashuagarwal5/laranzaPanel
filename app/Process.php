<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Process extends Eloquent
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'process';



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

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */


    /**
    * To allow soft deletes
    */


    protected $dates = ['created_at'];


    public function __construct()
    {
        parent::__construct();
        $this->attributes=array('ip' =>Helpers\Thumbnail::getclientip(),'site_id'=>config('constants.siteinfo.site_id'));
    }

    public static function generatecode($type, $history_id, $history_data, $update_data_id = null)
    {
        $process = new Process();
        $process->type = $type;
        $process->status = "Processing";
        if ($update_data_id != null) {
            $process->update_data_id = $update_data_id;
        }
        $process->history_data = $history_data;
        $process->history_id = $history_id;
        $process->save();
        return $process;
    }
}
