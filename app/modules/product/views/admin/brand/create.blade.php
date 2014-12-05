@section('footer_scripts')
<script type="text/javascript">
    CKEDITOR.disableAutoInline = true;
    $(window).load(function () {
        $('.ckeditor').ckeditor(); // Use CKEDITOR.replace() if element is <textarea>.
    });
</script>
@stop

<div class="page-content">
    <div class="page-header">
        <h1>
            Create Brand
        </h1>
    </div>
    <!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->


            <?php //echo HTML::ul($errors->all()); ?>
            <?php echo Form::open(array('route' => array('admin.page.store'), 'method' => 'post', 'class' => 'form-horizontal', 'role' => 'form')); ?>

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
                            <?php echo Form::label('title', 'Title', array('class' => 'col-sm-1 control-label no-padding-right')); ?>
                            <div class="col-sm-11">
                                <?php echo Form::text('title', null, array('class' => 'col-xs-10 col-sm-5')); ?>
                            </div>
                        </div>
                        <div class="space-4"></div>

                        <div class="form-group">
                            <?php echo Form::label('slug', 'Slug', array('class' => 'col-sm-1 control-label no-padding-right')); ?>
                            <div class="col-sm-11">
                                <?php echo Form::text('slug', null, array('class' => 'col-xs-10 col-sm-5')); ?>
                            </div>
                        </div>
                        <div class="space-4"></div>

                        <div class="form-group">
                            <?php echo Form::label('intro', 'Intro', array('class' => 'col-sm-1 control-label no-padding-right')); ?>
                            <div class="col-sm-11">
                                <?php echo Form::text('intro', null, array('class' => 'col-xs-10 col-sm-5')); ?>
                            </div>
                        </div>
                        <div class="space-4"></div>

                        <div class="form-group">
                            <?php echo Form::label('description', 'Description', array('class' => 'col-sm-1 control-label no-padding-right')); ?>

                            <div class="col-sm-11">
                                <?php echo Form::textarea('description', null, array('class' => 'col-xs-10 col-sm-5 ckeditor')); ?>
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
                            <?php echo Form::label('seo_title', 'Seo Title', array('class' => 'col-sm-1 control-label no-padding-right')); ?>
                            <div class="col-sm-11">
                                <?php echo Form::text('seo_title', null, array('class' => 'col-xs-10 col-sm-5')); ?>
                            </div>
                        </div>
                        <div class="space-4"></div>

                        <div class="form-group">
                            <?php echo Form::label('seo_keywords', 'Seo Keywords', array('class' => 'col-sm-1 control-label no-padding-right')); ?>
                            <div class="col-sm-11">
                                <?php echo Form::text('seo_keywords', null, array('class' => 'col-xs-10 col-sm-5')); ?>
                            </div>
                        </div>
                        <div class="space-4"></div>

                        <div class="form-group">
                            <?php echo Form::label('seo_description', 'Seo Description', array('class' => 'col-sm-1 control-label no-padding-right')); ?>
                            <div class="col-sm-11">
                                <?php echo Form::text('seo_description', null, array('class' => 'col-xs-10 col-sm-5')); ?>
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
                    <button class="btn btn-info" type="submit">
                        <i class="icon-ok bigger-110"></i>
                        Submit
                    </button>

                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset">
                        <i class="icon-undo bigger-110"></i>
                        Reset
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
