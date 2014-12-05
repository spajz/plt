<?php  namespace App\Modules\Product\Api\Admin;

use App\Modules\Product\Models\Category;
use Menu;
use Input;
use Msg;
use Session;

class CategoryApi
{
    protected $base = '';
    protected $href = 'index';
    protected $hrefParameters = array();


    public function setBase($base)
    {
        $this->base = $base;
    }

    public function setHref($value)
    {
        $this->href = $value;
    }

    public function setHrefParameters($array = array())
    {
        $this->hrefParameters = $array;
    }

    public function getTree($id = null, $root = false)
    {
        /*
         [activeKey] => 45
         [focusedKey] => 19
         [expandedKeyList] => 1,31,22,6,26,99,18,3,15,46,13,2,21,45,8,7
         */

        $items = Category::filter()->orderBy('sort', 'asc')->get();

        $tree = $this->itemsToTree($items);

        if (Session::get('options.category.count_products'))
            $this->itemsTreeCount($tree);

        $array = $this->itemsToArray($tree);

        if ($root)
            array_unshift($array, array('title' => 'ROOT', 'key' => 0, 'href' => '#'));

        echo json_encode($array);
        exit;
    }

    public function updateTree()
    {
        if (!$data = Input::get('data')) return;

        $this->tree($data);

        exit;
    }

    public function tree($data, $parent_id = 0, &$count = 0)
    {

        foreach ($data as $value) {

            if ($item = Category::find($value['key'])) {

                $count++;

                $item->sort = $count;
                $item->parent_id = $parent_id;
                $item->save();

                if (isset($value['children'])) {
                    $this->tree($value['children'], $value['key'], $count);
                }
            }
        }
    }

    public function treeCount2($items)
    {
        foreach ($items as $item) {
            $this->itemsTreeCount($item);
        }
    }

    public function itemsTreeCount(&$items)
    {
        $count = 0;
        $i = 0;
        $subItemsTotal = 0;
        foreach ($items as $item) {
            $subItems = 0;
            $ch = $item['children'];
            if (!empty($ch)) {
                $treeCount = $this->itemsTreeCount($ch);
                $item['total_children'] = $treeCount['count'];
                $subItems = $treeCount['sub_items'];
            }

            if (isset($item['total_children'])) {
                $count = $count + $item['total_children'] + $item['count'];
                $item['total'] = $item['total_children'] + $item['count'];
            } else {
                $item['total'] = $item['count'];
                $count += $item['count'];
            }
            if ($subItems) {
                $item['sub_items'] = $subItems;
                $subItemsTotal += $subItems;
            }
            $i++;
        }
        $out['count'] = $count;
        $out['sub_items'] = $i + $subItemsTotal;
        return $out;
    }

    public function itemsToTree($items, $parentId = 0)
    {
        $out = array();

        foreach ($items as $item) {
            if ($item['parent_id'] == $parentId) {
                $item['count'] = null;
                if (Session::get('options.category.count_products'))
                    $item['count'] = $item->products()->filter()->status()->count();
                // Todo Group ID 0
                //if ($item['group_id'] == 0) continue;
                $children = $this->itemsToTree($items, $item['group_id']);
                if ($children) {
                    $item['children'] = $children;
                } else {
                    $item['children'] = array();
                }
                $out[] = $item;
            }
        }
        return $out;
    }

    public function itemsToList($items)
    {
        $out = '';
        foreach ($items as $item) {
            $out .= '<li id="' . $item->group_id . '">';
            $out .= $item->group_name;
            if ($item['count'] != $item['total'] && $item['count'] > 0) $out .= ' <small>(' . $item['count'] . ')</small>';
            $out .= ' <small>(' . $item['total'] . ')</small>';
            if (!empty($item['children'])) {
                $out .= '<ul>';
                $out .= $this->itemsToList($item['children']);
                $out .= '</ul>';
            }
            $out .= '</li>';
        }
        return $out;
    }

    public function itemsToArray($items)
    {

        /*
        [title] => Hladnjaci (74) (204)
        [key] => 2
        [isFolder] => false
        [isLazy] => false
        [tooltip] =>
        [href] =>
        [icon] =>
        [addClass] =>
        [noLink] => false
        [activate] => false
        [focus] => false
        [expand] => false
        [select] => false
        [hideCheckbox] => false
        [unselectable] => false
        [children] => Array

        [cookiesFound] => true
            [activeKey] => 45
            [focusedKey] => 45
            [expandedKeyList] => Array
                (
                    [0] => 1
                    [1] => 31
                    [2] => 22
                    [3] => 6
                    [4] => 26
                    [5] => 99
                    [6] => 18
                    [7] => 3
                    [8] => 15
                    [9] => 46
                    [10] => 13
                    [11] => 2
                    [12] => 21
                    [13] => 45
                    [14] => 8
                )

        */

        $expand = array();

        if (Input::get('expandedKeyList')) $expand = explode(',', Input::get('expandedKeyList'));

        $out = array();

        foreach ($items as $item) {
            $tmp = array();
            //$counter = '';
            $title = $item->group_name;
            if (isset($item->sub_items)) $title .= '<small class="green-subtitle"> (' . $item->sub_items . ')</small>';
            // if ($item['count'] != $item['total'] && $item['count'] > 0) $counter .= ' (' . $item['count'] . ')';
            if (Session::get('options.category.count_products')) {
                if ($item['total'] == $item['count'])
                    $title .= '<small class="add-title"> (' . $item['count'] . ')</small>';
                else
                    $title .= '<small class="add-title"> (' . $item['count'] . ') (' . $item['total'] . ')</small>';
            }
            $tmp['title'] = $title;
            $tmp['key'] = $item->group_id;
            if (isset($this->hrefParameters[0])) {
                $parameters = array($this->hrefParameters[0] => $item->{$this->hrefParameters[1]});
            } else {
                $parameters = $item->group_id;
            }
            $tmp['href'] = route($this->base . $this->href, $parameters);
            $tmp['target'] = '_self';

            if (Input::get('activeKey') && Input::get('activeKey') == $item->group_id) $tmp['activate'] = true;
            if (Input::get('focusedKey') && Input::get('focusedKey') == $item->group_id) $tmp['focus'] = true;
            if (in_array($item->group_id, $expand)) {
                $tmp['expand'] = true;
            }

            if (!empty($item['children'])) {
                $tmp['children'] = $this->itemsToArray($item['children']);
            }

            $out[] = $tmp;
        }
        return $out;
    }

    public function updateSumOfProducts()
    {
        $categories = Category::all();
        if (count($categories)) {
            foreach ($categories as $category) {
                $category->product_sum_0 = $category->productsCount(0);
                $category->product_sum_1 = $category->productsCount(1);
                $category->save();
            }
        }
    }


}