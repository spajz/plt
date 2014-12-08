<?php
$attributes_label = array('class' => 'col-sm-1 control-label no-padding-right');
$attributes_field = array('class' => 'col-xs-10 col-sm-5');
?>
@section('footer_scripts')
@parent
@include('product::admin.category._partials.tree')
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
        Edit Category
        <small>
            <i class="icon-double-angle-right"></i>
            <?php echo $category->title ? : $category->group_name ; ?>
        </small>
    </h1>
</div>
<!-- /.page-header -->

<div class="row">

<div class="col-xs-2">
    <a href="#" class="btnCollapseAll" title="Collapse all"><i class="icon-folder-close-alt"></i></a>&nbsp;
    <a href="#" class="btnExpandAll" title="Expand all"><i class="icon-folder-open-alt"></i></a>
    <a href="#" class="btnRefreshTree" title="Refresh tree"><i class="icon-refresh"></i></a>

    <div id="tree"></div>

    <a href="#" class="btnCollapseAll" title="Collapse all"><i class="icon-folder-close-alt"></i></a>&nbsp;
    <a href="#" class="btnExpandAll" title="Expand all"><i class="icon-folder-open-alt"></i></a>
    <a href="#" class="btnRefreshTree" title="Refresh tree"><i class="icon-refresh"></i></a>
</div>

<div class="col-xs-10">

<?php  echo View::make('product::admin._partials.admin_options'); ?>

<!-- PAGE CONTENT BEGINS -->

<div id="pjax-container">
<?php //echo HTML::ul($errors->all()); ?>
<?php echo Form::model($category, array('route' => array($base . 'update', $category->group_id), 'method' => 'put',
    'class' => 'form-horizontal', 'role' => 'form', 'data-pjax' => 1)); ?>

<div class="widget-box">
    <div class="widget-header">
        <h4>Base Information</h4>

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
                <?php echo Form::label('group_name', 'Name', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('group_name', null, $attributes_field); ?>
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
                <?php echo Form::label('show_menu', 'Show In Menu', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::hidden('show_menu', 0); ?>
                    <?php echo Form::checkbox('show_menu', 1, null, array('class' => 'ace ace-switch ace-switch-2')); ?>
                    <span class="lbl"></span>
                </div>
            </div>
            <div class="space-4"></div>


            <div class="form-group">
                <?php echo Form::label('intro', 'Intro', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::textarea('intro', null, array('rows' => 3) + $attributes_field); ?>
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

<div class="widget-box">
    <div class="widget-header">
        <h4>Attributes</h4>

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

        <table class="table table-striped table-bordered table-hover table-sortable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Attribute</th>
                    <th>Filter</th>
                    <th>Status</th>
                    <th>Sort</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody <?php echo create_dynamic_attributes('dynamic', $category, $category->group_id, 'product::admin.category._partials.attributes'); ?>>

            </tbody>
        </table>

         <div class="form-group">
                <?php echo Form::label('attribute', 'Add Atribute', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('attribute', null, $attributes_field); ?>
                </div>
        </div>
         <div class="form-group">
            <?php echo Form::label('filter', 'Filter Attribute', $attributes_label); ?>
            <div class="col-sm-11">
                <?php echo Form::hidden('filter', 0); ?>
                <?php echo Form::checkbox('filter', 1, null, array('class' => 'ace ace-switch ace-switch-2')); ?>
                <span class="lbl"></span>
            </div>
        </div>

        </div>
    </div>
    <!-- eof widget-body -->
</div>
<!-- eof widget-box -->

<div class="space-8"></div>

<div class="widget-box">
    <div class="widget-header">
        <h4>Seo Information</h4>

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
</div>

<!-- PAGE CONTENT ENDS -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div><!-- /.page-content -->

@section('footer_scripts')
@parent
<script type="text/javascript">

$(document).ready(function () {

    $.pjax.defaults.scrollTo = false;

    $(document).on('pjax:send', function () {
        ajaxLoaderShow();
        //$("#tree").fancytree("destroy");
    });

    $(document).on('pjax:complete', function () {
        ajaxLoaderHide();
        //initTree();
        if (('.dynamic').length) {
            processDynamic();
        }
    });

    $(document).on('submit', 'form[data-pjax]', function(event) {
        $.pjax.submit(event, {container: '#pjax-container', timeout: 5000});
    })

});
</script>
@stop
