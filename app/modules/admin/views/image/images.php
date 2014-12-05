<?php if (count($item->images)): ?>

    <tbody data-model="<?php echo get_class_name($item->images[0]); ?>" <?php echo create_dynamic_attributes('dynamic', $item, $item->{$item->getProperty()}, 'admin::image.images'); ?>>
        <?php foreach ($item->images as $image): ?>
            <tr data-id="sort_<?php echo $image->id; ?>">
                <td>
                    <a href="<?php echo $image->getImage('original') . '?' . $image->getImageTime(); ?>"  <?php echo $image->getImageSize('original'); ?> class="fb-images" rel="current-images">
                        <img src="<?php echo $image->getImage() . '?' . $image->getImageTime(); ?>" class="img-responsive thumbnail">
                    </a>
                </td>
                <td>
                    <div class="space-8"></div>
                    <?php echo Form::text("image[update][alt][{$image->id}]", $image->alt); ?>
                </td>
                <td class="center">
                    <?php echo $thisObj->getStatusButton($image, $image->id, $image->status); ?>
                </td>
                <td class="center">
                    <a class="btn btn-xs btn-primary fb-crop" data-config="<?php echo $image->getRelatedModelConfig(); ?>" href="<?php echo $image->getImage('original') . '?' . $image->getImageTime(); ?>" title="Crop image">
                        <i class="icon-crop bigger-110"></i>
                    </a>
                </td>
                <td class="center">
                    <a class="btn btn-xs btn-primary sortable-handle" href="#" title="Drag & drop">
                        <i class="icon-resize-vertical bigger-110"></i>
                    </a>
                </td>
                <td class="center">
                    <a class="btn btn-xs btn-danger" href="<?php echo URL::route('admin.api.destroyModel', array(get_class_name($image), $image->id)); ?>" data-confirm="Are you sure?">
                        <i class="icon-trash bigger-110"></i>
                        Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
<?php else: ?>
    <tbody  <?php echo create_dynamic_attributes('dynamic', $item, $item->{$item->getProperty()}, 'admin::image.images'); ?>>
        <tr>
            <td colspan="6">There are no items.</td>
        </tr>
    </tbody>
<?php endif; ?>