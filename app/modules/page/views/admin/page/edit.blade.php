<?php
$attributes_label = array('class' => 'col-sm-1 control-label no-padding-right');
$attributes_field = array('class' => 'col-xs-10 col-sm-5');
?>
@section('footer_scripts')
<script type="text/javascript">
    CKEDITOR.disableAutoInline = true;
    $(window).load(function () {
        // $('.ckeditor').ckeditor(); // Use CKEDITOR.replace() if element is <textarea>.
    });
</script>

<script type="text/javascript">
    var tree = $('#tree');

    $(function () {
        // --- Initialize first Dynatree -------------------------------------------
        tree.dynatree({
            fx: { height: "toggle", duration: 200 },
            persist: true,
            initAjax: {
                url: "{{URL::route('admin.category.api.getTree')}}"
            },

            cookie: {
                expires: 7,
                path: '/'
            },
            autoFocus: false,
            cookieId: 'treeCategory',

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
            url: "{{URL::route('admin.category.api.updateTree')}}",
            data: { data: treeData},
            success: function (data) {
                tree.dynatree('getTree').reload();
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
        Edit Category
        <small>
            <i class="icon-double-angle-right"></i>
            <?php echo $category->title ? : $category->group_name ; ?>
        </small>
    </h1>
</div>
<!-- /.page-header -->

<div class="row">

<div class="col-xs-2">
    <a href="#" class="btnCollapseAll" title="Collapse all"><i class="icon-folder-close-alt"></i></a>&nbsp;
    <a href="#" class="btnExpandAll" title="Expand all"><i class="icon-folder-open-alt"></i></a>

    <div id="tree"></div>

    <a href="#" class="btnCollapseAll" title="Collapse all"><i class="icon-folder-close-alt"></i></a>&nbsp;
    <a href="#" class="btnExpandAll" title="Expand all"><i class="icon-folder-open-alt"></i></a>
</div>

<div class="col-xs-10">

<?php  echo View::make('product::admin._partials.admin_options'); ?>

<!-- PAGE CONTENT BEGINS -->

<?php //echo HTML::ul($errors->all()); ?>
<?php echo Form::model($category, array('route' => array($base . 'update', $category->group_id), 'method' => 'put', 'class' => 'form-horizontal', 'role' => 'form')); ?>

<div class="widget-box">
    <div class="widget-header">
        <h4>Base Information</h4>

        <div class="widget-toolbar">
            <a href="#" data-action="collapse">
                <i class="icon-chevron-up"></i>
            </a>

            <a href="#" data-action="close">
                <i class="icon-remove"></i>
            </a>
        </div>
    </div>
    <!-- eof widget-header -->

    <div class="widget-body">
        <div class="widget-main">

            <div class="form-group">
                <?php echo Form::label('group_name', 'Name', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('group_name', null, $attributes_field); ?>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('title', 'Title', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('title', null, $attributes_field); ?>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('slug', 'Slug', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('slug', null, $attributes_field); ?>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('status', 'Status', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::hidden('status', 0); ?>
                    <?php echo Form::checkbox('status', 1, null, array('class' => 'ace ace-switch')); ?>
                    <span class="lbl"></span>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('show_menu', 'Show In Menu', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::hidden('show_menu', 0); ?>
                    <?php echo Form::checkbox('show_menu', 1, null, array('class' => 'ace ace-switch ace-switch-2')); ?>
                    <span class="lbl"></span>
                </div>
            </div>
            <div class="space-4"></div>


            <div class="form-group">
                <?php echo Form::label('intro', 'Intro', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::textarea('intro', null, array('rows' => 3) + $attributes_field); ?>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('description', 'Description', $attributes_label); ?>

                <div class="col-sm-11">
                    <?php echo Form::textarea('description', null, array('class' => $attributes_field['class'] . ' ckeditor')); ?>
                </div>
            </div>
            <div class="space-4"></div>

        </div>
    </div>
    <!-- eof widget-body -->
</div>
<!-- eof widget-box -->

<div class="space-8"></div>

<div class="widget-box">
    <div class="widget-header">
        <h4>Seo Information</h4>

        <div class="widget-toolbar">
            <a href="#" data-action="collapse">
                <i class="icon-chevron-up"></i>
            </a>

            <a href="#" data-action="close">
                <i class="icon-remove"></i>
            </a>
        </div>
    </div>
    <!-- eof widget-header -->

    <div class="widget-body">
        <div class="widget-main">

            <div class="form-group">
                <?php echo Form::label('seo_title', 'Seo Title', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('seo_title', null, $attributes_field); ?>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('seo_keywords', 'Seo Keywords', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('seo_keywords', null, $attributes_field); ?>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('seo_description', 'Seo Description', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('seo_description', null, $attributes_field); ?>
                </div>
            </div>
            <div class="space-4"></div>

        </div>
    </div>
    <!-- eof widget-body -->
</div>
<!-- eof widget-box -->

<div class="space-8"></div>

<div class="widget-box">
    <div class="widget-header">
        <h4>Media</h4>

        <div class="widget-toolbar">
            <a href="#" data-action="collapse">
                <i class="icon-chevron-up"></i>
            </a>

            <a href="#" data-action="close">
                <i class="icon-remove"></i>
            </a>
        </div>
    </div>
    <!-- eof widget-header -->

    <div class="widget-body">
        <div class="widget-main">


        </div>
    </div>
    <!-- eof widget-body -->
</div>
<!-- eof widget-box -->

<div class="clearfix form-actions">
    <div class="col-md-offset-3 col-md-9">
        <button class="btn" type="reset">
            <i class="icon-undo bigger-110"></i>
            Reset
        </button>
        &nbsp; &nbsp; &nbsp;
        <button class="btn btn-success" type="submit" name="save" value="edit">
            <i class="icon-save bigger-110"></i>
            Save
        </button>
        &nbsp; &nbsp; &nbsp;
        <button class="btn btn-success" type="submit" name="save" value="create">
            <i class="icon-plus bigger-110"></i>
            Save & New
        </button>
        &nbsp; &nbsp; &nbsp;
        <button class="btn btn-success" type="submit" name="save" value="index">
            <i class="icon-external-link bigger-110"></i>
            Save & Exit
        </button>

    </div>
</div>

<?php echo Form::close(); ?>

<!-- PAGE CONTENT ENDS -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div><!-- /.page-content -->
