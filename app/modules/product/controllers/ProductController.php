<?php  namespace App\Modules\Product\Controllers;

use App\Modules\Frontend\Controllers\FrontendController;
use Menu;
use Helper;
use Datatables;
use Request;
use URL;
use Breadcrumbs;
use View;
use DB;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Category;
use Cart;
use Notification;

class ProductController extends FrontendController
{
    public function home()
    {
        echo 'home2';
        exit;
    }

    public function showSingle($slug, $id)
    {

        $product = Product::where('proizvod_id', $id)->first();

        $featured = Product::where('show_item', 1)->orderBy(DB::raw('RAND()'))->take(5)->get();

        $categories = Category::withProducts();

        $this->render('product::frontend.single', array(
                'product' => $product,
                'categories' => $categories,
                'featured' => $featured,
        ));
    }

    public function showList($slug, $id)
    {

        $vars['products'] = Product::where('show_item', 1)
                ->where('group_id', $id)
                ->paginate(15);

        $vars['category'] = Category::where('group_id', $id)->first();

        $vars['subCategories'] = Category::children($id)->get();

        $vars['featured'] = Product::where('show_item', 1)->orderBy(DB::raw('RAND()'))->take(5)->get();

        $vars['specials'] = Product::select('*')->orderBy('proizvod_id')->limit(5)->get();

        $this->render('product::frontend.list', $vars);
    }

    public function tree()
    {
        $items = Category::orderBy('sort', 'asc')->get();

        $tree = $this->itemsToTree($items);

        $this->itemsTreeCount($tree);
        //$array = array('children' => $this->itemsToArray($tree));

        return $this->itemsToList($tree);

    }

    public function itemsTreeCount($items)
    {
        $count = 0;
        $i = 0;
        $sub_items_total = 0;
        foreach ($items as $item) {
            $sub_items = 0;
            if (!empty($item['children'])) {
                $tree_count = $this->itemsTreeCount($item['children']);
                $item['total_children'] = $tree_count['count'];
                $sub_items = $tree_count['sub_items'];
            }

            if (isset($item['total_children'])) {
                $count = $count + $item['total_children'] + $item['count'];
                $item['total'] = $item['total_children'] + $item['count'];
            } else {
                $item['total'] = $item['count'];
                $count += $item['count'];
            }
            if ($sub_items) {
                $item['sub_items'] = $sub_items;
                $sub_items_total += $sub_items;
            }
            $i++;
        }
        $out['count'] = $count;
        $out['sub_items'] = $i + $sub_items_total;
        return $out;
    }

    function itemsToTree($items, $parentId = 0)
    {
        $out = array();

        foreach ($items as $item) {
            if ($item['parent_id'] == $parentId) {
                $item['count'] = $item->products()->status()->count();
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
            $out .= '<li id="' . $item->group_id . '">' . "\n";
            $out .= '<a href="">';
            $out .= $item->group_name;

            if ($item['count'] != $item['total'] && $item['count'] > 0) $out .= ' <small>(' . $item['count'] . ')</small>';
            $out .= ' <small>(' . $item['total'] . ')</small>';
            $out .= '</a>';
            if (!empty($item['children'])) {
                $out .= '<ul>' . "\n";
                $out .= $this->itemsToList($item['children']) . "\n";
                $out .= '</ul>' . "\n";
            }
            $out .= '</li>';
        }
        return $out;
    }


    public function addToCart($id, $quantity = 1)
    {
        $out = array();
        if ($product = Product::where('proizvod_id', $id)->first()) {
            $items = array(
                    'id' => $product->proizvod_id,
                    'name' => $product->proizvod,
                    'price' => cena($product->cena_prodajna, true, null, false),
                    'quantity' => $quantity
            );

            //Make the insert...
            Cart::insert($items);

            Notification::successInstant('Proizvod ' . $product->proizvod . ' ste uspešno ubacili u korpu.');
            $out['msg'] = Notification::showAll();
            $out['total_items'] = Cart::totalItems(true);
            $out['total_quantity'] = Cart::totalItems();
            $out['total_price'] = Cart::total(false);

            echo json_encode($out);
            exit;

        }
    }


}