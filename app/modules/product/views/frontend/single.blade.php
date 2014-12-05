<div class="breadcrumb-wrap">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?php echo url('/'); ?>">Početna</a> <span class="divider"></span></li>
            <li>
                <a href="<?php echo route('product.category', array('slug' => Str::slug($product->category->group_name), 'id' => $product->category->group_id)); ?>">
                    <?php echo $product->category->group_name ?>
                </a>
                <span class="divider"></span>
            </li>
            <li class="active"><?php echo $product->proizvod; ?></li>
        </ul>
    </div>
</div>

<!-- Page title -->
<div class="page-title">
    <div class="container">
        <h2><?php echo $product->proizvod; ?></h2>
        <hr/>
    </div>
</div>
<!-- Page title -->

<!-- Page content -->

<div class="shop-items">
<div class="container">

<div class="row">

<div class="col-md-9 col-md-push-3">

    <div class="single-item">
        <div class="row">
            <div class="col-md-5 col-xs-5">

                <div class="item-image">
                    @if(count($product->images))

                        <ul class="bxslider">
                            @foreach($product->images as $image)


                            @if(is_file(public_path() . '/media/product/images/large/' . $image->image))

                            <li>
                                <a href="{{ url('media/product/images/large/' . $image->image) }}" rel="gallery" class="fancybox">
                                    <img src="{{ url('media/product/images/large/' . $image->image) }}">
                                </a>
                            </li>

                            @endif

                            @endforeach
                        </ul>

                    @else
                        <?php if (isset($product->productDescription->slika) && isset($product->category->group_name)  && is_file(public_path() . '/slike/' . $product->category->group_name . '/' . $product->productDescription->slika)): ?>
                            <img src="<?php echo url('slike/' . $product->category->group_name . '/' . $product->productDescription->slika); ?>" alt="" class="img-responsive"/>
                        <?php else: ?>
                            <img src="<?php echo url(frontend_asset('img/no-image300.jpg')); ?>" alt="" class="img-responsive"/>
                        <?php endif; ?>
                    @endif

                </div>

            </div>
            <div class="col-md-7 col-xs-7">

                <h5 class="single-cena"><strong>Cena: <?php echo cena($product->cena_prodajna); ?> din.</strong></h5>

                <p><strong>Naziv:</strong>
                    <?php echo $product->proizvod; ?>
                </p>

                <p><strong>Grupa:</strong>
                    <?php echo $product->category->group_name; ?>
                </p>

                <p><strong>Proizvođač:</strong>
                    <a href="<?php echo url($product->brand->proizvodjac_link); ?>" target="_blank"><?php echo $product->brand->proizvodjac; ?></a>
                </p>

                <p><strong>Na lageru:</strong> Pozvati</p>

                <?php if (!empty($product->productdata->new)): ?>
                    <p><strong>U katalogu od:</strong> <?php echo date('d-m-Y', $product->productdata->new); ?></p>
                <?php endif; ?>

                <br/>
                <!-- Quantity and add to cart button -->

                <div class="input-group">
                    <input type="text" class="form-control quantity" value="1">
                     <span class="input-group-btn">
                       <a class="btn btn-success add-to-cart-single" href="<?php echo route('cart.add', array('id' => $product->proizvod_id)); ?>"><i class="icon-shopping-cart"></i>
                           U
                           korpu
                       </a>
                     </span>
                </div>
                <!-- /input-group -->

                <!-- Add to wish list -->
                <a href="wishlist.html">+ Dodaj u listu želja</a>

                <div class="social-wrap clearfix">
                    <div class="box" style="width: 60px;">
                        <div class="g-plusone" data-size="medium"></div>
                    </div>
                    <div class="box">
                        <div data-href="http://www.google.com" class="fb-like" data-width="150" data-height="50" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="false" data-send="false"></div>
                    </div>
                    <div class="box">
                        <a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
                        <script>!function (d, s, id) {
                                var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                                if (!d.getElementById(id)) {
                                    js = d.createElement(s);
                                    js.id = id;
                                    js.src = p + '://platform.twitter.com/widgets.js';
                                    fjs.parentNode.insertBefore(js, fjs);
                                }
                            }(document, 'script', 'twitter-wjs');</script>
                    </div>
                </div>


                <!-- Share button -->

            </div>
        </div>
    </div>

    <br/>

    <div class="clearfix"></div>

    <!-- Description, specs and review -->

    <ul id="myTab" class="nav nav-tabs">
        <!-- Use uniqe name for "href" in below anchor tags -->
        <li class="active"><a href="#tab1" data-toggle="tab">Opis</a></li>
        <li><a href="#tab2" data-toggle="tab">Media</a></li>
        <li><a href="#tab3" data-toggle="tab">Komentari (5)</a></li>
    </ul>

    <!-- Tab Content -->
    <div id="myTabContent" class="tab-content">
        <!-- Description -->
        <div class="tab-pane fade in active" id="tab1">
            <?php if (!empty($product->productdescription->opisproizvoda)) echo $product->productdescription->opisproizvoda; ?>

        </div>

        <!-- Sepcs -->
        <div class="tab-pane fade" id="tab2">
            <?php if (!empty($product->productdescription->youtube)) echo $product->productdescription->youtube; ?>

        </div>

        <!-- Review -->
        <div class="tab-pane fade" id="tab3">
            <div class="item-review">
                <h5>Ravi Kumar - <span class="color">4/5</span></h5>

                <p class="rmeta">27/1/2012</p>

                <p>Suspendisse potenti. Morbi ac felis nec mauris imperdiet fermentum. Aenean sodales augue ac lacus
                    hendrerit sed rhoncus erat hendrerit. Vivamus vel ultricies elit. Curabitur lacinia nulla vel tellus
                    elementum non mollis justo aliquam.</p>
            </div>

            <hr/>
            <h5><strong>Write a Review</strong></h5>
            <hr/>
            <form role="form">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter Name">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="rating">Rating</label>
                    <!-- Dropdown menu -->
                    <select class="form-control">
                        <option>Rating</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Review</label>
                    <textarea class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-info">Send</button>
                <button type="reset" class="btn btn-default">Reset</button>
            </form>

        </div>

    </div>

</div>

<div class="col-md-3 col-md-pull-9">

    @include('frontend::_partials.category_menu')

    <!-- Sidebar items (featured items)-->

    <div class="sidebar-items">

                        <h5>Izdvajamo</h5>

                        <?php if (!empty($featured)): ?>
                            <?php foreach ($featured as $featured_item): ?>

                                <div class="sitem">
                                    <div class="onethree-left">
                                        <!-- Image -->

                                        <?php if (isset($featured_item->productDescription->slika) && isset($featured_item->category->group_name) && is_file(public_path() . '/slike/' . $featured_item->category->group_name . '/' . $featured_item->productDescription->slika)): ?>
                                            <a href="<?php echo route('product', array('slug' => Str::slug($featured_item->proizvod), 'id' => $featured_item->proizvod_id)); ?>">
                                                <img src="<?php echo url('slike/' . $featured_item->category->group_name . '/' . $featured_item->productDescription->slika); ?>" alt="" class="img-responsive"/>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo route('product', array('slug' => Str::slug($featured_item->proizvod), 'id' => $featured_item->proizvod_id)); ?>">
                                                <img src="<?php echo url(frontend_asset('img/no-image100.jpg')); ?>" alt="" class="img-responsive"/>
                                            </a>
                                        <?php endif; ?>


                                    </div>
                                    <div class="onethree-right">
                                        <!-- Title -->
                                        <a href="<?php echo route('product', array('slug' => Str::slug($featured_item->proizvod), 'id' => $featured_item->proizvod_id)); ?>">
                                            <?php echo $featured_item->proizvod; ?>
                                        </a>

                                        <p class="bold"><?php echo cena($featured_item->cena_prodajna); ?> din.</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
</div>
</div>

<div class="sep-bor"></div>
</div>
</div>

@section('footerScripts')
@parent
<script type="text/javascript">
    $(document).ready(function () {
        $('.bxslider').bxSlider({
            slideMargin: 10,
            autoReload: true,
            breaks: [
                {screen: 0, slides: 1, pager: false},
                {screen: 460, slides: 1}
            ],
            captions: true
        });

        $(".fancybox").fancybox({

        });

    });
</script>
@stop