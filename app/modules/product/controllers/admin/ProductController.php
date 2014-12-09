<?php  namespace App\Modules\Product\Controllers\Admin;

use App\Modules\Admin\Controllers\AdminController;
use App\Modules\Product\Models\Category;
use App\Modules\Admin\Api\AdjacencyApi;
use App\Modules\Product\Models\Product;
use App\Modules\Admin\Api\ImageApi;
use App\Modules\Admin\Api\VideoApi;
use Menu;
use Helper;
use Request;
use Breadcrumbs;
use View;
use Input;
use Datatable;
use Msg;
use Redirect;
use Config;
use Paginator;
use Search\Search;
use Session;
use App\Modules\Admin\Models\Video;

class ProductController extends AdminController
{

    protected $viewDir = 'product::admin.product.';
    protected $base = 'admin.product.';
    public $data = array();
    protected $adjacencyApi;

    public $dtColumns = array(
            'product' => array('proizvod_id', 'proizvod', 'group_id', 'cena_prodajna')
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
                $breadcrumbs->push('Product', route($base . 'index'));
            });
        }
    }

    public function getDatatable()
    {
        $thisObj = $this;
        $group_id = Input::get('group_id');
        $buttons = $this->viewDir . 'datatable/dt_buttons';
        $query = Product::filter()->select(array_pusher($this->dtColumns['product'], 'show_item'));

        if (is_numeric($group_id)) $query->where('group_id', $group_id);
        return Datatable::query($query)
                ->showColumns($this->dtColumns['product'])
                ->addColumn('cena_prodajna', function ($model) use ($buttons) {
                    return nf($model->cena_prodajna);
                })
                ->addColumn('action', function ($model) use ($buttons, $thisObj) {
                    return View::make($buttons, array('model' => $model, 'thisObj' => $thisObj))->render();
                })
                ->searchColumns('proizvod')
                ->orderColumns($this->dtColumns['product'])
                ->make();
    }

    public function getTree($id = null, $root = false)
    {
        $adjacencyApi = $this->adjacencyApi;
        $adjacencyApi->setHref('index');
        $adjacencyApi->setHrefParameters(array('group_id', 'group_id'));
        $adjacencyApi->getTree($id, $root);
    }

    public function updateTree()
    {
        if (!$data = Input::get('data')) return;
        $this->adjacencyApi->tree($data);
        exit;
    }

    public function searchOptions()
    {
        $this->setOptions('options', false);
        $model = Input::get('model');
        $product = $model::find(Input::get('id'));

        return View::make('product::admin._partials.search_options', array(
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

        return View::make('product::admin._partials.search_template', array(
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
        $product = Product::find(Input::get('id'));
        $errors = array();

//        try {

        switch ($type) {

            case 'videos':

                $videoApi = new VideoApi();
                $videoApi->setModel($product);

                foreach (Input::get('items') as $key => $item) {
                    $data = array(
                            'url' => $item,
                            'status' => 1,
                            'title' => Input::get('titles.' . $key),
                    );
                    $videoApi->saveData($data);
                }

                break;

            case 'images':

                $images = array();
                $imageApi = new ImageApi();
                $imageApi->setModel($product);
                $imageApi->setConfig('product::product');

                foreach (Input::get('items') as $key => $item) {
                    $images[] = $item;
                }

                if ($images) {
                    $imageApi->remote($images);
                    $imageApi->process();
                }

                $errors = $imageApi->getErrors();

                break;
        }

        if($errors) Msg::danger($errors);
        return Msg::success('Items from search successfully added.')->instant();


//        } catch
//        (Exception $e) {
//            return Msg::danger('An error occurred during save data in database.')->showDangerInstant();
//        }

    }

    public function index()
    {
        $productConfig = Config::get('product::product');

        $groupId = Input::get('group_id');

        $category = Category::find($groupId);

        $categories = Category::orderBy('sort', 'asc')->get();

        $adjacencyApi = $this->adjacencyApi;
        // $adjacencyApi->updateSumOfProducts();

        $tree = $adjacencyApi->itemsToTree($categories);

        $col = array_merge($this->dtColumns['product'], array('action'));

        $table = Datatable::table()
                ->addColumn($col) // these are the column headings to be shown
                ->setUrl(route('admin.product.api.datatable')) // this is the route where data will be retrieved
                ->noScript()
                ->setOptions('bServerSide', true)
                ->setOptions('bStateSave', true)
                ->setOptions('bAutoWidth', false);

        if (is_numeric(Input::get('group_id'))) {
            $table->setCallbacks(
                    'fnServerParams', 'function ( aoData ) {
                                        aoData.push( { "name": "group_id", "value": "' . Input::get('group_id') . '" } );
                                    }'
            );
        }

        $vars['group_id'] = $groupId;
        $vars['table'] = $table;
        $vars['subTitle'] = $category ? $category->title ? : $category->group_name : 'All';

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
    public function edit($id = '6')
    {
        $base = $this->base;
        View::share('itemId', $id);

        Breadcrumbs::register($this->base . 'edit', function ($breadcrumbs) use ($id, $base) {
            $breadcrumbs->parent($base . 'index');
            $breadcrumbs->push('Edit', route($base . 'edit', $id));
        });

        if (!$product = Product::find($id)) {
            Msg::danger('Selected item does not exist or has been deleted');
            return $this->redirectBack(Redirect::route($this->base . 'index'));
        }

        $vars['product'] = $product;
        $vars['thisObj'] = $this;
        // $vars['searchResult'] = $searchResult;
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
        $product = Product::find($id);

        $product->update(Input::all());

        // Upload images
        $imageApi = new ImageApi();
        $imageApi->setModel($product);
        $imageApi->setConfig('product::product');
        $imageApi->setMetaData(Input::get('image'));
        $imageApi->upload('image.file');
        //$imageApi->setName('name');
        $imageApi->process();

        // Insert videos
        if ($insertVideos = Input::get('video.insert.url')) {
            $videoApi = new VideoApi();
            $videoApi->setModel($product);
            foreach ($insertVideos as $key => $insertVideo) {
                if (!$insertVideo) continue;
                $insertData['url'] = $insertVideo;
                $insertData['title'] = Input::get('video.insert.title.' . $key);
                $videoApi->saveData($insertData);
            }
        }

        // Update videos
        if ($updateVideos = Input::get('video.update.title')) {
            foreach ($updateVideos as $key => $title) {
                if ($video = Video::find($key)) {
                    $video['title'] = $title;
                    $video->save();
                }
            }
        }

        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $item = Product::find($id);
        $name = $item->proizvod;
        Product::destroy($id);
        Msg::success('Item "' . $name . '" successfully deleted.');

        return Redirect::back();
    }


}