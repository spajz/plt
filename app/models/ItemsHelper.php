<?php

class ItemsHelper
{

    protected $id = 'id';
    protected $parentId = 'parent_id';
    private $items = false;

    public function __construct($items)
    {
        $this->items = $items;

       // dd($items->lists('group_name', 'group_id'));
        if(count($items) && $items instanceof Illuminate\Database\Eloquent\Collection){
            $item = $items->first();
            $this->id = $item->getProperty();
        }
    }

    public function htmlList()
    {
        return $this->htmlFromArray($this->itemArray());
    }

    private function itemArray()
    {
        $result = array();
        foreach ($this->items as $key => $item) {
            if ($item->{$this->parentId} == 0) {
                $result[$item->group_id] = $this->itemWithChildren($item);
            }
        }
        return $result;
    }

    private function childrenOf($item)
    {
        $result = array();
        foreach ($this->items as $i) {
            if ($i->{$this->parentId} == $item->{$this->id}) {
                $result[] = $i;
            }
        }
        return $result;
    }

    private function itemWithChildren($item)
    {
        $result = array();
        $children = $this->childrenOf($item);
        foreach ($children as $child) {
            $result[$child->group_id] = $this->itemWithChildren($child);
        }
        return $result;
    }

    private function htmlFromArray($array)
    {
        $html = '';
        foreach ($array as $k => $v) {
            $html .= "<ul>";
            $html .= "<li>" . $this->items[$k]->group_name . "</li>";
            if (count($v) > 0) {
                $html .= $this->htmlFromArray($v);
            }
            $html .= "</ul>";
        }
        return $html;
    }
}