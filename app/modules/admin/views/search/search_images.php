<div class="row multi-columns-row">
    <?php foreach ($result as $key => $item): ?>

        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
            <div class="search-item-box clearfix">
                <a href="<?php echo $item->contentImage->full->url; ?>" class="fb-images" rel="group">
                    <img src="<?php echo $item->contentImage->thumb->url; ?>" class="img-responsive thumbnail">
                </a>

                Dimenzija: <?php echo $item->contentImage->full->width; ?>
                x <?php echo $item->contentImage->full->height; ?>
                <?php echo Form::checkbox("items[{$key}]", $item->contentImage->full->url, null, array('class' => 'pull-right search-checkbox')) ?>
            </div>

        </div>

    <?php endforeach; ?>
</div>
