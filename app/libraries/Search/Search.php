<?php namespace Search;

use Search\Google;
use Search\Bing;
use Search\SearchInterface;
use HTML;

class Search
{
    protected $engine;

    public function __construct($engine)
    {
        $engine = ucfirst($engine);
        $engine = 'Search\\' . $engine;
        $this->engine = new $engine();
    }

    public function setEngine(SearchInterface $engine)
    {
        $this->engine = $engine;
    }

    public function getEngine()
    {
        return $this->engine;
    }

    public function searchWeb($term = null, $startIndex = null)
    {
        return $this->engine->searchWeb($term, $startIndex);
    }

    public function searchImages($term = null, $startIndex = null)
    {
        return $this->engine->searchImages($term, $startIndex);
    }

    public function searchVideos($term = null, $startIndex = null)
    {
        return $this->engine->searchVideos($term, $startIndex);
    }

    public function getNumberOfItems()
    {
        return $this->engine->getNumberOfItems();
    }

    public function setSiteFilter($filter = null)
    {
        $this->engine->setSiteFilter($filter);
    }

    public function getTotalResults()
    {
        return $this->engine->getTotalResults();
    }

    public function setParameters($parameters = array())
    {
        $this->engine->setParameters($parameters);
    }

    public function setImageFilters($filters = array())
    {
        $this->engine->setImageFilters($filters);
    }

    public function nextPage()
    {
        return $this->engine->nextPage();
    }

    public function previousPage()
    {
        return $this->engine->previousPage();
    }

    public function getOffset()
    {
        return $this->engine->getOffset();
    }

    public function getErrors()
    {
        return $this->engine->getErrors();
    }

}
 
 