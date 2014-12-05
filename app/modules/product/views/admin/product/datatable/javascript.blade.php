<script type="text/javascript">
    jQuery(document).ready(function(){
        // dynamic table
        jQuery('#{{ $id }}').dataTable({
            fnDrawCallback: function (oSettings) {
                laravel.initialize();
            },
//            aoColumns: [
//                   {sTitle: 'ID', sWidth: '70px'},
//                   {sTitle: 'Name'},
//                   {sTitle: 'Group ID', sWidth: '120px'},
//                   {sTitle: 'Cena', sWidth: '120px'},
//                   {sTitle: 'Action', bSortable: false, sWidth: '200px' }
//               ],

            @foreach ($options as $k => $o)
                {{ json_encode($k) }}: {{ json_encode($o) }},
            @endforeach

            @foreach ($callbacks as $k => $o)
                {{ json_encode($k) }}: {{ $o }},
            @endforeach
        });
    // custom values are available via $values array
    });
</script>