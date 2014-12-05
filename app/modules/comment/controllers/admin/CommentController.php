<?php  namespace App\Modules\Comment\Controllers\Admin;

use App\Modules\Admin\Controllers\AdminController;
use App\Modules\Product\Models\Category;
use App\Modules\Admin\Api\ImageApi;
use App\Modules\Admin\Api\VideoApi;
use Menu;
use Request;
use Breadcrumbs;
use View;
use Input;
use Datatable;
use Msg;
use Redirect;
use App\Modules\Admin\Models\Video;
use App\Modules\Comment\Models\Comment;

class CommentController extends AdminController
{
    protected $viewDir = 'comment::admin.comment.';
    protected $base = 'admin.comment.';

    public $dtColumns = array(
            'comment' => array('id', 'model_type', 'comment', 'created_at')
    );

    public function __construct()
    {
        parent:: __construct();

        View::share('base', $this->base);
        $base = $this->base;

        if (!Request::ajax()) {
            Breadcrumbs::register($base . 'index', function ($breadcrumbs) use ($base) {
                $breadcrumbs->parent('home');
                $breadcrumbs->push('Comment', route($base . 'index'));
            });
        }
    }

    public function getDatatable()
    {
        $thisObj = $this;
        $buttons = $this->viewDir . 'datatable/dt_buttons';

        return Datatable::query(Comment::filter()->select($this->dtColumns['comment']))
                ->showColumns($this->dtColumns['comment'])
                ->addColumn('action', function ($model) use ($buttons, $thisObj) {
                    return View::make($buttons, array('model' => $model, 'thisObj' => $thisObj))->render();
                })
                ->addColumn('model_type', function ($model) {
                    $array = explode('\\', $model->model_type);
                    return end($array);
                })
                ->searchColumns(array('comment'))
                ->orderColumns($this->dtColumns['comment'])
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
        $col = array_merge($this->dtColumns['comment'], array('action'));

        $table = Datatable::table()
                ->addColumn($col) // these are the column headings to be shown
                ->setUrl(route('admin.comment.api.datatable')) // this is the route where data will be retrieved
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

        $vars['news'] = Comment::find($id);
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
        $news = Comment::find($id);
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
        $item = Category::find($id);
        if (count($item->products))
            Msg::danger('This group has a product(s). You can not delete it. First delete all products from the group.');

        if (count($item->children($id)->get()))
            Msg::danger('This group has a subgroup(s). You can not delete it. First delete all subgroups.');

        if (!Msg::has('danger')) {
            Category::destroy($id);
            Msg::success('Item "' . $item->title . '" successfully deleted.');
        }

        return Redirect::back();
    }


}