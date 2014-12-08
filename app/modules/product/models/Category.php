<?php namespace App\Modules\Product\Models;

use Base;
use App\Modules\Product\Models\Product;
use Spajz\Modval\Modval;
use Session;
use ItemsHelper;

class Category extends Base
{
    protected $table = 'groups';
    protected $primaryKey = 'group_id';
    public $timestamps = true;
    protected $guarded = array('group_id');
    protected $fillable = array(
        'group_name',
        'title',
        'sort',
        'parent_id',
        'intro',
        'description',
        'seo_title',
        'seo_keywords',
        'seo_description',
        'slug',
        'show_menu',
        'status',
        'product_sum_1',
        'product_sum_0',
    );

    protected static $rules = array(
        'save' => array(
            'group_name' => 'required',
            'parent_id' => 'required',
            // 'title' => 'required',
        ),
        'create' => array(),
        'update' => array( //'slug' => 'required',
        )
    );

    public function scopeFilter($query, $value = null)
    {
        $status = Session::get('options.category.status');

        if (is_numeric($status) && $status >= 0) {
            $query->where('status', '=', $status);
        }
    }

    public function products()
    {
        return $this->hasMany('App\Modules\Product\Models\Product', 'group_id');
    }

    public function productsCount($status = null)
    {
        return $this->products()->filter()->status($status)->count();
    }

    public function productsCountChildren($group_id = null, $countSelf = true)
    {
        $sum = 0;
        $children = array();
        if ($countSelf) $sum = $this->productsCount();

        $ids = self::getAllChildrenId($group_id);
        if (is_array($ids)) {
            $ids = array_filter($ids, 'strlen');
            if (!empty($ids)) $children = self::whereIn('group_id', $ids)->get();
        }

        if (count($children)) {
            foreach ($children as $child) {
                $sum += $child->productsCount();
            }
        }

        return $sum;
    }

    public static function getAllChildrenId($group_id = null)
    {
        $categories = self::select(array('group_id', 'parent_id'))->get();
        return self::getChildrenRecursive($categories, $group_id);
    }

    public static function getChildrenRecursive($array, $id)
    {
        // Return false if $initialParent doesn't exist
        if ($id == 0) $id = "";
        if (!isset($array[$id])) return null;

        // Loop data and assign children by reference
        foreach ($array as $item) {
            if ($item['parent_id'] == $id) {
                $out[] = $item['group_id'];
                $out = array_merge((array)$out, (array)self::getChildrenRecursive($array, $item['group_id']));
            }
        }

        // Return the data
        return isset($out) && is_array($out) ? $out : array();
    }

    public static function withProducts()
    {
        return Category::whereIn('group_id', Product::where('show_item', 1)->distinct()->select('group_id')->lists('group_id'))
            ->orderBy('group_name')
            ->get();
    }

    public function scopeChildren($query, $value)
    {
        return $query->where('parent_id', '=', $value);
    }

    public static function menu()
    {
        return new ItemsHelper(self::all());
    }

    public function attr()
    {
        return $this->hasMany('App\Modules\Product\Models\Attribute', 'group_id', 'group_id')->orderBy('sort');
    }

}