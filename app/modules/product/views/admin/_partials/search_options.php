<?php
$engine = Session::get('options.search.engine');
$type = Session::get('options.search.type');
?>
<?php echo Form::open(array('route' => array('admin.product.api.searchOptions'), 'method' => 'post', 'class' => 'form-inline form-options ajax-form', 'role' => 'form', 'data-box' => '.form-search-box')); ?>
<?php echo Form::hidden('id', $item->{$item->getProperty()}); ?>
    <div class="row">

        <div class="col-xs-12">

            <?php if ($type == 'Videos'): ?>

                <div class="form-group pull-right">
                    <label for="videoSites">Video sites</label>
                    <?php echo Form::select('options[search][videoSites]', Config::get('search.options.videoSites'), Session::get('options.search.videoSites'), array('class' => 'submit-form-change')); ?>
                </div>

            <?php endif; ?>

            <?php if ($type == 'Images'): ?>

                <?php if (Config::get("search.{$engine}.imgDimension")): ?>
                    <div class="form-group pull-right">
                        <label for="width">Width</label>
                        <?php echo Form::text('options[search][imgDimension][width]', Session::get("options.search.imgDimension.width"), array('class' => 'input-mini')); ?>
                    </div>
                    <div class="form-group pull-right">
                        <label for="height">Height</label>
                        <?php echo Form::text('options[search][imgDimension][height]', Session::get("options.search.imgDimension.height"), array('class' => 'input-mini')); ?>

                    </div>
                <?php endif; ?>

                <?php foreach (array('imgAspect', 'imgType', 'imgSize') as $option): ?>
                    <?php if (!Config::get("search.{$engine}.{$option}")) continue; ?>
                    <div class="form-group pull-right">
                        <label for="<?php echo $option; ?>"><?php echo $option; ?></label>
                        <?php echo Form::select("options[search][{$option}]", Config::get("search.{$engine}.{$option}"), Session::get("options.search.{$option}"), array('class' => 'submit-form-change')); ?>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>

            <div class="form-group pull-right">
                <label for="type">Search type</label>
                <?php echo Form::select('options[search][type]', Config::get('search.options.type'), Session::get('options.search.type'), array('class' => 'submit-form-change')); ?>
            </div>

            <div class="form-group pull-right">
                <label for="engine">Engine</label>
                <?php echo Form::select('options[search][engine]', Config::get('search.options.engine'), Session::get('options.search.engine'), array('class' => 'submit-form-change')); ?>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">

            <div class="form-group pull-right">
                <a class="btn btn-xs btn-success search-start" href="<?php echo URL::route('admin.product.api.search', array('page' => 1)); ?>">
                    <i class="icon-search bigger-110"></i>
                    Search
                </a>
            </div>

            <div class="form-group pull-right">
                <label for="term">Term</label>
                <?php //echo Form::text('options[search][term]', Session::get("options.search.term"), array('class' => 'input-mini-height')); ?>
                <?php echo Form::text('options[search][term]', isset($searchTerm) ? $searchTerm : $item->title, array('class' => 'input-mini-height input-xxlarge search-term')); ?>
            </div>

        </div>

    </div>
<?php echo Form::close(); ?>