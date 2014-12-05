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

class NewsGroupController extends AdminController
{

    protected $viewDir = 'news::admin.group.';
    protected $base = 'admin.news-group.';

    public $dtColumns = array(
            'group' => array('id', 'title')
    );

    public function __construct()
    {
        parent:: __construct();

        View::share('base', $this->base);
        $base = $this->base;

        if (!Request::ajax()) {
            Breadcrumbs::register($base . 'index', function ($breadcrumbs) use ($base) {
                $breadcrumbs->parent('home');
                $breadcrumbs->push('News Group', route($base . 'index'));
            });
        }
    }

    public function getDatatable()
    {
        $thisObj = $this;
        $buttons = $this->viewDir . 'datatable/dt_buttons';

        return Datatable::query(NewsGroup::filter()->select(array_pusher($this->dtColumns['group'], 'status')))
                ->showColumns($this->dtColumns['group'])
                ->addColumn('news', function ($model) {
                    return $model->news->count();
                })
                ->addColumn('action', function ($model) use ($buttons, $thisObj) {
                    return View::make($buttons, array('model' => $model, 'thisObj' => $thisObj))->render();
                })
                ->searchColumns(array('id', 'title'))
                ->orderColumns($this->dtColumns['group'])
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
        $col = array_merge($this->dtColumns['group'], array('news', 'action'));

        $table = Datatable::table()
                ->addColumn($col) // these are the column headings to be shown
                ->setUrl(route('admin.newsGroup.api.datatable')) // this is the route where data will be retrieved
                ->noScript()
                ->setOptions('bServerSide', true)
                ->setOptions('bStateSave', true)
                ->setOptions('bAutoWidth', false);

        $vars['table'] = $table;
        $vars['subTitle'] = 'All';

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

        $vars['group'] = NewsGroup::find($id);
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
        $group = NewsGroup::find($id);
        $group->update(Input::all());

        // Upload images
        $imageApi = new ImageApi();
        $imageApi->setModel($group);
        $imageApi->setConfig('news::group');
        $imageApi->setMetaData(Input::get('image'));
        $imageApi->upload('image.file');
        $imageApi->process();

        // Insert videos
        if ($insertVideos = Input::get('video.insert.url')) {
            $videoApi = new VideoApi();
            $videoApi->setModel($group);
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

        return $this->redirect($this->base, $group->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $item = NewsGroup::find($id);

        NewsGroup::destroy($id);

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
        $group = NewsGroup::create(Input::all());

        Msg::success('Item "' . Input::get('title') . '" successfully created.');

        return $this->redirect($this->base, $group->id);
    }

}