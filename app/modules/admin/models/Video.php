<?php namespace App\Modules\Admin\Models;

use Base;
use VideoSites;

class Video extends Base
{
    protected $table = 'content_videos';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = array('id');
    protected $fillable = array(
        'model_id',
        'model_type',
        'vid',
        'title',
        'url',
        'host',
        'status',
        'sort',
    );

    protected $appends = array('thumb');

    public function model()
    {
        return $this->morphTo();
    }

    public function getThumbAttribute()
    {
        $videSites = new VideoSites();
        return $videSites->getThumbUrl($this->vid, $this->url);
    }

}