<?php  namespace App\Modules\Admin\Api;

use App\Modules\Product\Models\Category;
use Menu;
use Input;
use Msg;
use Validator;
use Img;
use Config;
use Intervention\Image\Exception\ImageNotWritableException;
use App\Modules\Admin\Models\Image;
use Str;
use File;


class ImageApi
{
    protected $tmpFolder;
    public $config;
    public $errors = array();
    public $sizeError = array();
    public $cleanFilename = true;
    public $metaData = array();
    public $uploads = array();
    public $files = array();
    public $model;
    public $originalName;
    public $alt = null;
    public $altName = 'alt';
    public $name = null;
    public $sourcePath = null;
    public $savedImagePath = null;
    public $savedImageName = null;
    public $currentKey = null;
    public $downloadedImages = array();
    public $url = array();
    public $overwrite = false;
    protected $processType = 'upload';


    public function __construct($config = array())
    {
        $this->config = $config;
        $this->tmpFolder = app_path() . '/tmp/';
    }

    public function setMetaData($metaData)
    {
        $this->metaData = $metaData;
    }

    public function file($files = array())
    {
        $this->processType = 'file';

        foreach ((array)$files as $file) {
            if (!is_file($file)) {
                $this->errors[] = $file . ' not exists.';
            } else {
                $this->files[] = $file;
            }
        }
    }

    public function cleanTmpFolder()
    {
        $files = File::files($this->tmpFolder);
        if (!$files) return;

        foreach ($files as $file) {
            if ((time() - File::lastModified($file)) > 86400) {
                unlink($file);
            }
        }
    }

    public function remote($url = array())
    {
        $this->processType = 'remote';
        $this->cleanTmpFolder();

        $files = array();
        foreach ((array)$url as $key => $value) {
            if ($file = $this->downloadImage($value)) {
                $this->url[] = $value;
                $files[] = $file;
            }
        }
        if ($files) {
            $this->files = $files;
            return true;
        }

        return false;
    }

    public function process()
    {
        switch ($this->processType) {
            case 'upload':
                $this->processUpload();
                break;
            case 'file':
                $this->processFile();
                break;
            case 'remote':
                $this->processFile();
                break;
            case 'update':
                $this->processFile(false);
                break;
        }
    }

    public function update($fileName, $actions = array())
    {
        $parts = explode('?', basename($fileName));
        $fileName = $parts[0];

        $this->processType = 'update';

        $config = $this->config();
        $destinationPath = array_get($config, 'images.path');

        // Copy original image to temp folder
        $tmpFile = $this->tmpFolder . $fileName;
        File::copy($destinationPath . 'original/' . $fileName, $tmpFile);

        $actions = array('actions' => $actions);

        $image = $this->actions(Img::make($tmpFile), $actions);

        try {
            $image->save();
        } catch (ImageNotWritableException $e) {
            $this->errors[] = 'Error during saving file: "' . $tmpFile . '".';
        }

        $this->files[] = $tmpFile;

    }

    public function upload($input = null)
    {
        $this->processType = 'upload';

        if ($input && Input::hasFile($input)) {
            $uploads = Input::file($input);
        } else {
            return false;
        }

        if ($uploads) {

            // Make sure it really is an array
            if (!is_array($uploads)) {
                $uploads = array($uploads);
            }
            $this->uploads = $uploads;
            return $this;
        } else {

            // No files have been uploaded
            $this->errors[] = 'No files have been uploaded.';
            return false;
        }
    }

    public function processFile($saveData = true)
    {
        if (empty($this->files)) {
            $this->errors[] = 'There is no files for processing.';
            return false;
        }

        foreach ($this->files as $key => $file) {
            $this->originalName = basename($file);
            if (!is_file($file) || !getimagesize($file)) {
                $this->errors[] = 'Image: "' . $this->originalName . '" is corrupted.';
                continue;
            }
            $this->sourcePath = $file;
            $this->currentKey = $key;

            if ($this->validate($file)) {
                $this->makeImage($file);
                if (!empty($this->sizeError) && !is_null($this->savedImageName)) {
                    $this->deleteFile($this->savedImageName);
                }
                if ($saveData) $this->saveData();
            }
        }
    }


    public function processUpload()
    {

        if (empty($this->uploads)) return false;

        // Loop through all uploaded files
        foreach ($this->uploads as $key => $upload) {

            // Ignore array member if it's not an UploadedFile object, just to be extra save
            if (!is_a($upload, 'Symfony\Component\HttpFoundation\File\UploadedFile')) {
                continue;
            }

            $this->originalName = $upload->getClientOriginalName();
            $this->sourcePath = $upload->getRealPath();
            $this->currentKey = $key;

            if ($this->validate($upload)) {

                $this->makeImage($upload);
                if (!empty($this->sizeError) && !is_null($this->savedImageName)) {
                    $this->deleteFile($this->savedImageName);
                }
                $this->saveData();
            }
        }
    }

    public function findAlt()
    {
        if (isset($this->metaData[$this->altName][$this->currentKey])) {
            $this->alt = $this->metaData[$this->altName][$this->currentKey];
        } elseif (isset($this->metaData[$this->altName]) && is_string($this->metaData[$this->altName])) {
            $this->alt = $this->metaData[$this->altName];
        } else {
            $this->alt = null;
        }
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setCleanFilename($clean)
    {
        $this->cleanFilename = $clean;
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function makeImage($image)
    {
        // Check image
        if (getimagesize($this->sourcePath))

            $config = $this->config();

        if ($sizes = array_get($config, 'images.sizes')) {
            foreach ($sizes as $key => $size) {
                $image = $this->actions(Img::make($this->sourcePath), $size);
                if (!$this->save($image, $key)) {
                    $this->sizeError[] = array(
                            'size' => $key,
                    );
                }
            }
        }
    }

    public function saveData()
    {
        $image = new Image();
        $image->image = $this->savedImageName;
        $image->status = 1;
        $this->findAlt();
        $image->alt = $this->alt;
        $image->save();
        $this->model->images()->save($image);
    }

    public function deleteFile($files, $sizesDelete = array())
    {
        $config = $this->config();
        $destinationPath = array_get($config, 'images.path');

        if ($sizes = array_get($config, 'images.sizes')) {
            foreach ($sizes as $key => $size) {
                if (!empty($sizesDelete) && !in_array($key, $sizesDelete)) continue;
                foreach ((array)$files as $file) {
                    if (is_file($destinationPath . $key . '/' . $file))
                        unlink($destinationPath . $key . '/' . $file);
                }
            }
        }
    }

    public function actions($image, $array = array())
    {
        if ($actions = array_get($array, 'actions')) {
            foreach ($actions as $action => $param) {
                $image = call_user_func_array(array($image, $action), $param);
            }
        }
        return $image;
    }

    public function save($image, $key)
    {
        $config = $this->config();
        $destinationDirPath = array_get($config, 'images.path');
        $filename = $this->checkName();

        try {
            $destinationPath = $destinationDirPath . $key . '/' . $filename;
            $image->save($destinationPath);
            $this->savedImageName = $filename;
            $this->savedImagePath = $destinationPath;
            return true;
        } catch (ImageNotWritableException $e) {
            $this->errors[] = 'Error during saving file: "' . $image->originalName . '".';
            return false;
        }
    }

    public function checkName($name = null, $addSufix = null)
    {
        $config = $this->config();
        if (!is_null($name)) {
            //
        } elseif (!is_null($this->name)) {
            $name = $this->name;
        } else {
            $name = $this->originalName;
            $name = $this->pathInfo($name, 'filename');
        }

        if ($this->cleanFilename) $name = $this->sanitize($name);

        $extension = array_get($config, 'images.filetype');

        $sufix = '';

        if ($addSufix) {
            $array = $this->checkSufix($name);
            $sufix = $array['sufix'];
            $basename = $array['basename'];
        }

        if ($sufix != '')
            $sufix = '-' . $sufix;

        if (isset($basename)) $name = $basename;

        $fullname = $name . $sufix . '.' . $extension;

        $image = Image::where('image', $fullname)->get();

        if (count($image) && !$this->overwrite) {
            $fullname = $this->checkName($name . $sufix, true);
        }

        return $fullname;
    }

    protected function checkSufix($string, $separator = '-')
    {
        $pos = strrpos($string, $separator);
        if ($pos !== false && is_numeric(substr($string, $pos))) {
            return array(
                    'basename' => substr($string, 0, $pos),
                    'sufix' => substr($string, $pos + 1) + 1,
            );
        }
        return array(
                'basename' => $string,
                'sufix' => 1,
        );
    }

    protected function pathInfo($path, $key = null)
    {
        $pathInfo = pathinfo($path);
        if (!is_null($key))
            return $pathInfo[$key];
        return $pathInfo;
    }

    public function config()
    {
        $config = $this->config;

        if (!is_array($config)) {
            $config = Config::get($config, array());
        }

        return $config;
    }

    public function validate($image)
    {
        $config = $this->config();

        $validator = Validator::make(
                array('file' => $image),
                array('file' => array_get($config, 'images.validator'))
        );

        if (!$validator->passes()) {
            $this->errors[] = 'File "' . $this->originalName . '":' . $validator->messages()->first('file');
            return false;
        } else {
            return true;
        }

        $file = Input::file('file'); // your file upload input field in the form should be named 'file'

        $destinationPath = 'uploads/' . str_random(8);
        $filename = $file->getClientOriginalName();
        //$extension =$file->getClientOriginalExtension(); //if you need extension of the file
        $uploadSuccess = Input::file('file')->move($destinationPath, $filename);

        if ($uploadSuccess) {
            return Response::json('success', 200); // or do a redirect with some message that file was uploaded
        } else {
            return Response::json('error', 400);
        };
    }

    function sanitize($name = null, $separator = '-')
    {
        $name = strtr($name, array('+' => '-'));
        return Str::slug($name, $separator);
    }

    function sanitize2($name = null, $separator = '-')
    {
        $name = utf8_encode(strtr(utf8_decode($name), utf8_decode('ŠŽšžŸÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåçèéêëìíîïñòóôõöøùúûüýÿ'), 'SZszYAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy'));
        $name = strtr($name, array('Þ' => 'TH', 'þ' => 'th', 'Ð' => 'DH', 'ð' => 'dh', 'ß' => 'ss', 'Œ' => 'OE', 'œ' => 'oe', 'Æ' => 'AE', 'æ' => 'ae', 'µ' => 'u', '+' => '-'));
        return preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array($separator, '.', ''), $name);
    }

    function downloadImage($url)
    {
        $parts = explode('?', basename($url));
        $image = $parts[0];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $raw = curl_exec($ch);
        curl_close($ch);
        if ($raw != false) {
            $file = $this->tmpFolder . $image;
            if (file_exists($file)) {
                unlink($file);
            }
            $fp = fopen($file, 'x');
            fwrite($fp, $raw);
            fclose($fp);
            return $file;
        }
        return false;

    }

    function getErrors()
    {
        return $this->errors;
    }

    public function cropImage($config = null, $overwrite = true)
    {
        if ($config)
            $this->setConfig($config);

        $crop = Input::get('crop');

        $actions = array(
                'crop' => array((int)$crop['w'], (int)$crop['h'], (int)$crop['x'], (int)$crop['y'])
        );

        $this->update(Input::get('image'), $actions);

        $this->overwrite = $overwrite;

        $this->process();
    }

}