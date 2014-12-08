<script type="text/javascript">
    var tree = $("#tree");

    function initTree(){
        // Attach the fancytree widget to an existing <div id="tree"> element
        // and pass the tree options as an argument to the fancytree() function:
        $("#tree").fancytree({
            extensions: ["dnd", "persist"],
            persist: {
                expandLazy: true,
                overrideSource: false, // true: cookie takes precedence over `source` data attributes.
                store: "auto" // 'cookie', 'local': use localStore, 'session': sessionStore
            },
            source: {
                url: "{{URL::route('admin.category.api.getTree')}}"
            },
            dnd: {
                preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
                preventRecursiveMoves: true, // Prevent dropping nodes on own descendants
                autoExpandMS: 400,
                dragStart: function (node, data) {
                    /** This function MUST be defined to enable dragging for the tree.
                     *  Return false to cancel dragging of node.
                     */
                    return true;
                },
                dragEnter: function (node, data) {
                    /** data.otherNode may be null for non-fancytree droppables.
                     *  Return false to disallow dropping on node. In this case
                     *  dragOver and dragLeave are not called.
                     *  Return 'over', 'before, or 'after' to force a hitMode.
                     *  Return ['before', 'after'] to restrict available hitModes.
                     *  Any other return value will calc the hitMode from the cursor position.
                     */
                    // Prevent dropping a parent below another parent (only sort
                    // nodes under the same parent)
                    /*           if(node.parent !== data.otherNode.parent){
                     return false;
                     }
                     // Don't allow dropping *over* a node (would create a child)
                     return ["before", "after"];
                     */
                    return true;
                },
                dragDrop: function (node, data) {
                    /** This function MUST be defined to enable dropping of items on
                     *  the tree.
                     */
                    data.otherNode.moveTo(node, data.hitMode);

                    var dict = $("#tree").fancytree('getTree').toDict();
                    sendTreeData(dict);
                }
            },
            click: function (event, data) {
                var node = data.node;

                if (node.data.href && data.targetType == 'title') {
                    // Open target
                    window.open(node.data.href, node.data.target);
                }
            },
            lazyLoad: function (event, data) {
                //data.result = {url: "ajax-sub2.json"}
            }
        });



    }

    $('.btnCollapseAll').click(function () {
        $("#tree").fancytree("getRootNode").visit(function (node) {
            node.setExpanded(false);
        });
        return false;
    });
    $('.btnExpandAll').click(function () {
        $("#tree").fancytree("getRootNode").visit(function (node) {
            node.setExpanded(true);
        });
        return false;
    });
    $('.btnRefreshTree').click(function (e) {
        e.preventDefault();
        initTree();
    });

    $(function () {
       initTree();
    });

    function sendTreeData(treeData) {
        $.ajax({
            type: 'post',
            dataType: 'html',
            url: "{{URL::route('admin.category.api.updateTree')}}",
            data: { data: treeData },
            success: function (data) {
                $("#tree").fancytree('getTree').reload();
                if ($('.dt-table').length) {
                    $('.dt-table').dataTable().fnDraw(false);
                }
            },
            error: function (data) {
                alert('Error!');
            }
        })
    }

</script>