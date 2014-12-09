<?php namespace App\Modules\Product\Models;

use Base;
use Session;

class ProductAttribute extends Base
{
    protected $table = 'product_attributes';
    public $timestamps = false;
    protected $guarded = array('id');
    protected $fillable = array(
            'product_id',
            'value',
            'sort',
    );

    public function products()
    {
        return $this->belongsTo('App\Modules\Product\Models\Product', 'product_id', 'proizvod_id');
    }


}