<?php namespace App\Modules\Product\Models;

use Base;

class Attribute extends Base
{
    protected $table = 'attributes';
    public $timestamps = false;
    protected $guarded = array('id');
    protected $fillable = array(
        'title',
        'status',
        'group_id',
        'sort',
        'filter',
    );

    public function group()
    {
        return $this->belongsTo('App\Modules\Product\Models\Category', 'group_id', 'group_id');
    }

    public function attrValues()
    {
        return $this->hasMany('App\Modules\Product\Models\AttributeValue', 'attribute_id', 'id');
    }

    public function products()
    {
        return $this->hasMany('App\Modules\Product\Models\Product', 'group_id', 'group_id');
    }


    public static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            foreach ($model->attrValues as $attrValue) {
                $attrValue->delete();
            }
        });
    }

}