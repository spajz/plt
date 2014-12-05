<?php  namespace App\Modules\Admin\Api;

use App\Modules\Admin\Models\Video;
use VideoSites;

class VideoApi
{
    protected $model;

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function saveData($data = array())
    {
        if (!array_get($data, 'url')) return false;
        $vid = $this->getVideoId($data['url']);
        if (!$vid) return false;
        $video = new Video();
        $video->vid = $vid;
        $video->title = array_get($data, 'title');
        $video->url = $data['url'];
        $video->host = get_domain($data['url']);
        $video->status = array_get($data, 'status', 1);
        $video->save();
        $this->model->videos()->save($video);
    }

    public function getVideoId($url)
    {
        $videoSites = new VideoSites();
        return $videoSites->getVideoId($url);
    }
}