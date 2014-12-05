<?php if (count($item->videos)): ?>

    <tbody data-model="<?php echo get_class_name($item->videos[0]); ?>" <?php echo create_dynamic_attributes('dynamic', $item, $item->{$item->getProperty()}, 'admin::video.videos'); ?>>
        <?php foreach ($item->videos as $video): ?>
            <tr data-id="sort_<?php echo $video->id; ?>">
                <td>
                    <a href="<?php echo $video->url; ?>" class="fb-videos" rel="current-videos">
                        <img src="<?php echo $video->thumb; ?>" class="img-responsive thumbnail">
                    </a>
                </td>
                <td>
                    <a href="<?php echo $video->url; ?>" target="_blank" title="Go to source"><?php echo $video->url; ?></a>

                    <div class="space-8"></div>
                    <?php echo Form::text("video[update][title][{$video->id}]", $video->title); ?>
                </td>
                <td class="center">
                    <?php echo $thisObj->getStatusButton($video, $video->id, $video->status); ?>
                </td>
                <td class="center">
                    <a class="btn btn-xs btn-primary sortable-handle" href="#" title="Drag & drop">
                        <i class="icon-resize-vertical bigger-110"></i>
                    </a>
                </td>
                <td class="center">
                    <a class="btn btn-xs btn-danger" href="<?php echo URL::route('admin.api.destroyModel', array(get_class_name($video), $video->id)); ?>" data-confirm="Are you sure?">
                        <i class="icon-trash bigger-110"></i>
                        Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
<?php else: ?>
    <tbody <?php echo create_dynamic_attributes('dynamic', $item, $item->{$item->getProperty()}, 'admin::video.videos'); ?>>
        <tr>
            <td colspan="5">There are no items.</td>
        </tr>
    </tbody>
<?php endif; ?>