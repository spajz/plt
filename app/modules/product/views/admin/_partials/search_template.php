<?php if ($result): ?>
    <div class="row">
        <div class="col-xs-9"><?php echo $paginatorLinks; ?></div>
        <div class="col-xs-3">
            <a class="btn btn-sm btn-success pull-right submit-form-click" href="<?php echo URL::route('admin.product.api.search', array('page' => 1)); ?>" data-form="#search-engine-form">
                <i class="icon-share bigger-110"></i>
                Add Selected Items
            </a>
        </div>
    </div>
    <?php echo Form::open(array('route' => array($base . 'api.addItemsFromSearch'), 'method' => 'post', 'role' => 'form', 'id' => 'search-engine-form')); ?>

        <?php echo Form::hidden('type', $type); ?>

        <?php echo View::make('product::admin._partials.search_' . $type, array('result' => $result)); ?>

    <?php echo Form::close(); ?>
    <div class="row">
        <div class="col-xs-9"><?php echo $paginatorLinks; ?></div>
        <div class="col-xs-3">
            <a class="btn btn-sm btn-success pull-right submit-form-click" href="<?php echo URL::route('admin.product.api.search', array('page' => 1)); ?>" data-form="#search-engine-form">
                <i class="icon-share bigger-110"></i>
                Add Selected Items
            </a>
        </div>
    </div>
<?php else: ?>

    <div class="row">
        <div class="col-xs-12">
            <?php echo Msg::info('No results for the term: ' . $term)->showInfoInstant(); ?>
        </div>
    </div>

<?php endif; ?>