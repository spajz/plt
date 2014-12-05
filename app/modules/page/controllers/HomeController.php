<?php  namespace App\Modules\Page\Controllers;

use App\Modules\Frontend\Controllers\FrontendController;
use App\Modules\Page\Models\Page;
use App\Modules\Page\Models\Content;
use App\Modules\Page\Models\Blog;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Category;
use Menu;
use View;
use ItemsHelper;
use TreeApi;

class HomeController extends FrontendController
{

    public function index()
    {
        $vars['products'] = Product::select('*')->orderBy('proizvod_id')->limit(5)->get();

        $vars['specials'] = Product::select('*')->orderBy('proizvod_id')->limit(5)->get();

        $this->display('page::frontend.home', $vars);
    }

    protected function display($view = 'page::frontend.home', $vars = array())
    {
        $this->render($view, $vars);
    }

}