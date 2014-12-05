@section('footer_scripts')
<script type="text/javascript">
    $(window).load(function () {
        var oTable = $(".dt-table-page").dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "{{URL::route('admin.brand.api.datatable')}}",
            "bStateSave": true
        });
    });
</script>
@stop

<div class="page-content">
<div class="page-header">
    <h1>
        Tables One
        <small>
            <i class="icon-double-angle-right"></i>
            Static &amp; Dynamic Tables
        </small>
    </h1>
</div>
<!-- /.page-header -->

<div class="row">
<div class="col-xs-12">
<h3 class="header smaller lighter blue">jQuery dataTables</h3>
<!-- PAGE CONTENT BEGINS -->
<div class="row">
    <div class="col-xs-2">
        MENU
    </div>
    <div class="col-xs-10">

        <div class="table-header">
            Results for "Latest Registered Domains"
        </div>

        <div class="table-responsive">

            {{$dt_table}}

        </div>
    </div>
</div>

<div class="hr hr-18 dotted hr-double"></div>

<!-- PAGE CONTENT ENDS -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div><!-- /.page-content -->

 @section('sidebar')
            This is the master sidebar.
        @show