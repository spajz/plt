<?php namespace App\Modules\News\Models;

use Base;
use App\Modules\Product\Models\Product;
use Spajz\Modval\Modval;
use Session;

class NewsGroup extends Base
{
    protected $table = 'news_groups';
    public $timestamps = true;
    protected $guarded = array('id');
    protected $fillable = array(
            'title',
            'slug',
            'description',
            'status',

    );

    protected static $rules = array();
    protected $modulConfig = 'news::group';

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

    public function news()
    {
        return $this->hasMany('App\Modules\News\Models\News', 'news_group_id');
    }

}