<?php  namespace App\Modules\Product\Controllers\Admin;

use App\Modules\Admin\Controllers\AdminController;
use App\Modules\Page\Models\Page;
use App\Modules\Page\Models\Content;
use App\Modules\Page\Models\Blog;
use Menu;
use Helper;
use Datatables;
use Request;
use URL;
use Breadcrumbs;
use View;

class GroupController extends AdminController
{

    public function index()
    {
        echo 'group contr';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        Breadcrumbs::register('page.create', function ($breadcrumbs) {
            $breadcrumbs->parent('page.index');
            $breadcrumbs->push('Create Page', route('admin.page.create'));
        });

        //$vars['breadcrumbs'] = Breadcrumbs::render('page.create');
        $this->current_breadcrumb = 'page.create';
        $this->display('page::page.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
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

    protected function display($view = 'page::page.index', $vars = array())
    {
        $this->render($view, $vars);
    }

}