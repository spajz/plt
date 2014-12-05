<?php namespace App\Modules\Admin\Models;

use Base;
use App\Modules\Admin\Api\ImageApi;
use Config;

class Image extends Base
{
    protected $table = 'content_images';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = array('id');
    protected $fillable = array(
            'model_id',
            'model_type',
            'title',
            'image',
            'status',
            'alt',
            'sort',
    );

    protected $appends = array('thumb');

    public function model()
    {
        return $this->morphTo();
    }

    public function getImage($size = 'thumb')
    {
        if ($config = $this->getRelatedModelConfig()) {
            $baseUrl = Config::get($config . '.images.path');
            $baseUrl = str_replace(public_path(), '', $baseUrl);
            return url($baseUrl . $size . '/' . $this->image);
        }
        return $this->image;

    }

    public function getImageSize($size = 'thumb', $key = 3)
    {
        if ($config = $this->getRelatedModelConfig()) {
            $size = getimagesize(Config::get($config . '.images.path') . $size . '/' . $this->image);
            if (is_numeric($key))
                return $size[$key];

            return $size;
        }
        return false;
    }

    public function getRelatedModelConfig()
    {
        if ($relatedModel = $this->model_type) {
            $relatedModel = new $relatedModel();
            return $relatedModel->getModulConfig();
        }
        return false;
    }

    public function getImageTime($size = 'thumb')
    {
        if ($config = $this->getRelatedModelConfig()) {
            return filemtime(Config::get($config . '.images.path') . $size . '/' . $this->image);
        }
        return false;
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
        // Delete images
        if ($relatedConfig = $this->getRelatedModelConfig()) {
            $imageApi = new ImageApi();
            $imageApi->setConfig($relatedConfig);
            $imageApi->deleteFile($this->image);
        }
    }

}