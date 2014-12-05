<?php namespace Search;

use Config;
use stdClass;

class Bing
{
    protected $version;
    protected $baseUrl;
    protected $output;
    protected $content;
    protected $parameters = array();
    protected $numberOfItems = 50;
    protected $siteFilter = null;
    protected $imageFilters = array();
    protected $totalResults = null;
    protected $offset;
    protected $errors = array();

    public function __construct($output = 'json')
    {
        $apiKey = Config::get('search.bing.apiKey');
        $this->baseUrl = Config::get('search.bing.baseUrl');
        $this->output = $output;
        $this->version = 'v1';
        $auth = base64_encode("$apiKey:$apiKey");
        $data = array(
                'http' => array(
                        'request_fulluri' => true,
                        'ignore_errors' => true,
                        'header' => "Authorization: Basic $auth"
                )
        );
        $this->baseUrl .= $this->version;
        $this->content = stream_context_create($data);
    }

    protected function get($sources, $term = null, $startIndex = null)
    {
        $endpoint = 'Composite';

        $qs = "?\$format={$this->output}";
        $qs .= "&Sources='{$sources}'";

        if (is_numeric($startIndex))
            $qs .= "&\$skip={$startIndex}";
        //if (is_numeric($this->numberOfItems))
        //$qs .= "&\$top={$this->numberOfItems}";

        $parameters = $this->parameters;

        if ($this->siteFilter)
            $term = trim($this->siteFilter . ' ' . $term);
        $parameters['Query'] = "'{$term}'";

        if ($this->processImageFilters()) {
            $parameters['ImageFilters'] = "'{$this->processImageFilters()}'";
        }

        $qs .= ($parameters) ? "&" . http_build_query($parameters) : "";

        $return = file_get_contents($this->baseUrl . "/" . $endpoint . $qs, 0, $this->content);
        $json = json_decode($return);

        if ($json) {
            return $json;
        } else {
            $this->errors[] = $return;
            return false;
        }
    }

    public function searchWeb($term = null, $startIndex = 1)
    {
        return $this->get('Web', $term, $startIndex);
    }

    public function searchImages($term = null, $startIndex = 1)
    {
        $result = $this->get('Image', $term, $startIndex - 1);

        if (!$result) return false;

        if (!$this->setTotalResults('Image', $result)) return false;

        $items = $this->getResults('Image', $result);

        $this->setOffset($startIndex);

        if ($items) {
            foreach ($items as &$item) {
                $contentImage = new stdClass();
                $contentImage->thumb = $this->getImageThumb($item);
                $contentImage->full = $this->getImageFull($item);
                $item->contentImage = $contentImage;
            }
        }
        return $items;
    }

    public function searchVideos($term = null, $startIndex = 1)
    {
        $result = $this->get('Video', $term, $startIndex - 1);

        if (!$result) return false;

        if (!$this->setTotalResults('Video', $result)) return false;

        $items = $this->getResults('Video', $result);

        $this->setOffset($startIndex);

        if ($items) {
            foreach ($items as &$item) {
                $contentImage = new stdClass();
                $contentImage->thumb = $this->getImageThumb($item);
                $item->contentImage = $contentImage;
                $item->contentVideo = $this->getVideo($item);
            }
        }
        return $items;
    }

    protected function getImageThumb($item)
    {
        $out = new stdClass();
        $out->url = $item->Thumbnail->MediaUrl;
        $out->type = $item->Thumbnail->ContentType;
        $out->width = $item->Thumbnail->Width;
        $out->height = $item->Thumbnail->Height;
        $out->size = $item->Thumbnail->FileSize;
        return $out;
    }

    protected function getImageFull($item)
    {
        $out = new stdClass();
        $out->title = $item->Title;
        $out->url = $item->MediaUrl;
        $out->type = $item->ContentType;
        $out->width = $item->Width;
        $out->height = $item->Height;
        $out->size = $item->FileSize;
        $out->source = $item->SourceUrl;
        return $out;
    }

    protected function getVideo($item)
    {
        $thisObj = $this;
        $out = new stdClass();
        $out->title = $item->Title;
        $out->url = $item->MediaUrl;
        $out->description = null;
        $out->width = null;
        $out->height = null;
        $out->time = $this->duration($item->RunTime);
        $out->host = get_domain($item->MediaUrl);
        $out->id = $thisObj->getVideoId($item->MediaUrl);
        return $out;
    }

    protected function setNumberOfItems($number)
    {
        $this->numberOfItems = $number;
    }

    public function getNumberOfItems()
    {
        return $this->numberOfItems;
    }

    public function setSiteFilter($filter = null)
    {
        $this->siteFilter = $filter;
    }

    protected function setTotalResults($type, $result)
    {
        if (!is_array($result->d->results)) {
            $this->totalResults = null;
            return false;
        }
        $total = reset($result->d->results)->{$type . 'Total'};
        if ($total > 500) $total = 500;
        $this->totalResults = $total;
        return true;
    }

    public function getTotalResults()
    {
        return $this->totalResults;
    }

    protected function getResults($type, $result)
    {
        if (!is_array($result->d->results)) return false;
        return reset($result->d->results)->{$type};
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
                case 'imgAspect':
                    $out[$key] = 'Aspect:' . $filter;
                    break;
                case 'imgSize':
                    $out[$key] = 'Size:' . $filter;
                    break;
                case 'imgType':
                    if (in_array($filter, array('Photo', 'Graphics'))) {
                        $out[$key] = 'Style:' . $filter;
                        break;
                    }
                    if (in_array($filter, array('Face', 'Portrait'))) {
                        $out[$key] = 'Face:' . $filter;
                        break;
                    }
                case 'imgDimension':
                    if (!is_array($filter)) break;
                    if (isset($filter['height']) && is_numeric($filter['height']))
                        $out[$key][] = 'Size:Height:' . $filter['height'];
                    if (isset($filter['width']) && is_numeric($filter['width']))
                        $out[$key][] = 'Size:Width:' . $filter['width'];
                    break;
            }
        }

        if (isset($out['imgDimension'])) {
            $return = implode('+', $out['imgDimension']);
            if (isset($out['imgType'])) $return .= '+' . $out['imgType'];

            return $return;
        }

        return implode('+', $out);
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
        return (int)round($duration / 1000);
    }

    public function nextPage()
    {
        return $this->offset + $this->numberOfItems;
    }

    public function previousPage()
    {
        return $this->offset - $this->numberOfItems;
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


}