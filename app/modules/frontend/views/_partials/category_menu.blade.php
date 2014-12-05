<?php
$tree = new TreeApi(App\Modules\Product\Models\Category::all());
$tree->setTitle('group_name');
$tree->setItemClosure(function ($item, $thisObj) {
    $out = '<a href="' . route('product.category', array('slug' => Str::slug($item->group_name), 'id' => $item->group_id)) . '" target="_self">';
    $out .= $item->{$thisObj->getTitle()};
    $out .= '</a>';
    return $out;
});
$items = $tree->getTree();
?>

<div id="tree">
    {{ $items }}
</div>

@section('footerScripts')
@parent
<script type="text/javascript">
    $(document).ready(function () {

        $("#tree").fancytree({
            extensions: ["persist"],
            icons: false,
            persist: {
                expandLazy: true,
                overrideSource: false, // true: cookie takes precedence over `source` data attributes.
                store: "auto" // 'cookie', 'local': use localStore, 'session': sessionStore
            },
            click: function (event, data) {
                var node = data.node;
                if (node.data.href && data.targetType == 'title') {
                    window.open(node.data.href, node.data.target);
                }
            },
        });
    });
</script>
@stop

 