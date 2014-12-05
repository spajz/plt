<div class="alert alert-<?php echo $key ?> alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <?php foreach($group as $message): ?>
        <p><?php echo $message ?></p>
    <?php endforeach ?>
</div>