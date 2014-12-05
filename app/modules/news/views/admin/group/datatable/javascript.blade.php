<script type="text/javascript">
    jQuery(document).ready(function(){
        // dynamic table
        jQuery('.{{ $class }}').dataTable({
            //"sPaginationType": "full_numbers",
            "bProcessing": false,
            fnDrawCallback: function (oSettings) {
                laravel.initialize();
            },
            aoColumns: [
                {sTitle: 'ID', sWidth: '70px'},
                {sTitle: 'Title'},
                {sTitle: 'News', bSortable: false, sWidth: '70px'},
                {sTitle: 'Action', bSortable: false, sWidth: '200px' }
            ],

            @foreach ($options as $k => $o)
        {{ json_encode($k) }}: {{ json_encode($o) }},
        @endforeach
            @foreach ($callbacks as $k => $o)
        {{ json_encode($k) }}: {{ $o }},
        @endforeach
    }).columnFilter({
            sPlaceHolder: 'head:after',
            aoColumns: [
                { type: "text"},
                { type: "text" },
                null,
                null
            ]
        });
    // custom values are available via $values array
    });
</script>