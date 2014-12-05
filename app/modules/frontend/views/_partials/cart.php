<!-- Shopping cart Modal -->
<div class="modal fade" id="shoppingcart" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Korpa</h4>
            </div>
            <div class="modal-body">

                <!-- Items table -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Naziv</th>
                            <th>Kolicina</th>
                            <th>Cena</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (Cart::totalItems(true) > 0): ?>
                            <?php foreach (Cart::contents() as $cart_item): ?>
                                <tr>
                                    <td><?php echo $cart_item->name; ?></td>
                                    <td><?php echo $cart_item->quantity; ?></td>
                                    <td><?php echo cena($cart_item->price, false); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Nastavi kupovinu</button>
                <button type="button" class="btn btn-info">Kupi odabrane proizvode</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->