<?php  namespace App\Modules\News\Controllers\Admin;

use App\Modules\Admin\Controllers\AdminController;
use App\Modules\Admin\Api\ImageApi;
use App\Modules\Admin\Api\VideoApi;
use Menu;
use Msg;
use View;
use Request;
use Breadcrumbs;
use Datatable;
use App\Modules\News\Models\News;
use App\Modules\News\Models\NewsGroup;
use Input;
use Redirect;
use Form;

class NewsController extends AdminController
{
    protected $viewDir = 'news::admin.news.';
    protected $base = 'admin.news.';

    public $dtColumns = array(
            'news' => array('id', 'news_group_id', 'title')
    );

    public function __construct()
    {
        parent:: __construct();

        View::share('base', $this->base);
        $base = $this->base;

        if (!Request::ajax()) {
            Breadcrumbs::register($base . 'index', function ($breadcrumbs) use ($base) {
                $breadcrumbs->parent('home');
                $breadcrumbs->push('News', route($base . 'index'));
            });
        }
    }

    public function getDatatable()
    {
        $thisObj = $this;
        $buttons = $this->viewDir . 'datatable/dt_buttons';
        return Datatable::query(News::filter()->select(array_pusher($this->dtColumns['news'], 'status')))
                ->showColumns($this->dtColumns['news'])

                ->addColumn('action', function ($model) use ($buttons, $thisObj) {
                    return View::make($buttons, array('model' => $model, 'thisObj' => $thisObj))->render();
                })
                ->addColumn('group', function ($model) {
                    return count($model->group) ? $model->group->title : null;
                })
                ->searchColumns(array('id', 'news_group_id', 'title'))
                ->orderColumns($this->dtColumns['news'])
                ->setSearchOperator("LIKE")
                ->make();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $col = array_merge($this->dtColumns['news'], array('action'));

        $table = Datatable::table()
                ->addColumn($col) // these are the column headings to be shown
                ->setUrl(route('admin.news.api.datatable')) // this is the route where data will be retrieved
                ->noScript()
                ->setOptions('bServerSide', true)
                ->setOptions('bStateSave', true)
                ->setOptions('bAutoWidth', false);

        $vars['table'] = $table;
        $vars['subTitle'] = 'All';
        $vars['model'] = new News();

        $this->currentBreadcrumb = $this->base . 'index';
        $this->display($this->viewDir . 'index', $vars);
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

        $vars['news'] = News::find($id);
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
        $news = News::find($id);
        $news->update(Input::all());

        // Upload images
        $imageApi = new ImageApi();
        $imageApi->setModel($news);
        $imageApi->setConfig('news::news');
        $imageApi->setMetaData(Input::get('image'));
        $imageApi->upload('image.file');
        $imageApi->process();

        // Insert videos
        if ($insertVideos = Input::get('video.insert.url')) {
            $videoApi = new VideoApi();
            $videoApi->setModel($news);
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

        return $this->redirect($this->base, $news->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $item = News::find($id);

        News::destroy($id);

        Msg::success('Item "' . $item->title . '" successfully deleted.');

        return Redirect::back();
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

        $vars['newsGroup'] = new NewsGroup();
        $this->currentBreadcrumb = $this->base . 'create';
        $this->display($this->viewDir . 'create', $vars);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $news = News::create(Input::all());

        Msg::success('Item "' . Input::get('title') . '" successfully created.');

        return $this->redirect($this->base, $news->id);
    }


}