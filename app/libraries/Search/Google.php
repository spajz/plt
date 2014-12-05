<?php namespace Search;

use Google_Client;
use Google_Service_Customsearch;
use Config;
use stdClass;
use DateInterval;
use Exception;

class Google
{
    protected $client;
    protected $search;
    protected $parameters = array();
    protected $siteFilter = null;
    protected $imageFilters = array();
    protected $numberOfItems = 10;
    protected $totalResults = null;
    protected $offset;
    protected $errors = array();

    public function __construct()
    {
        $client = new Google_Client();
        $client->setDeveloperKey(Config::get('search.google.apiKey'));
        $this->client = $client;
    }

    public function setApplicationName($name = null)
    {
        $this->client->setApplicationName($name);
    }

    public function getApplicationName()
    {
        return $this->client->getApplicationName();
    }

    protected function customSearch()
    {
        return new Google_Service_Customsearch($this->client);
    }

    public function searchInformation($key = 'totalResults')
    {
        if ($key)
            return $this->search->getSearchInformation()->$key;
        else
            return $this->search->getSearchInformation();
    }

    public function searchResult()
    {
        return $this->search->getItems();
    }

    public function searchWeb($term = null, $startIndex = 1)
    {
        $out = array();
        $search = $this->customSearch();
        $this->search = $search->cse->listCse($term, array(
                'cx' => Config::get('search.google.searchType.web'),
                'start' => $startIndex,
        ));

        if (!$this->searchInformation() > 0) return false;
        $this->setTotalResults();

        foreach ($this->searchResult() as $key => $item) {
            if ($obj = $this->prepareItem($item, 'web')) {
                $out[] = $obj;
            }
        }

        return $out;
    }

    public function searchImages($term = null, $startIndex = 1)
    {
        $out = array();
        $imageFilters = array();
        if ($this->processImageFilters()) {
            $imageFilters = $this->processImageFilters();
        }

        $this->setOffset($startIndex);

        $search = $this->customSearch();
        $this->search = $search->cse->listCse($term, array(
                        'cx' => Config::get('search.google.searchType.web'),
                        'searchType' => 'image',
                        'start' => $startIndex,
                ) + $imageFilters);

        if (!$this->searchInformation() > 0) return false;
        $this->setTotalResults();

        foreach ($this->searchResult() as $key => $item) {
            if ($obj = $this->prepareItem($item, 'image')) {
                $out[] = $obj;
            }
        }

        return $out;
    }

    public function searchVideos($term = null, $startIndex = 1)
    {
        $siteSearch = array();

        if ($this->siteFilter && Config::get('search.options.videoSites.' . $this->siteFilter)) {
            $siteSearch = array(
                    'siteSearch' => Config::get('search.options.videoSites.' . $this->siteFilter)
            );
        }

        $this->setOffset($startIndex);

        $search = $this->customSearch();
        $this->search = $search->cse->listCse($term, array(
                        'cx' => Config::get('search.google.searchType.allVideos'),
                        'start' => $startIndex,
                ) + $siteSearch);

        if (!$this->searchInformation() > 0) return false;
        $this->setTotalResults();

       //dd($this->searchResult());

        foreach ($this->searchResult() as $key => $item) {
            if ($obj = $this->prepareItem($item, 'video')) {
                $out[] = $obj;
            }
        }

        return $out;
    }

    public function getVideoObject($item)
    {
        $thisObj = $this;

        if (isset($item->pagemap['videoobject'][0]) && !empty($item->pagemap['videoobject'][0])) {
            $video = $item->pagemap['videoobject'][0];
            $out = new stdClass();
            $out->title = array_get($video, 'name');
            $out->url = $item->link;
            $out->description = array_get($video, 'description');
            $out->width = array_get($video, 'width');
            $out->height = array_get($video, 'height');
            $out->time = array_get($video, 'duration') ? $this->duration(array_get($video, 'duration')) : null;
            $out->host = get_domain($item->link);
            $out->id = $thisObj->getVideoId($item->link);
            return $out;
        } elseif ($thisObj->getVideoId($item->link)) {
            $out = new stdClass();
            $out->title = $item->title;
            $out->url = $item->link;
            $out->description = $item->snippet;
            $out->width = null;
            $out->height = null;
            $out->time = null;
            $out->host = get_domain($item->link);
            $out->id = $thisObj->getVideoId($item->link);
            return $out;
        } else {
            $out = new stdClass();
            $out->title = $item->title;
            $out->url = $item->link;
            $out->description = $item->snippet;
            $out->width = null;
            $out->height = null;
            $out->time = null;
            $out->host = get_domain($item->link);
            $out->id = null;
            return $out;
        }

        return false;
    }

    public function prepareItem($item = null, $type = 'web')
    {
        $contentImage = new stdClass();

        switch ($type) {
            case 'web':
                $contentImage->thumb = $this->getImageCseThumb($item);
                $item->contentImage = $contentImage;
                break;

            case 'image':
                $contentImage->thumb = $this->getImageThumb($item);
                $contentImage->full = $this->getImageFull($item);
                $item->contentImage = $contentImage;
                break;

            case 'video':
                if ($thumb = $this->getImageCseThumb($item))
                    $contentImage->thumb = $thumb;
                elseif ($thumb = $this->getImageCseFull($item)) {
                    $contentImage->thumb = $thumb;
                } else {
                    $contentImage->thumb = null;
                }
                $item->contentImage = $contentImage;
                $item->contentVideo = $this->getVideoObject($item);
                break;
        }

        return $item;
    }

    protected function getImageThumb($item)
    {
        $image = $item->getImage();

        $out = new stdClass();
        $out->url = $image->thumbnailLink;
        $out->type = null;
        $out->width = $image->thumbnailWidth;
        $out->height = $image->thumbnailHeight;
        $out->size = null;
        return $out;
    }

    protected function getImageFull($item)
    {
        $image = $item->getImage();

        $out = new stdClass();
        $out->title = $item->title;
        $out->url = $item->link;
        $out->type = $item->mime;
        $out->width = $image->width;
        $out->height = $image->height;
        $out->size = $image->byteSize;
        $out->source = $image->contextLink;
        return $out;
    }

    protected function getImageObject($item)
    {
        if (isset($item->pagemap['imageobject'][0]['url'])) {
            $image = $item->pagemap['imageobject'][0];
            $out = new stdClass();
            $out->title = null;
            $out->url = $image['url'];
            $out->type = null;
            $out->width = $image['width'];
            $out->height = $image['height'];
            $out->size = null;
            $out->source = null;
            return $out;
        }
        return false;
    }

    protected function getImageCseThumb($item)
    {
        if ($item->pagemap && isset($item->pagemap['cse_thumbnail'][0])) {
            $image = $item->pagemap['cse_thumbnail'][0];
            $out = new stdClass();
            $out->url = $image['src'];
            $out->type = null;
            $out->width = $image['width'];
            $out->height = $image['height'];
            $out->size = null;
            return $out;
        }
        return false;
    }

    protected function getImageCseFull($item)
    {
        if ($item->pagemap && isset($item->pagemap['cse_image'][0]['src'])) {
            $out = new stdClass();
            $out->title = null;
            $out->url = $item->pagemap['cse_image'][0]['src'];
            $out->type = null;
            $out->width = null;
            $out->height = null;
            $out->size = null;
            $out->source = null;
            return $out;
        }
        return false;
    }

    protected function setTotalResults()
    {
        $total = $this->searchInformation('totalResults');
        if ($total > 100) $total = 100;
        $this->totalResults = $total;
    }

    public function getTotalResults()
    {
        return $this->totalResults;
    }

    public function setSiteFilter($filter = null)
    {
        $this->siteFilter = $filter;
    }

    public function setParameters($parameters = array())
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    public function setImageFilters($filters = array())
    {
        $this->imageFilters = $filters;
    }

    protected function processImageFilters()
    {
        $out = array();

        $imageFilters = $this->imageFilters;
        if (empty($imageFilters)) return false;

        foreach ($imageFilters as $key => $filter) {
            if (!$filter) continue;
            switch ($key) {
                case 'imgSize':
                case 'imgType':
                    if ($filter != '')
                        $out[$key] = $filter;
                    break;

            }
        }
        return $out;
    }

    protected function getVideoId($url)
    {
        $host = get_domain($url);

        if (strpos($host, 'youtu') === 0) {
            preg_match('#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#', $url, $matches);
            return isset($matches[0]) ? $matches[0] : false;
        };

        if (strpos($host, 'vimeo') === 0) {
            preg_match('/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/', $url, $matches);
            return isset($matches[5]) ? $matches[5] : false;
        };

        if (strpos($host, 'dailymotion') === 0) {
            $id = strtok(basename($url), '_');
            return !empty($id) ? $id : false;
        };

        if (strpos($host, 'metacafe') === 0) {
            preg_match('/((http:\/\/)?(www\.)?metacafe\.com)(\/watch\/)(\d+)(.*)/i', $url, $matches);
            return isset($matches[5]) ? $matches[5] : false;
        };
    }

    protected function duration($duration)
    {
        try {
            $di = new DateInterval($duration);
            $sum = 0;
            if ($di->i > 0) $sum = $di->i * 60;
            return $di->s + $sum;

        } catch (Exception $e) {
            return null;
        }
    }

    public function nextPage()
    {
        $queries = $this->search->getQueries();
        if (isset($queries['nextPage'][0]['startIndex'])) {
            return $queries['nextPage'][0]['startIndex'];
        }
    }

    public function previousPage()
    {
        $queries = $this->search->getQueries();
        if (isset($queries['previousPage'][0]['startIndex'])) {
            return $queries['previousPage'][0]['startIndex'];
        }
    }

    protected function setOffset($offset)
    {
        $this->offset = $offset;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getNumberOfItems()
    {
        return $this->numberOfItems;
    }

}