<script type="text/javascript">
    $(document).ready(function () {

        function fnCreateSelect(aData) {
            var select = $('<select></select>');

            select.append($("<option/>", {
                value: '',
                text: 'Select value'
            }));

            $.each(aData, function (key, value) {
                select.append($("<option/>", {
                    value: value,
                    text: value
                }));
            });
            return select;
        }

        function fnPopulateSelectAjax(i, model, filterElem, column, key) {
            var data = getModelList(model, column, key);
            var select = $('select', filterElem);
            if (data) {
                $.each(data, function (key, value) {
                    if (!select.find("option[value='" + key + "']").length > 0) {
                        select.append($("<option/>", {
                            value: key,
                            text: value
                        }));
                    }
                });

                var oSettings = dtTable.fnSettings();
                if (oSettings.aoPreSearchCols[i]['sSearch'] != '') {
                    select.val(oSettings.aoPreSearchCols[i]['sSearch']);
                }
//                    if(oSettings.oPreviousSearch['sSearch']!=''){
//                        $('.search_field').val(oSettings.oPreviousSearch['sSearch']);
//                    }

            }
        }

        var dtTable = $('.{{ $class }}').dataTable({
            //"sPaginationType": "full_numbers",
            // iCookieDuration: 60 * 60 * 24,
            bProcessing: false,
            fnDrawCallback: function (oSettings) {
                laravel.initialize();

                $('.footFilterRow th').each(function (i) {

                    if (i == 1) {
                        fnPopulateSelectAjax(i, $("[name='model[group]']").val(), '#filter' + i, 'title');
                    }
                });
            },

            aoColumns: [
                {sTitle: 'ID', sWidth: '70px'},
                {
                    sTitle: 'Group',
                    mRender: function (data, type, full) {
                        return full[4];
                    }
                },
                {sTitle: 'Title'},
                {sTitle: 'Action', bSortable: false, sWidth: '200px' },
                {sTitle: 'Test', bVisible: false}
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
            { type: "select" },
            { type: "text"},
            null,
            null
        ]
    });

    });

</script>