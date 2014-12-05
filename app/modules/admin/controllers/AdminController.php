<?php namespace App\Modules\Admin\Controllers;

use BaseController;
use Config;
use Menu;
use View;
use Session;
use Redirect;
use Notification;
use Sentry;
use Breadcrumbs;
use Messages;
use Input;
use Msg;
use App\Modules\Admin\Api\ImageApi;
use App\Modules\Admin\Api\VideoApi;
use Search\Search;
use Paginator;


class AdminController extends BaseController
{
    protected $layout = 'admin::layouts.master';
    protected $headScripts = array();
    protected $footerScripts = array();
    protected $bassetCollection = array();
    protected $currentBreadcrumb = 'home';
    protected $user = false;
    public $dtTemplate = 'admin::datatable.template';
    public $dtJsTemplate = 'admin::datatable.javascript';
    public $dtClass = 'dt';

    public $messages;
    public $kurs;


    public function __construct()
    {
        define('SITE', 'admin');

        try {
            // Get the current active/logged in user
            $this->user = Sentry::getUser();

        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            // User wasn't found, should only happen if the user was deleted
            // when they were already logged in or had a "remember me" cookie set
            // and they were deleted.
            die("User wasn't found");
        }

        $this->kurs = 115;

        // Breadcrumbs
        Breadcrumbs::setView('admin::_partials.bootstrap3');
        Breadcrumbs::register('home', function ($breadcrumbs) {
            $breadcrumbs->push('Home', route('admin.dashboard'));
        });
        $this->initOptions();
    }


    protected function display($view = null, $vars = array())
    {
        $this->render($view, $vars);
    }

    protected function render($view = 'admin.dashboard', $vars = array())
    {
        View::share('dtTemplate', $this->dtTemplate);
        View::share('dtJsTemplate', $this->dtJsTemplate);

        // Add current sentry user to global var user
        View::share('user', $this->user);
        View::share('kurs', $this->kurs);
        View::share('messages', $this->messages);
        View::share('dtTemplate', $this->dtTemplate);
        // Views
        $this->layout->headScripts = implode('', $this->headScripts);
        $this->layout->footerScripts = implode('', $this->footerScripts);
        $this->layout->navbar = View::make('admin::_partials.navbar');
        // $this->layout->sidebar = View::make('admin::_partials.sidebar')->with('navigation', Menu::get('navigation'));
        $this->layout->breadcrumbs = View::make('admin::_partials.breadcrumbs')->with('current_breadcrumb', $this->currentBreadcrumb);
        $this->layout->settings = View::make('admin::_partials.settings');
        $this->layout->sidebar = View::make('admin::_partials.sidebar');
        $this->layout->content = View::make($view, $vars)->with('footer', View::make('admin::_partials.footer'));
    }

    public function changeLanguage($lang = false)
    {
        cmsLanguage($lang);
        $languages = Config::get('admin::admin_languages');
        Notification::info(__('admin::admin.current_language_msg', null, array('lang' => $languages[Session::get('admin_language')])));
        return Redirect::back();
    }

    public function initOptions()
    {
        $options = array(
                'category' => array(
                        'status' => -1,
                        'count_products' => 1,
                        'dnd' => 0,
                ),
                'product' => array(
                        'show_item' => 0
                ),
                'search' => array(
                        'engine' => Config::get('search.options.default.engine'),
                        'type' => Config::get('search.options.default.type'),
                ),
        );

        $session = Session::get('options', array());
        $options = array_replace_recursive($options, $session);

        Session::set('options', $options);
    }

    public function setOptions($type = 'options', $redirect = true)
    {
        $options = array();

        if (is_array($type)) {
            $options = $type;
        } elseif (is_string($type) && Input::get($type)) {
            $options = Input::get($type);
        }

        $session = Session::get($type, array());
        $options = array_replace_recursive($session, $options);
        Session::set($type, $options);

        if ($redirect)
            return Redirect::back();
    }

    // Change status of item
    public function toggleStatus($model, $id)
    {
        $model = urldecode2($model);

        $item = new $model();

        $newStatus = $item->toggleStatus($id);

        return $this->getStatusButton($model, $id, $newStatus);
    }

    // Return status button html
    public function getStatusButton($model, $id, $status)
    {
        if (is_object($model)) {
            $model = get_class($model);
        }

        return View::make('admin::_partials.status_button', array(
                'status' => $status,
                'model' => $model,
                'id' => $id,
        ))->render();
    }

    // Sort model
    public function sortModel()
    {
        $model = urldecode2(Input::get('model'));
        $modelObj = new $model();
        $modelObj->sortModel(Input::get('sort'));

        exit;
    }

    // Delete any item from model
    public function destroyModel($model, $id)
    {
        $model = urldecode2($model);
        if ($item = $model::find($id)) {
            $item->delete();
            Msg::success('Item with ID "' . $id . '" successfully deleted.');
        } else {
            Msg::danger('Item with ID "' . $id . '" does not exist or has been deleted.');
        }

        return Redirect::back();
    }

    public function getModelById($model, $id, $view = null)
    {
        $model = urldecode2($model);
        $item = $model::find($id);
        if (is_null($view))
            return $item;

        $view = urldecode2($view);
        return View::make($view, array(
                'item' => $item,
                'thisObj' => $this,
        ))->render();
    }

    public function getModelList($model, $column, $key = null)
    {
        $model = urldecode2($model);
        $model = new $model;
        $out = $model->modelList($column, $key);
        return json_encode($out);
    }

    public function createMsg()
    {
        $type = Input::get('type', 'danger');
        $msg = Input::get('msg');
        $showMethod = 'show' . ucfirst($type) . 'Instant';
        return Msg::$type($msg)->$showMethod();
    }

    public function cropImage()
    {
        $imageApi = new ImageApi();

        $imageApi->cropImage(Input::get('config'));
    }

    public function searchOptions()
    {
        $this->setOptions('options', false);
        $model = Input::get('model');
        $product = $model::find(Input::get('id'));

        return View::make('admin::search.search_options', array(
                'item' => $product,
                'searchTerm' => Input::get('options.search.term'),
        ))->render();
    }

    public function search()
    {
        $search = new Search(Session::get('options.search.engine'));

        $type = strtolower(Session::get('options.search.type'));

        $search->setImageFilters(Session::get('options.search', array()));

        if ($type == 'videos') {
            $search->setSiteFilter(Session::get('options.search.videoSites'));
        }

        $num = $search->getNumberOfItems();

        $start = 1;
        $currentPage = Input::get('page', 1);
        if ($currentPage > 0) {
            $start = $currentPage * $num - $num + 1;
        }

        $term = urldecode2(Input::get('term'));
        $method = 'search' . ucfirst($type);
        $result = $search->$method($term, $start);

        if ($result == false) {
            return Msg::danger(array_merge(array('An error occurred. '), $search->getErrors()))->showDangerInstant();
        }

        $paginator = Paginator::make($result, $search->getTotalResults(), $num);
        $paginatorLinks = $paginator->links();

        return View::make('admin::search.search_template', array(
                'type' => $type,
                'result' => $result,
                'paginatorLinks' => $paginatorLinks,
                'term' => $term,
        ))->render();
    }

    public function addItemsFromSearch()
    {
        if (!Input::get('items')) {
            return Msg::info('Nothing selected.')->showInfoInstant();
        }
        if (!Input::get('id') || !Input::get('type')) {
            return Msg::danger('Required parameter is missing.')->showDangerInstant();
        }

        $type = Input::get('type');
        $model = Input::get('model');
        $item = $model::find(Input::get('id'));
        $errors = array();

        //        try {

        switch ($type) {

            case 'videos':

                $videoApi = new VideoApi();
                $videoApi->setModel($item);

                foreach (Input::get('items') as $key => $url) {
                    $data = array(
                            'url' => $url,
                            'status' => 1,
                            'title' => Input::get('titles.' . $key),
                    );
                    $videoApi->saveData($data);
                }

                break;

            case 'images':

                $images = array();
                $imageApi = new ImageApi();
                $imageApi->setModel($item);
                $imageApi->setConfig($item->getModulConfig());

                foreach (Input::get('items') as $key => $image) {
                    $images[] = $image;
                }

                if ($images) {
                    $imageApi->remote($images);
                    $imageApi->process();
                }

                $errors = $imageApi->getErrors();

                break;
        }

        if ($errors) Msg::danger($errors);
        return Msg::success('Items from search successfully added.')->instant();


        //        } catch
        //        (Exception $e) {
        //            return Msg::danger('An error occurred during save data in database.')->showDangerInstant();
        //        }

    }

    public function redirect($base, $itemId = null)
    {
        switch (Input::get('save')) {
            case 'index':
                return Redirect::route($base . 'index');
                break;

            case 'edit':
                return Redirect::route($base . 'edit', $itemId);
                break;

            case 'create':
                return Redirect::route($base . 'create');
                break;

            default:
                return Redirect::back();
        }
    }

}