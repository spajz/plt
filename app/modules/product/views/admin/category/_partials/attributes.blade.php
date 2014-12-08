<?php if (count($item->attr)): ?>

    <tbody data-model="<?php echo get_class_name($item->attr[0]); ?>" <?php echo create_dynamic_attributes('dynamic', $item, $item->{$item->getProperty()}, 'product::admin.category._partials.attributes'); ?>>
        <?php foreach ($item->attr as $attribute): ?>
            <tr data-id="sort_<?php echo $attribute->id; ?>">

                <td>{{ $attribute->id }}</td>
                <td>{{ Form::text("attributes[{$attribute->id}]", $attribute->title); }}</td>
                <td>
                {{ Form::hidden("filters[{$attribute->id}]", 0) }}
                {{ Form::checkbox("filters[{$attribute->id}]", 1, $attribute->filter) }}
                </td>
                <td class="center">
                    {{ $thisObj->getStatusButton($attribute, $attribute->id, $attribute->status) }}
                </td>
                <td class="center">
                    <a class="btn btn-xs btn-primary sortable-handle" href="#" title="Drag & drop">
                        <i class="icon-resize-vertical bigger-110"></i>
                    </a>
                </td>
                <td class="center">
                    <a class="btn btn-xs btn-danger" href="<?php echo URL::route('admin.api.destroyModel', array(get_class_name($attribute), $attribute->id)); ?>" data-confirm="Are you sure?">
                        <i class="icon-trash bigger-110"></i>
                        Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
<?php else: ?>
    <tbody  <?php echo create_dynamic_attributes('dynamic', $item, $item->{$item->getProperty()}, 'product::admin.category._partials.attributes'); ?>>
        <tr>
            <td colspan="6">There are no items.</td>
        </tr>
    </tbody>
<?php endif; ?>


