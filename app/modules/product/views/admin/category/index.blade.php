@section('footer_scripts')
{{ $table->script('product::admin.category.datatable.javascript') }}
@include('product::admin.category._partials.tree')
@stop

<div class="page-content">
    <div class="page-header">
        <h1>
            Category
        </h1>
    </div>
    <!-- /.page-header -->

    <div class="row">

        <!-- PAGE CONTENT BEGINS -->

        <div class="col-xs-2">
            <a href="#" class="btnCollapseAll" title="Collapse all"><i class="icon-folder-close-alt"></i></a>&nbsp;
            <a href="#" class="btnExpandAll" title="Expand all"><i class="icon-folder-open-alt"></i></a>

            <div id="tree"></div>

            <a href="#" class="btnCollapseAll" title="Collapse all"><i class="icon-folder-close-alt"></i></a>&nbsp;
            <a href="#" class="btnExpandAll" title="Expand all"><i class="icon-folder-open-alt"></i></a>
        </div>

        <div class="col-xs-10">

            <?php echo View::make('product::admin._partials.admin_options'); ?>

            <div class="table-header">
                Category List
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
