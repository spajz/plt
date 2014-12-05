<?php namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Controllers\AdminController;
use Menu;
use Helper;
use Route;
use Breadcrumbs;

class DashboardController extends AdminController
{
    public function getIndex()
    {
        $this->display();
    }

    protected function display($view = 'admin::dashboard.index', $vars = array())
    {
        $vars['breadcrumbs'] = Breadcrumbs::render('home');

        $this->render($view, $vars);
    }

}