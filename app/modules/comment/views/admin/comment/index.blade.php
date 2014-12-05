@section('footer_scripts')
{{ $table->script('comment::admin.comment.datatable.javascript') }}
@stop

<div class="page-content">
    <div class="page-header">
        <h1>
            Comment
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

                    <div class="table-header">
                        Comment List
                    </div>

                    <div class="table-responsive">

                        {{ $table->render() }}

                    </div>
                </div>

            <div class="hr hr-18 dotted hr-double"></div>

            <!-- PAGE CONTENT ENDS -->

        <!-- /.col -->
    </div>
    <!-- /.row -->
</div><!-- /.page-content -->
