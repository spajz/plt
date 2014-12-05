<?php namespace App\Modules\News\Models;

use Base;
use App\Modules\Product\Models\Product;
use Spajz\Modval\Modval;
use Session;

class News extends Base
{
    protected $table = 'news';
    public $timestamps = true;
    protected $guarded = array('id');
    protected $fillable = array(
            'title',
            'slug',
            'description',
            'status',
            'news_group_id',
            'date',

    );

    protected static $rules = array();
    protected $modulConfig = 'news::news';

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

    public function group()
    {
        return $this->belongsTo('App\Modules\News\Models\NewsGroup', 'news_group_id');
    }

}