<?php  namespace App\Modules\Page\Controllers;

use App\Modules\Frontend\Controllers\FrontendController;
use Illuminate\Support\Facades\Redirect;
use Menu;

class PageController extends FrontendController
{

    public function index()
    {
       echo 'index of front page yes';
    }


    protected function display($view = 'page::frontend.index', $vars = array())
    {
        $this->render($view, $vars);
    }

}