<?php namespace App\Modules\Product\Models;

use Base;

class AttributeValue extends Base
{
    protected $table = 'attribute_values';
    public $timestamps = false;
    protected $guarded = array('id');
    protected $fillable = array(
        'attribute_id',
        'value',
        'product_id',
    );

    public function products()
    {
        return $this->belongsTo('App\Modules\Product\Models\Product', 'product_id', 'proizvod_id');
    }

    public function attr()
    {
        return $this->belongsTo('App\Modules\Product\Models\Attribute', 'attribute_id', 'id');
    }

    public function scopeProductAttr($query, $id)
    {
        return $query->where('product_id', $id);
    }
}