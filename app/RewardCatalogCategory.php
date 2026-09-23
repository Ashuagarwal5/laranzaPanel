<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class RewardCatalogCategory extends Eloquent
{
    use SoftDeletes;

    protected $table = 'reward_catalog_categories';
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'sort_order' => 'integer',
    ];
}
