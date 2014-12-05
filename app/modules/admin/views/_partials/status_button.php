<?php if ($status == 1): ?>
    <a class="btn btn-xs btn-success toggle-status" href="#" data-model="<?php echo urlencode2($model); ?>" data-id="<?php echo $id; ?>" title="Click to disable">
        <i class="icon-ok bigger-110"></i>
    </a>
<?php else: ?>
    <a class="btn btn-xs btn-danger toggle-status" href="#" data-model="<?php echo urlencode2($model); ?>" data-id="<?php echo $id; ?>" title="Click to enable">
        <i class="icon-off bigger-110"></i>
    </a>
<?php endif; ?>