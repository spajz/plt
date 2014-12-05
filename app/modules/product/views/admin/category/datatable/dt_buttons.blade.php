<div>
    <a class="btn btn-xs btn-danger" href="{{ URL::route($base . 'destroy', $model->group_id) }}" data-method="delete" data-confirm="Are you sure?">
        <i class="icon-trash bigger-110"></i>
        Delete
    </a>

    <a class="btn btn-xs btn-primary" href="{{ URL::route($base . 'edit', $model->group_id) }}">
        <i class="icon-pencil bigger-110"></i>
        Edit
    </a>
</div>