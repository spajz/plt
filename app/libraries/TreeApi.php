<?php

class TreeApi
{
    protected $items;
    protected $id = 'id';
    protected $parentId = 'parent_id';
    protected $title = 'title';
    protected $container = array('<ul>', '</ul>');
    protected $element = array('<li>', '</li>');
    protected $tree;
    protected $itemClosure = null;

    public function __construct($items)
    {
        $this->items = $items;

        if (count($items) && $items instanceof Illuminate\Database\Eloquent\Collection) {
            $item = $items->first();
            $this->id = $item->getProperty();
        }
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function setElement($element)
    {
        $this->element = $element;
    }

    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setItemClosure($closure)
    {
        $this->itemClosure = $closure;
    }

    public function getTree($parent = 0, $depth = 0)
    {
        return $this->generatePageTree($this->items, $parent, $depth);
    }

    protected function generatePageTree($data, $parent = 0, $depth = 0)
    {
        $tree = $this->container[0];
        foreach ($data as $item) {
            if ($item->{$this->parentId} == $parent) {
                $tree .= $this->element[0];

                if (is_callable($this->itemClosure)) {
                    $closure = $this->itemClosure;
                    $tree .= $closure($item, $this);
                } else {
                    $tree .= $item->{$this->title};
                }

                $tree .= $this->generatePageTree($data, $item->{$this->id}, $depth + 1);
                $tree .= $this->element[1];
            }
        }
        $tree .= $this->container[1];
        return $tree;
    }
}