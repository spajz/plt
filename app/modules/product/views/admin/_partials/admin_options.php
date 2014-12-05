<div class="row">

    <div class="col-xs-12">

        <?php echo Form::open(array('route' => array('admin.options'), 'method' => 'post', 'class' => 'form-inline', 'role' => 'form')); ?>

        <div class="form-group pull-right">
            <label for="size">Grupe</label>
            <?php echo Form::select('options[category][status]', array('-1' => 'Sve', '0' => 'Isključene', '1' => 'Uključene'), Session::get('options.category.status'), array('class' => 'submit-form-change')); ?>
        </div>

        <div class="form-group pull-right">
            <label for="size">Drag & Drop</label>
            <?php echo Form::select('options[category][dnd]', array('0' => 'Ne', '1' => 'Da'), Session::get('options.category.dnd'), array('class' => 'submit-form-change')); ?>
        </div>

        <div class="form-group pull-right">
            <label for="size">Broj proizvoda</label>
            <?php echo Form::select('options[category][count_products]', array('0' => 'Ne', '1' => 'Da'), Session::get('options.category.count_products'), array('class' => 'submit-form-change')); ?>
        </div>

        <div class="form-group pull-right">
            <label for="size">Proizvodi</label>
            <?php echo Form::select('options[product][show_item]', array('-1' => 'Svi', '0' => 'Isključeni', '1' => 'Uključeni'), Session::get('options.product.show_item'), array('class' => 'submit-form-change')); ?>
        </div>

        <?php echo Form::close(); ?>
    </div>
</div>