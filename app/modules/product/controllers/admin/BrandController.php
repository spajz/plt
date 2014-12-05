<?php  namespace App\Modules\Product\Controllers\Admin;

use App\Modules\Admin\Controllers\AdminController;
use App\Modules\Product\Models\Brand;
use Menu;
use Helper;
use Datatables;
use Request;
use Breadcrumbs;
use View;
use Config;

class BrandController extends AdminController
{

    protected $view_dir = 'product::admin.brand.';
    protected $base = 'admin.brand.';

    public $dt_columns = array(
        'brand' => array('proizvodjac_id', 'proizvodjac', 'proizvodjac_id as action_tmp')
    );

    public function __construct()
    {
        parent:: __construct();

        View::share('base', $this->base);


        if (!Request::ajax()) {
            Breadcrumbs::register($this->base . 'index', function ($breadcrumbs) {
                    $breadcrumbs->parent('home');
                    $breadcrumbs->push('Brand', route($this->base . 'index'));
                });
        }
    }

    public function getDatatable()
    {
        $buttons = View::make($this->view_dir. '_partials/dt_buttons');

        $items = Brand::select($this->dt_columns['brand']);
        return Datatables::of($items)
                ->add_column('operations', '')
                ->edit_column('action_tmp', $buttons->__toString())
                ->make();
    }

    protected function display($view = null, $vars = array())
    {
        $this->render($view, $vars);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->footer_scripts[] = Helper::jsScript('', true, 'load');
        $this->current_breadcrumb = $this->base . 'index';
        $this->display($this->view_dir . 'index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        Breadcrumbs::register($this->base . 'create', function ($breadcrumbs) {
                $breadcrumbs->parent($this->base . 'index');
                $breadcrumbs->push('Create', route($this->base . 'create'));
            });

        $this->current_breadcrumb = $this->base . 'create';
        $this->display($this->view_dir . 'create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $item = new Brand;
        if ($item->save()) {
            Notification::info('Item successfully saved.');

            return Redirect::route($this->base . Input::get('save'), $item->proizvodjac_id);
        } else {
            Notification::error($item->errors()->all());
            return Redirect::route($this->base . 'create');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        Breadcrumbs::register($this->base . 'edit', function ($breadcrumbs) use ($id) {
                $breadcrumbs->parent($this->base . 'index');
                $breadcrumbs->push('Edit', route($this->base . 'edit', $id));
            });

        $vars['brand'] = Brand::find($id);
        $this->current_breadcrumb = $this->base . 'edit';
        $this->display($this->view_dir . 'edit', $vars);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $item = Brand::find($id);
        if ($item->save()) {
            Notification::info('Thank You!');
            return Redirect::route($this->base . 'edit', $id);
        } else {
            Notification::error($item->errors()->all());
            return Redirect::route($this->base . 'edit', $id);
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
        //
    }


}