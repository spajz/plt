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
                url: "{{URL::route('admin.product.api.getTree')}}"
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
            url: "{{URL::route('admin.product.api.updateTree')}}",
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
<?php /*
<script type="text/javascript">

    $(window).load(function () {
        $('.dz-default').prependTo('.dropzone-previews');
    })

    Dropzone.options.mainForm = { // The camelized version of the ID of the form element

        // The configuration we've talked about above
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 10,
        maxFiles: 10,
        acceptedFiles: 'image/*',
        previewsContainer: '.dropzone-previews',
        maxFilesize: 1,
        method: 'post',
        addRemoveLinks: true,
        url: '{{URL::route($base . 'update', $product->proizvod_id)}}',

        // The setting up of the dropzone
        init: function () {
            var myDropzone = this;

            // First change the button to actually tell Dropzone to process the queue.
            this.element.querySelector("button[type=submit]").addEventListener("click", function (e) {
                // Make sure that the form isn't actually being sent.
                e.preventDefault();
                e.stopPropagation();

                if (myDropzone.getQueuedFiles().length > 0) {
                    myDropzone.processQueue();
                }
                else {
                    // Upload anyway without files
                    myDropzone.uploadFiles([ ]);
                }
            });


            // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
            // of the sending event because uploadMultiple is set to true.
            this.on("sendingmultiple", function () {
                // Gets triggered when the form is actually being sent.
                // Hide the success button or the complete form.
            });
            this.on("successmultiple", function (files, response) {
                // Gets triggered when the files have successfully been sent.
                // Redirect user or notify of success.
            });
            this.on("errormultiple", function (files, response) {
                // Gets triggered when there was an error sending the files.
                // Maybe show form again, and notify user of error
            });
        }

    }


</script>
*/
?>
@stop

<div class="page-content">
<div class="page-header">
    <h1>
        Edit Product
        <small>
            <i class="icon-double-angle-right"></i>
            <?php echo $product->proizvod; ?>
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

<?php echo View::make('product::admin._partials.admin_options'); ?>

<!-- PAGE CONTENT BEGINS -->

<?php //echo HTML::ul($errors->all()); ?>


<?php echo Form::model($product, array('route' => array($base . 'update', $product->proizvod_id), 'method' => 'put', 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'main-form', 'enctype' => 'multipart/form-data')); ?>
<?php //echo Form::hidden('_method', 'put') ?>
<small class="anchor-show"></small>
<div class="widget-box">
    <div class="widget-header">
        <h4 class="anchor-this" id="base-information">Base Information</h4>

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
                <?php echo Form::label('proizvod_id', 'ID', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('id', $product->proizvod_id, $attributes_field + array('disabled', 'id' => 'id')); ?>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('proizvod', 'Name', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('proizvod', null, $attributes_field); ?>
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
                <?php echo Form::label('show_item', 'Status', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::hidden('show_item', 0); ?>
                    <?php echo Form::checkbox('show_item', 1, null, array('class' => 'ace ace-switch')); ?>
                    <span class="lbl"></span>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('cena_prodajna', 'Cena &euro;', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('cena_prodajna', nf($product->cena_prodajna), $attributes_field + array('disabled')); ?>
                </div>
            </div>
            <div class="space-4"></div>

            <div class="form-group">
                <?php echo Form::label('cena_prodajna', 'Cena RSD', $attributes_label); ?>
                <div class="col-sm-11">
                    <?php echo Form::text('cena_prodajna', nf($product->cena_prodajna * $kurs), $attributes_field + array('disabled')); ?>
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

<small class="anchor-show"></small>
<div class="widget-box">
    <div class="widget-header">
        <h4 class="anchor-this" id="seo-information">Seo Information</h4>

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

<small class="anchor-show"></small>
<div class="widget-box">
    <div class="widget-header">
        <h4 class="anchor-this" id="images">Images</h4>

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

            <h5>Current Images</h5>
            <table class="table table-striped table-bordered table-hover table-sortable">
                <thead>
                    <tr>
                        <th class="w120">Image</th>
                        <th>Alt</th>
                        <th class="w100">Status</th>
                        <th class="w100">Crop</th>
                        <th class="w100">Sort</th>
                        <th class="w100">Actions</th>
                    </tr>
                </thead>

                <tbody <?php echo create_dynamic_attributes('dynamic', $product, $product->proizvod_id, 'admin::image.images'); ?>></tbody>
            </table>

            <h5>Upload Images</h5>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>File</th>
                        <th>Alt</th>
                        <th class="w100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo Form::file('image[file][]'); ?></td>
                        <td>
                            <?php echo Form::text('image[alt][]', null, $attributes_field); ?>
                        </td>
                        <td>
                            <a class="btn btn-xs btn-info clone-elem" data-elem="tr" href="#" title="Add row">
                                <i class="icon-plus bigger-110"></i>
                            </a>
                            <a class="btn btn-xs btn-info remove-elem" data-elem="tr" href="#" title="Remove row">
                                <i class="icon-minus bigger-110"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
    <!-- eof widget-body -->
</div>
<!-- eof widget-box -->


<div class="space-8"></div>

<small class="anchor-show"></small>
<div class="widget-box">
    <div class="widget-header">
        <h4 class="anchor-this" id="videos">Videos</h4>

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

            <h5>Current Videos</h5>
            <table class="table table-striped table-bordered table-hover table-sortable">
                <thead>
                    <tr>
                        <th class="w120">Video</th>
                        <th>Url / Title</th>
                        <th class="w100">Status</th>
                        <th class="w100">Sort</th>
                        <th class="w100">Actions</th>
                    </tr>
                </thead>

                <tbody <?php echo create_dynamic_attributes('dynamic', $product, $product->proizvod_id, 'admin::video.videos'); ?>></tbody>

            </table>

            <h5>Add Videos</h5>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Video</th>
                        <th>Title</th>
                        <th class="w100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?php echo Form::text('video[insert][url][]', null, $attributes_field); ?>
                        </td>
                        <td>
                            <?php echo Form::text('video[insert][title][]', null, $attributes_field); ?>
                        </td>
                        <td class="center">
                            <a class="btn btn-xs btn-info clone-elem" data-elem="tr" href="#" title="Add row">
                                <i class="icon-plus bigger-110"></i>
                            </a>
                            <a class="btn btn-xs btn-info remove-elem" data-elem="tr" href="#" title="Remove row">
                                <i class="icon-minus bigger-110"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>

            </table>

        </div>
    </div>
    <!-- eof widget-body -->
</div>
<!-- eof widget-box -->


<?php /*
<div class="space-8"></div>

<div class="widget-box">
    <div class="widget-header">
        <h4>Upload Images</h4>

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

            <div class="dropzone-previews"></div>

        </div>
    </div>
    <!-- eof widget-body -->
</div>
<!-- eof widget-box -->
 */
?>
<a class="anchor-this" id="save" data-title="Save"></a>

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

<div class="form-search-box">
    <?php echo View::make('admin::search.search_options', array('item' => $product)); ?>
</div>

<small class="anchor-show"></small>
<div class="widget-box">
    <div class="widget-header">
        <h4 class="anchor-this" id="search">Search</h4>

        <div class="widget-toolbar">
            <a href="#" data-action="collapse">
                <i class="icon-chevron-up"></i>
            </a>

            <a href="#" data-action="close">
                <i class="icon-remove"></i>
            </a>
        </div>
        <div class="widget-toolbar">
            <a href="#" class="check-all" data-elem=".search-item-box" title="Check all">
                <i class="icon-check"></i>
            </a>

            <a href="#" class="uncheck-all" data-elem=".search-item-box" title="Uncheck all">
                <i class="icon-check-empty"></i>
            </a>

            <a href="#" class="check-invert" data-elem=".search-item-box" title="Check invert">
                <i class="icon-check-sign"></i>
            </a>
        </div>
    </div>
    <!-- eof widget-header -->

    <div class="widget-body">
        <div class="widget-main">

            <div class="search-results"></div>

        </div>
    </div>
    <!-- eof widget-body -->
</div>
<!-- eof widget-box -->


<!-- PAGE CONTENT ENDS -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div><!-- /.page-content -->
