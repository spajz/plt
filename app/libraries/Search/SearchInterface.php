<?php namespace Search;

interface SearchInterface
{
    public function searchWeb();

    public function searchVideos();

    public function searchImages();

    public function nextPage();

    public function previousPage();
}