<div class="visible-md visible-lg hidden-sm hidden-xs">
    <a class="btn btn-xs btn-danger" href="{{ URL::route( '<?php echo $base; ?>edit', $proizvodjac_id) }}">
        <i class="icon-trash bigger-110"></i>
        Delete
    </a>

    <a class="btn btn-xs btn-primary" href="{{ URL::route( '<?php echo $base; ?>edit', $proizvodjac_id) }}">
        <i class="icon-pencil bigger-110"></i>
        Edit
    </a>

</div>