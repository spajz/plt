<div class="row multi-columns-row">
    <?php foreach ($result as $item): ?>

        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
            <div class="search-item-box clearfix">

                <?php if (!empty($item->contentVideo->id)): ?>

                    <a href="<?php echo $item->contentVideo->url; ?>" class="fb-videos" rel="search-videos">
                        <?php if (isset($item->contentImage->thumb->url)): ?>
                            <img src="<?php echo $item->contentImage->thumb->url; ?>" class="img-responsive thumbnail">
                        <?php else: ?>
                            <div class="text-center"><i class="icon-warning-sign bigger-800"></i></div>
                        <?php endif; ?>
                    </a>

                    <small>
                        Video ID: <?php echo $item->contentVideo->id; ?><br>
                        Host: <?php echo $item->contentVideo->host; ?><br>
                        Duration: <?php echo $item->contentVideo->time ? gmdate("H:i:s", $item->contentVideo->time) : 'N/A'; ?>
                        <br>
                        Title: <?php echo $item->contentVideo->title; ?><br>
                        Url:
                        <a href="<?php echo $item->contentVideo->url; ?>" target="_blank"><?php echo str_limit($item->contentVideo->url, 30); ?></a><br>
                    </small>

                    <?php echo Form::checkbox("items[{$item->contentVideo->id}]", $item->contentVideo->url, null, array('class' => 'pull-right search-checkbox')) ?>
                    <?php echo Form::hidden("titles[{$item->contentVideo->id}]", $item->contentVideo->title); ?>

                <?php else: ?>
                    <div class="text-center"><i class="icon-warning-sign bigger-800"></i></div>

                    <small>
                        Host: <?php echo $item->contentVideo->host; ?><br>
                        Title: <?php echo $item->contentVideo->title; ?><br>
                        Url:
                        <a href="<?php echo $item->contentVideo->url; ?>" target="_blank"><?php echo str_limit($item->contentVideo->url, 30); ?></a><br>
                    </small>

                <?php endif; ?>

            </div>

        </div>

    <?php endforeach; ?>
</div>

