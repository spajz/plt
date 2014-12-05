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
        Create News
        <small>
            <i class="icon-double-angle-right"></i>
        </small>
    </h1>
</div>
<!-- /.page-header -->

<div class="row">

<div class="col-xs-12">

<?php //echo View::make('product::admin._partials.admin_options'); ?>

<!-- PAGE CONTENT BEGINS -->

<?php //echo HTML::ul($errors->all()); ?>

<?php echo Form::open(array('route' => array($base . 'store'), 'method' => 'post', 'class' => 'form-horizontal main-form', 'role' => 'form', 'id' => 'main-form', 'enctype' => 'multipart/form-data')); ?>

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
                <?php echo Form::label('title', 'Title', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('title', Input::old('title'), $attributes_field); ?>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('slug', 'Slug', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('slug', Input::old('slug'), $attributes_field); ?>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('news_group_id', 'Group', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::select('news_group_id', $newsGroup->modelList('title') , Input::old('news_group_id'), $attributes_field); ?>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('status', 'Status', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::hidden('status', 0); ?>
                    <?php echo Form::checkbox('status', 1, Input::old('status'), array('class' => 'ace ace-switch')); ?>
                    <span class="lbl"></span>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('description', 'Description', $attributes_label); ?>

                <div class="col-sm-11">
                    <?php echo Form::textarea('description', Input::old('description'), array('class' => $attributes_field['class'] . ' ckeditor')); ?>
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
                    <?php echo Form::text('seo_title', Input::old('seo_title'), $attributes_field); ?>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('seo_keywords', 'Seo Keywords', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('seo_keywords', Input::old('seo_keywords'), $attributes_field); ?>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('seo_description', 'Seo Description', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('seo_description', Input::old('seo_description'), $attributes_field); ?>
                </div>
            </div>
            <div class="space-4"></div>

        </div>
    </div>
    <!-- eof widget-body -->
</div>
<!-- eof widget-box -->

<div class="space-8"></div>

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



<!-- PAGE CONTENT ENDS -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div><!-- /.page-content -->
