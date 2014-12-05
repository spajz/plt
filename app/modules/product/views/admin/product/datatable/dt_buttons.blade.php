<div>
    {{ $thisObj->getStatusButton($model, $model->proizvod_id, $model->show_item) }}

    <a class="btn btn-xs btn-primary" href="{{ URL::route($base . 'edit', $model->proizvod_id) }}">
        <i class="icon-pencil bigger-110"></i>
        Edit
    </a>

    <a class="btn btn-xs btn-danger" href="{{ URL::route($base . 'destroy', $model->proizvod_id) }}" data-method="delete" data-confirm="Are you sure?">
        <i class="icon-trash bigger-110"></i>
        Delete
    </a>
</div>