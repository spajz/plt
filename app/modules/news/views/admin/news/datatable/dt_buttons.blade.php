<div>
    {{ $thisObj->getStatusButton($model, $model->id, $model->status) }}

    <a class="btn btn-xs btn-primary" href="{{ URL::route($base . 'edit', $model->id) }}">
        <i class="icon-pencil bigger-110"></i>
        Edit
    </a>

    <a class="btn btn-xs btn-danger" href="{{ URL::route($base . 'destroy', $model->id) }}" data-method="delete" data-confirm="Are you sure?">
        <i class="icon-trash bigger-110"></i>
        Delete
    </a>
</div>