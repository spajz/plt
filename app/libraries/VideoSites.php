<?php

class VideoSites
{
    protected $attributes = array(
            'width' => 560,
            'height' => 315
    );

    public function getVideoId($url)
    {
        $host = get_domain($url);

        switch (true) {
            case strpos($host, 'youtu') === 0:
                preg_match('#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#', $url, $matches);
                return isset($matches[0]) ? $matches[0] : false;
                break;

            case strpos($host, 'vimeo') === 0:
                preg_match('/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/', $url, $matches);
                return isset($matches[5]) ? $matches[5] : false;
                break;

            case strpos($host, 'dailymotion') === 0:
                $id = strtok(basename($url), '_');
                return !empty($id) ? $id : false;
                break;

            case strpos($host, 'metacafe') === 0:
                preg_match('/((http:\/\/)?(www\.)?metacafe\.com)(\/watch\/)(\d+)(.*)/i', $url, $matches);
                return isset($matches[5]) ? $matches[5] : false;
                break;
        }

//        if (strpos($host, 'youtu') === 0) {
//            preg_match('#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#', $url, $matches);
//            return isset($matches[0]) ? $matches[0] : false;
//        };
//
//        if (strpos($host, 'vimeo') === 0) {
//            preg_match('/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/', $url, $matches);
//            return isset($matches[5]) ? $matches[5] : false;
//        };
//
//        if (strpos($host, 'dailymotion') === 0) {
//            $id = strtok(basename($url), '_');
//            return !empty($id) ? $id : false;
//        };
//
//        if (strpos($host, 'metacafe') === 0) {
//            preg_match('/((http:\/\/)?(www\.)?metacafe\.com)(\/watch\/)(\d+)(.*)/i', $url, $matches);
//            return isset($matches[5]) ? $matches[5] : false;
//        };
    }

    public function getEmbedVideo($vid, $host = 'youtube', $attributes = array())
    {
        $attributes = array_merge($this->attributes, $attributes);
        $attributes = HTML::attributes($attributes);

        $host = $this->getDomainName($host);

        View::make('video_sites.' . $host, array('vid' => $vid, 'attributes' => $attributes));
    }

    public function getThumbUrl($vid, $url)
    {
        $host = get_domain($url);
        $host = $this->getDomainName($host);
        $thumb = null;

        switch ($host) {
            case 'youtube':
                //$thumb = 'http://img.youtube.com/vi/' . $vid . '/maxresdefault.jpg';
                $thumb = 'http://img.youtube.com/vi/' . $vid . '/0.jpg';
                break;
            case 'vimeo':
                $link = 'http://vimeo.com/api/v2/video/' . $vid . '.php';
                $response = unserialize(file_get_contents($link));
                $thumb = $response[0]['thumbnail_medium'];
                break;
            case 'dailymotion':
                $thumb = 'http://www.dailymotion.com/thumbnail/video/' . $vid;
                break;
            case 'metacafe':
                $title = explode('/', rtrim($url, '/'));
                $title = end($title);
                $thumb = 'http://s4.mcstatic.com/thumb/' . $vid . '/0/4/directors_cut/0/1/' . $title . '.jpg';
                break;
        }
        return $thumb;
    }

    protected function getDomainName($host)
    {
        $host = explode('.', $host);
        $host = $host[0];
        if ($host == 'youtu') $host = 'youtube';
        return $host;
    }
}