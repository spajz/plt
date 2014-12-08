<?php  namespace App\Modules\Product\Controllers\Admin;

use App\Modules\Admin\Controllers\AdminController;
use App\Modules\Product\Models\Category;
use App\Modules\Product\Models\Attribute;
use Menu;
use Helper;
use Request;
use Breadcrumbs;
use View;
use Input;
use Response;
use Redirect;
use Msg;
use Datatable;
use App\Modules\Admin\Api\AdjacencyApi;

class CategoryController extends AdminController
{
    protected $viewDir = 'product::admin.category.';
    protected $base = 'admin.category.';
    public $data = array();
    protected $adjacencyApi;

    public $dtColumns = array(
        'category' => array('group_id', 'group_name', 'title', 'sort')
    );

    public function __construct()
    {
        parent:: __construct();

        View::share('base', $this->base);
        $base = $this->base;

        $this->adjacencyApi = new AdjacencyApi(new Category);
        $this->adjacencyApi->setBase($base);

        if (!Request::ajax()) {
            Breadcrumbs::register($base . 'index', function ($breadcrumbs) use ($base) {
                $breadcrumbs->parent('home');
                $breadcrumbs->push('Category', route($base . 'index'));
            });
        }
    }

    public function getDatatable()
    {
        $buttons = $this->viewDir . 'datatable/dt_buttons';

        return Datatable::query(Category::filter()->select($this->dtColumns['category']))
            ->showColumns($this->dtColumns['category'])
            ->addColumn('action', function ($model) use ($buttons) {
                return View::make($buttons, array('model' => $model))->render();
            })
            ->searchColumns(array('group_name', 'title'))
            ->orderColumns($this->dtColumns['category'])
            ->setSearchOperator("LIKE")
            ->make();
    }

    public function getTree($id = null, $root = false)
    {
        $adjacencyApi = $this->adjacencyApi;

        $relatedItemCounter = function ($item) {
            return $item->products()->filter()->status()->count();
        };

        $this->adjacencyApi->setRelatedItemCounter($relatedItemCounter);

        $adjacencyApi->setHref('edit');

        $adjacencyApi->getTree($id, $root);
    }

    public function updateTree()
    {
        if (!$data = Input::get('data')) return;

        $this->adjacencyApi->tree($data);
        exit;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $col = array_merge($this->dtColumns['category'], array('action'));

        $table = Datatable::table()
            ->addColumn($col)// these are the column headings to be shown
            ->setUrl(route('admin.category.api.datatable'))// this is the route where data will be retrieved
            ->noScript()
            ->setOptions('bServerSide', true)
            ->setOptions('bStateSave', true)
            ->setOptions('bAutoWidth', false);

        $vars['table'] = $table;

        $this->footerScripts[] = Helper::jsScript('', true, 'load');
        $this->currentBreadcrumb = $this->base . 'index';
        $this->display($this->viewDir . 'index', $vars);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $base = $this->base;
        Breadcrumbs::register($this->base . 'create', function ($breadcrumbs) use ($base) {
            $breadcrumbs->parent($base . 'index');
            $breadcrumbs->push('Create', route($base . 'create'));
        });

        $this->currentBreadcrumb = $this->base . 'create';
        $this->display($this->viewDir . 'create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $item = new Category(Input::all());
        if ($item->save()) {
            Msg::info('Item successfully saved.');
            if (Input::get('save') == 'edit') return Redirect::route($this->base . Input::get('save'), $item->group_id);
            return Redirect::route($this->base . Input::get('save'));

        } else {
            Msg::danger($item->getErrors()->all());
            //dd(Input::all());
            return Redirect::route($this->base . 'create')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $base = $this->base;
        Breadcrumbs::register($this->base . 'edit', function ($breadcrumbs) use ($id, $base) {
            $breadcrumbs->parent($base . 'index');
            $breadcrumbs->push('Edit', route($base . 'edit', $id));
        });

        $vars['category'] = Category::find($id);

        $this->currentBreadcrumb = $this->base . 'edit';
        $this->display($this->viewDir . 'edit', $vars);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $item = Category::find($id);
        $item->update(Input::all());
        if ($item->save()) {
            Msg::info('Item successfully updated.');

            // Inser new attribute
            if (Input::get('attribute')) {
                $attr = new Attribute;
                $attr->title = Input::get('attribute');
                $attr->status = 1;
                $attr->filter = Input::get('filter');
                $item->attr()->save($attr);
            }

            // Update attributes
            if (Input::get('attributes')) {
                foreach (Input::get('attributes') as $key => $attribute) {
                    $updateAttr = Attribute::find($key);
                    if ($updateAttr) {
                        $updateAttr->title = $attribute;
                        $updateAttr->filter = Input::get('filters.' . $key);
                        $updateAttr->save();
                    }
                }
            }

            if (Input::get('save') == 'edit') return Redirect::route($this->base . Input::get('save'), $id);
            return Redirect::route($this->base . Input::get('save'));
        } else {

            Msg::danger($item->getErrors()->all());

            return Redirect::route($this->base . 'edit', $id)
                ->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $item = Category::find($id);
        if (count($item->products))
            Msg::danger('This group has a product(s). You can not delete it. First delete all products from the group.');

        if (count($item->children($id)->get()))
            Msg::danger('This group has a subgroup(s). You can not delete it. First delete all subgroups.');

        if (!Msg::has('danger')) {
            Category::destroy($id);
            Msg::success('Item "' . $item->title . '" successfully deleted.');
        }

        return Redirect::back();
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
    }


}