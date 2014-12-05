<?php namespace App\Modules\Product\Models;

use Base;
use Session;

class Product extends Base
{
    protected $table = 'proizvodi';
    protected $primaryKey = 'proizvod_id';
    public $timestamps = true;
    protected $guarded = array('proizvod_id');
    protected $fillable = array(
            'proizvod',
            'group_id',
            'show_item',
            'old_item',
            'cena_prodajna',
            'cena_dilerska',
            'stanje',
            'datum',
            'pdv',
            'proizvodjac_id',
            'fix_show_item',
            'fix_cena',
            'fix_cena_din',
            'preporucena_cena',
            'description',
            'intro',
    );
    protected $statusColumn = 'show_item';
    protected $modulConfig = 'product::product';
    protected $appends = array('status');

    public function category()
    {
        return $this->belongsTo('App\Modules\Product\Models\Category', 'group_id');
    }

    public function brand()
    {
        return $this->belongsTo('App\Modules\Product\Models\Brand', 'proizvodjac_id');
    }

    public function productData()
    {
        return $this->hasOne('App\Modules\Product\Models\ProductData', 'proizvod_id_copy');
    }

    public function productDescription()
    {
        return $this->hasOne('App\Modules\Product\Models\ProductDescription', 'proizvod_id');
    }

    public function scopeActiveList($query)
    {
        return $query->where('show_item', 1)->select('group_id')->distinct()->lists('group_id', 'group_id');
    }

    public function scopeStatus($query, $value = null)
    {
        if (is_numeric($value))
            $query->where('show_item', $value);
    }

    // Polymorphic relations
    public function images()
    {
        return $this->morphMany('App\Modules\Admin\Models\Image', 'model')
                ->orderBy('sort', 'asc')
                ->orderBy('id', 'desc');
    }

    public function videos()
    {
        return $this->morphMany('App\Modules\Admin\Models\Video', 'model')
                ->orderBy('sort', 'asc')
                ->orderBy('id', 'desc');
    }

    public function comments()
    {
        return $this->morphMany('App\Modules\Comment\Models\Comment', 'model')
                ->orderBy('id', 'desc');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->deleteWith();
        });
    }

    public function deleteWith()
    {
        $this->images()->delete();

        $this->videos()->delete();
    }

    public function scopeFilter($query, $value = null)
    {
        $status = Session::get('options.product.show_item');

        if (is_numeric($status) && $status >= 0) {
            $query->where('show_item', '=', $status);
        }

        return $query;
    }

    public function getTitleAttribute()
    {
        return $this->proizvod;
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['first_name'] = strtolower($value);
    }

    public function attrValues()
    {
        return $this->hasMany('App\Modules\Product\Models\AttributeValue', 'product_id', 'proizvod_id');
    }

}