<?php  namespace App\Modules\Page\Controllers\Admin;

use App\Modules\Admin\Controllers\AdminController;
use App\Modules\Page\Models\Page;
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

class PageController extends AdminController
{
    protected $viewDir = 'page::admin.page.';
    protected $base = 'admin.page.';
    public $data = array();
    protected $adjacencyApi;

    public $dtColumns = array(
            'page' => array('id', 'title')
    );

    public function __construct()
    {
        parent:: __construct();

        View::share('base', $this->base);
        $base = $this->base;

        $this->adjacencyApi = new AdjacencyApi(new Page);
        $this->adjacencyApi->setBase($base);

        if (!Request::ajax()) {
            Breadcrumbs::register($base . 'index', function ($breadcrumbs) use ($base) {
                $breadcrumbs->parent('home');
                $breadcrumbs->push('Page', route($base . 'index'));
            });
        }
    }

    public function getDatatable()
    {
        $thisObj = $this;
        $buttons = $this->viewDir . 'datatable/dt_buttons';
        return Datatable::query(Page::filter()->select($this->dtColumns['page']))
                ->showColumns($this->dtColumns['page'])
                ->addColumn('action', function ($model) use ($buttons, $thisObj) {
                    return View::make($buttons, array('model' => $model, 'thisObj' => $thisObj))->render();
                })
                ->searchColumns(array('id', 'title'))
                ->orderColumns($this->dtColumns['page'])
                ->setSearchOperator("LIKE")
                ->make();
    }


    public function getTree($id = null, $root = false)
    {
        $adjacencyApi = $this->adjacencyApi;

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
        $col = array_merge($this->dtColumns['page'], array('action'));

        $table = Datatable::table()
                ->addColumn($col) // these are the column headings to be shown
                ->setUrl(route('admin.page.api.datatable')) // this is the route where data will be retrieved
                ->noScript()
                ->setOptions('bServerSide', true)
                ->setOptions('bStateSave', true)
                ->setOptions('bAutoWidth', false);

        $vars['table'] = $table;
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
        $item = new Page(Input::all());
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

        $vars['page'] = Page::find($id);
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
        $item = Page::find($id);
        $item->update(Input::all());
        if ($item->save()) {
            Msg::info('Item successfully updated.');
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
        $item = Page::find($id);
        if (count($item->products))
            Msg::danger('This group has a product(s). You can not delete it. First delete all products from the group.');

        if (count($item->children($id)->get()))
            Msg::danger('This group has a subgroup(s). You can not delete it. First delete all subgroups.');

        if (!Msg::has('danger')) {
            Page::destroy($id);
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