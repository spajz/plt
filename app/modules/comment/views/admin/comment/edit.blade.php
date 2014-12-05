<?php
$attributes_label = array('class' => 'col-sm-1 control-label no-padding-right');
$attributes_field = array('class' => 'col-xs-10 col-sm-5');
?>
@section('footer_scripts')
<script type="text/javascript">
    CKEDITOR.disableAutoInline = true;
    $(window).load(function () {
        // $('.ckeditor').ckeditor(); // Use CKEDITOR.replace() if element is <textarea>.
    });
</script>
@stop

<div class="page-content">
<div class="page-header">
    <h1>
        Edit News
        <small>
            <i class="icon-double-angle-right"></i>
            <?php echo $news->title; ?>
        </small>
    </h1>
</div>
<!-- /.page-header -->

<div class="row">

<div class="col-xs-12">

<?php //echo View::make('product::admin._partials.admin_options'); ?>

<!-- PAGE CONTENT BEGINS -->

<?php //echo HTML::ul($errors->all()); ?>


<?php echo Form::model($news, array('route' => array($base . 'update', $news->id), 'method' => 'put', 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'main-form', 'enctype' => 'multipart/form-data')); ?>
<?php //echo Form::hidden('_method', 'put') ?>
<small class="anchor-show"></small>
<div class="widget-box">
    <div class="widget-header">
        <h4 class="anchor-this" id="base-information">Base Information</h4>

        <div class="widget-toolbar">
            <a href="#" data-action="collapse">
                <i class="icon-chevron-up"></i>
            </a>

            <a href="#" data-action="close">
                <i class="icon-remove"></i>
            </a>
        </div>
    </div>
    <!-- eof widget-header -->

    <div class="widget-body">
        <div class="widget-main">

            <div class="form-group">
                <?php echo Form::label('id', 'ID', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('id', $news->id, $attributes_field + array('disabled', 'id' => 'id')); ?>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('title', 'Title', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('title', null, $attributes_field); ?>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('slug', 'Slug', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('slug', null, $attributes_field); ?>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('status', 'Status', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::hidden('status', 0); ?>
                    <?php echo Form::checkbox('status', 1, null, array('class' => 'ace ace-switch')); ?>
                    <span class="lbl"></span>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('description', 'Description', $attributes_label); ?>

                <div class="col-sm-11">
                    <?php echo Form::textarea('description', null, array('class' => $attributes_field['class'] . ' ckeditor')); ?>
                </div>
            </div>
            <div class="space-4"></div>

        </div>
    </div>
    <!-- eof widget-body -->
</div>
<!-- eof widget-box -->

<div class="space-8"></div>

<small class="anchor-show"></small>
<div class="widget-box">
    <div class="widget-header">
        <h4 class="anchor-this" id="seo-information">Seo Information</h4>

        <div class="widget-toolbar">
            <a href="#" data-action="collapse">
                <i class="icon-chevron-up"></i>
            </a>

            <a href="#" data-action="close">
                <i class="icon-remove"></i>
            </a>
        </div>
    </div>
    <!-- eof widget-header -->

    <div class="widget-body">
        <div class="widget-main">

            <div class="form-group">
                <?php echo Form::label('seo_title', 'Seo Title', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('seo_title', null, $attributes_field); ?>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('seo_keywords', 'Seo Keywords', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('seo_keywords', null, $attributes_field); ?>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('seo_description', 'Seo Description', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('seo_description', null, $attributes_field); ?>
                </div>
            </div>
            <div class="space-4"></div>

        </div>
    </div>
    <!-- eof widget-body -->
</div>
<!-- eof widget-box -->

<div class="space-8"></div>

<small class="anchor-show"></small>
<div class="widget-box">
    <div class="widget-header">
        <h4 class="anchor-this" id="images">Images</h4>

        <div class="widget-toolbar">
            <a href="#" data-action="collapse">
                <i class="icon-chevron-up"></i>
            </a>

            <a href="#" data-action="close">
                <i class="icon-remove"></i>
            </a>
        </div>
    </div>
    <!-- eof widget-header -->

    <div class="widget-body">
        <div class="widget-main">

            <h5>Current Images</h5>
            <table class="table table-striped table-bordered table-hover table-sortable">
                <thead>
                    <tr>
                        <th class="w120">Image</th>
                        <th>Alt</th>
                        <th class="w100">Status</th>
                        <th class="w100">Crop</th>
                        <th class="w100">Sort</th>
                        <th class="w100">Actions</th>
                    </tr>
                </thead>

                <tbody <?php echo create_dynamic_attributes('dynamic', $news, $news->id, 'admin::image.images'); ?>></tbody>
            </table>

            <h5>Upload Images</h5>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>File</th>
                        <th>Alt</th>
                        <th class="w100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo Form::file('image[file][]'); ?></td>
                        <td>
                            <?php echo Form::text('image[alt][]', null, $attributes_field); ?>
                        </td>
                        <td>
                            <a class="btn btn-xs btn-info clone-elem" data-elem="tr" href="#" title="Add row">
                                <i class="icon-plus bigger-110"></i>
                            </a>
                            <a class="btn btn-xs btn-info remove-elem" data-elem="tr" href="#" title="Remove row">
                                <i class="icon-minus bigger-110"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
    <!-- eof widget-body -->
</div>
<!-- eof widget-box -->


<div class="space-8"></div>

<small class="anchor-show"></small>
<div class="widget-box">
    <div class="widget-header">
        <h4 class="anchor-this" id="videos">Videos</h4>

        <div class="widget-toolbar">
            <a href="#" data-action="collapse">
                <i class="icon-chevron-up"></i>
            </a>

            <a href="#" data-action="close">
                <i class="icon-remove"></i>
            </a>
        </div>
    </div>
    <!-- eof widget-header -->

    <div class="widget-body">
        <div class="widget-main">

            <h5>Current Videos</h5>
            <table class="table table-striped table-bordered table-hover table-sortable">
                <thead>
                    <tr>
                        <th class="w120">Video</th>
                        <th>Url / Title</th>
                        <th class="w100">Status</th>
                        <th class="w100">Sort</th>
                        <th class="w100">Actions</th>
                    </tr>
                </thead>


                <tbody <?php echo create_dynamic_attributes('dynamic', $news, $news->id, 'admin::video.videos'); ?>></tbody>


            </table>

            <h5>Add Videos</h5>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Video</th>
                        <th>Title</th>
                        <th class="w100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?php echo Form::text('video[insert][url][]', null, $attributes_field); ?>
                        </td>
                        <td>
                            <?php echo Form::text('video[insert][title][]', null, $attributes_field); ?>
                        </td>
                        <td class="center">
                            <a class="btn btn-xs btn-info clone-elem" data-elem="tr" href="#" title="Add row">
                                <i class="icon-plus bigger-110"></i>
                            </a>
                            <a class="btn btn-xs btn-info remove-elem" data-elem="tr" href="#" title="Remove row">
                                <i class="icon-minus bigger-110"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>

            </table>

        </div>
    </div>
    <!-- eof widget-body -->
</div>
<!-- eof widget-box -->


<?php /*
<div class="space-8"></div>

<div class="widget-box">
    <div class="widget-header">
        <h4>Upload Images</h4>

        <div class="widget-toolbar">
            <a href="#" data-action="collapse">
                <i class="icon-chevron-up"></i>
            </a>

            <a href="#" data-action="close">
                <i class="icon-remove"></i>
            </a>
        </div>
    </div>
    <!-- eof widget-header -->

    <div class="widget-body">
        <div class="widget-main">

            <div class="dropzone-previews"></div>

        </div>
    </div>
    <!-- eof widget-body -->
</div>
<!-- eof widget-box -->
 */
?>
<a class="anchor-this" id="save" data-title="Save"></a>

<div class="clearfix form-actions">
    <div class="col-md-offset-3 col-md-9">
        <button class="btn" type="reset">
            <i class="icon-undo bigger-110"></i>
            Reset
        </button>
        &nbsp; &nbsp; &nbsp;
        <button class="btn btn-success" type="submit" name="save" value="edit">
            <i class="icon-save bigger-110"></i>
            Save
        </button>
        &nbsp; &nbsp; &nbsp;
        <button class="btn btn-success" type="submit" name="save" value="create">
            <i class="icon-plus bigger-110"></i>
            Save & New
        </button>
        &nbsp; &nbsp; &nbsp;
        <button class="btn btn-success" type="submit" name="save" value="index">
            <i class="icon-external-link bigger-110"></i>
            Save & Exit
        </button>

    </div>
</div>

<?php echo Form::close(); ?>

<div class="form-search-box">
    <?php echo View::make('admin::search.search_options', array('item' => $news)); ?>
</div>

<small class="anchor-show"></small>
<div class="widget-box">
    <div class="widget-header">
        <h4 class="anchor-this" id="search">Search</h4>

        <div class="widget-toolbar">
            <a href="#" data-action="collapse">
                <i class="icon-chevron-up"></i>
            </a>

            <a href="#" data-action="close">
                <i class="icon-remove"></i>
            </a>
        </div>
        <div class="widget-toolbar">
            <a href="#" class="check-all" data-elem=".search-item-box" title="Check all">
                <i class="icon-check"></i>
            </a>

            <a href="#" class="uncheck-all" data-elem=".search-item-box" title="Uncheck all">
                <i class="icon-check-empty"></i>
            </a>

            <a href="#" class="check-invert" data-elem=".search-item-box" title="Check invert">
                <i class="icon-check-sign"></i>
            </a>
        </div>
    </div>
    <!-- eof widget-header -->

    <div class="widget-body">
        <div class="widget-main">

            <div class="search-results"></div>

        </div>
    </div>
    <!-- eof widget-body -->
</div>
<!-- eof widget-box -->


<!-- PAGE CONTENT ENDS -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div><!-- /.page-content -->
