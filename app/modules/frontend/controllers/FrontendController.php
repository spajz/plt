<?php namespace App\Modules\Frontend\Controllers;

use BaseController;
use Config;
use Menu;
use View;
use App;
use Session;
use Redirect;
use Notification;
use Sentry;
use Breadcrumbs;
use App\Modules\Product\Models\Product;
use Illuminate\Support\MessageBag;

class FrontendController extends BaseController
{
    protected $layout = 'frontend::layouts.master';
    protected $headScripts = array();
    protected $footerScripts = array();
    protected $bassetCollection = array();
    protected $currentBreadcrumb = 'home';
    protected $user = false;
    public $kurs = 115;
    public $messages;

    public function __construct()
    {
        define('SITE', 'frontend');
        // $product = Product::find(3132);
        // $this->kurs = $product->cena_prodajna;
        // $this->messages = new MessageBag();
    }

    protected function render($view, $vars = array())
    {
        $newProducts = Product::where('show_item', 1)->orderBy('proizvod_id', 'desc')->take(16)->get();

        // Views
        // $this->layout->messages = $this->messages;
        $this->layout->headScripts = implode('', $this->headScripts);
        $this->layout->footerScripts = implode('', $this->footerScripts);
        $this->layout->cart = View::make('frontend::_partials.cart');
        $this->layout->header = View::make('frontend::_partials.header');
        $this->layout->bannerHot = View::make('frontend::_partials.banner_hot')->with('products', $newProducts);
        $this->layout->bannerNewsletter = View::make('frontend::_partials.banner_newsletter');
        $this->layout->bannerSocial = View::make('frontend::_partials.banner_social');
        $this->layout->footer = View::make('frontend::_partials.footer');

        $this->layout->content = View::make($view, $vars);
    }

}