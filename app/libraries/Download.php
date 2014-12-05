<?php

class Download
{
    public $url;
    public $basename;
    public $extension;
    public $dir;
    protected $data;

    public function __construct($dir = null)
    {
        if ($dir)
            $this->dir = $dir;
        else
            $this->dir = '/var/www/spajz/tmp/';
    }

    public function setDir($dir = null)
    {
        $this->dir = $dir;
    }

    public function getData($url, $type = 'curl')
    {
        $this->url = $url;
//        $pathParts = pathinfo($url);
//        $this->basename = Str::slug($pathParts['basename']);
//        $this->extension = Str::lower($pathParts['extension']);

        $this->data = $this->get($url, $type);
    }

    public function saveData($filename = null, $extension = true)
    {
        $this->save($filename, $extension);
    }

    protected function save($filename = null, $extension = true)
    {
        if ($extension)
            $extension = '.' . $this->extension;
        else
            $extension = '';
        if (!$filename)
            $filename = uniqid() . $extension;
        else
            $filename = $filename . $extension;
        $fp = fopen($this->dir . $filename, 'w+');
        fwrite($fp, $this->data);
        fclose($fp);
    }

    protected function get($url, $type = 'curl')
    {
        if ($type == 'curl') {
            $ch = curl_init();
            $timeout = 20;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            //curl_setopt($ch, CURLOPT_HEADERFUNCTION, "read_header");
            $data = curl_exec($ch);

            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            $contentType = explode(';', trim($contentType));
            $contentType = trim($contentType[0]);
            $extension = file_ext($contentType, false);

            curl_close($ch);
        } else {
            $data = File::get($url);
            $extension = File::extension($url);
        }
        $this->data = $data;
        $this->extension = $extension;
        return $data;
    }

}