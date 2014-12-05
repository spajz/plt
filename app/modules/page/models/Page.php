<?php namespace App\Modules\Page\Models;

use Base;
use App\Modules\Product\Models\Product;
use Session;

class Page extends Base
{
    protected $table = 'pages';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = array('id');
    protected $fillable = array(
            'parent_id',
            'title',
            'slug',
            'description',
            'status',
            'sort',
    );

    protected static $rules = array(
            'save' => array(
                //   'parent_id' => 'required',
                // 'title' => 'required',
            ),
            'create' => array(),
            'update' => array( //'slug' => 'required',
            )
    );

    public function scopeFilter($query, $value = null)
    {
        $status = Session::get('options.page.status');

        if (is_numeric($status) && $status >= 0) {
            $query->where('status', '=', $status);
        }
    }

    public function childrens()
    {
        return $this->hasMany('App\Modules\Page\Models\Page', 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Modules\Page\Models\Page', 'parent_id');
    }

    public function childrensCount($status = null)
    {
        return $this->childrens()->filter()->status($status)->count();
    }

    public static function getAllChildrenId($id = null)
    {
        $pages = self::select(array('id', 'parent_id'))->get();
        return self::getChildrenRecursive($pages, $id);
    }

    public static function getChildrenRecursive($array, $id)
    {
        // Return false if $initialParent doesn't exist
        if ($id == 0) $id = "";
        if (!isset($array[$id])) return null;

        // Loop data and assign children by reference
        foreach ($array as $item) {
            if ($item['parent_id'] == $id) {
                $out[] = $item['id'];
                $out = array_merge((array)$out, (array)self::getChildrenRecursive($array, $item['id']));
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


}