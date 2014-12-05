<div class="recent-posts blocky">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Section title -->
                <div class="section-title">
                    <h4><i class="icon-desktop color"></i> &nbsp;Novi proizvodi</h4>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="my_carousel">

                            <div class="carousel_nav pull-right">
                                <!-- Carousel navigation -->
                                <a class="prev" id="car_prev" href="#"><i class="fa fa-chevron-left"></i></a>
                                <a class="next" id="car_next" href="#"><i class="fa fa-chevron-right"></i></a>
                            </div>
                            <?php if (!empty($products)): ?>

                                <ul id="carousel_container">
                                    <!-- Carousel item -->
                                    <?php foreach ($products as $product): ?>
                                        <li>
                                            <?php if ($product->productDescription): ?>
                                                <?php if (is_file(public_path() . '/slike/' . $product->category->group_name . '/' . $product->productDescription->slika)): ?>
                                                    <a href="<?php echo route('product', array('slug' => Str::slug($product->proizvod), 'id' => $product->proizvod_id)); ?>">
                                                        <img src="<?php echo url('slike/' . $product->category->group_name . '/' . $product->productDescription->slika); ?>" alt="" class="img-responsive"/>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?php echo route('product', array('slug' => Str::slug($product->proizvod), 'id' => $product->proizvod_id)); ?>">
                                                        <img src="<?php echo url(frontend_asset('img/no-image300.jpg')); ?>" alt="" class="img-responsive"/>
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <a href="<?php echo route('product', array('slug' => Str::slug($product->proizvod), 'id' => $product->proizvod_id)); ?>">
                                                    <img src="<?php echo url(frontend_asset('img/no-image300.jpg')); ?>" alt="" class="img-responsive"/>
                                                </a>
                                            <?php endif; ?>

                                            <div class="carousel_caption">
                                                <h5>
                                                    <a href="<?php echo route('product', array('slug' => Str::slug($product->proizvod), 'id' => $product->proizvod_id)); ?>">
                                                        <?php echo $product->proizvod; ?>
                                                    </a>
                                                </h5>

                                                <a href="#" class="btn btn-info btn-sm"><i class="icon-shopping-cart"></i>
                                                    Kupi za <?php echo cena($product->cena_prodajna); ?> din.</a>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>

                                </ul>

                            <?php endif; ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>