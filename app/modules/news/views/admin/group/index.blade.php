@section('footer_scripts')
{{ $table->script('news::admin.group.datatable.javascript') }}
@stop

<div class="page-content">
    <div class="page-header">
        <h1>
            News Group
            <small>
                <i class="icon-double-angle-right"></i>
                <?php echo $subTitle; ?>
            </small>
        </h1>
    </div>
    <!-- /.page-header -->

    <div class="row">

            <!-- PAGE CONTENT BEGINS -->

                <div class="col-xs-12">

                    <?php // echo View::make('product::admin._partials.admin_options'); ?>

                    <div class="table-header">
                        News Group List
                    </div>

                    <div class="table-responsive">

                        {{ $table->render($dtTemplate) }}

                    </div>
                </div>


            <div class="hr hr-18 dotted hr-double"></div>

            <!-- PAGE CONTENT ENDS -->

        <!-- /.col -->
    </div>
    <!-- /.row -->
</div><!-- /.page-content -->
