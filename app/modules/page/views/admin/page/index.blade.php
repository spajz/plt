@section('footer_scripts')
{{ $table->script('page::admin.page.datatable.javascript') }}

<script type="text/javascript">
    var tree = $('#tree');

    $(function () {
        // --- Initialize first Dynatree -------------------------------------------
        tree.dynatree({
            fx: { height: "toggle", duration: 200 },
            persist: true,
            initAjax: {
                url: "{{URL::route('admin.page.api.getTree')}}"
            },

            cookie: {
                expires: 7,
                path: '/'
            },
            autoFocus: false,
            cookieId: 'treePage',

            onActivate: function (node) {
                // Use <a> href and target attributes to load the content:
                if (node.data.href) {
                    // Open target
                    window.open(node.data.href, node.data.target);
                }
            },

            dnd: {
                onDragStart: function (node) {
                    /** This function MUST be defined to enable dragging for the tree.
                     *  Return false to cancel dragging of node.
                     */
                    //logMsg("tree.onDragStart(%o)", node);
                    return true;
                },
                onDragStop: function (node) {
                    // This function is optional.
                    // logMsg("tree.onDragStop(%o)", node);
                },
                autoExpandMS: 500,
                preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
                onDragEnter: function (node, sourceNode) {
                    /** sourceNode may be null for non-dynatree droppables.
                     *  Return false to disallow dropping on node. In this case
                     *  onDragOver and onDragLeave are not called.
                     *  Return 'over', 'before, or 'after' to force a hitMode.
                     *  Return ['before', 'after'] to restrict available hitModes.
                     *  Any other return value will calc the hitMode from the cursor position.
                     */
                    // logMsg("tree.onDragEnter(%o, %o)", node, sourceNode);
                    return true;
                },
                onDragOver: function (node, sourceNode, hitMode) {
                    /** Return false to disallow dropping this node.
                     *
                     */
                    //logMsg("tree.onDragOver(%o, %o, %o)", node, sourceNode, hitMode);
                    // Prevent dropping a parent below it's own child
                    if (node.isDescendantOf(sourceNode)) {
                        return false;
                    }
                    // Prohibit creating childs in non-folders (only sorting allowed)
                    if (!node.data.isFolder && hitMode === "over") {
                        return "after";
                    }
                },
                onDrop: function (node, sourceNode, hitMode, ui, draggable) {
                    /** This function MUST be defined to enable dropping of items on
                     * the tree.
                     */
                        // logMsg("tree.onDrop(%o, %o, %s)", node, sourceNode, hitMode);
                    sourceNode.move(node, hitMode);
                    // expand the drop target

                    var dict = tree.dynatree('getTree').toDict();
                    sendTreeData(dict);

//        sourceNode.expand(true);
                },
                onDragLeave: function (node, sourceNode) {
                    /** Always called if onDragEnter was called.
                     */
                    // logMsg("tree.onDragLeave(%o, %o)", node, sourceNode);
                }
            }
        });

        $('.btnCollapseAll').click(function () {
            tree.dynatree('getRoot').visit(function (node) {
                node.expand(false);
            });
            return false;
        });
        $('.btnExpandAll').click(function () {
            tree.dynatree('getRoot').visit(function (node) {
                node.expand(true);
            });
            return false;
        });

    });

    function sendTreeData(treeData) {
        $.ajax({
            type: 'post',
            dataType: 'html',
            url: "{{URL::route('admin.page.api.updateTree')}}",
            data: { data: treeData },
            success: function (data) {
                tree.dynatree('getTree').reload();
                $('.dt-table-page').dataTable().fnDraw(false);
            },
            error: function (data) {
                alert('Error!');
            }
        })
    }

</script>
@stop

<div class="page-content">
    <div class="page-header">
        <h1>
            Page
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

                    <?php  echo View::make('product::admin._partials.admin_options'); ?>

                    <div class="table-header">
                        Page List
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
