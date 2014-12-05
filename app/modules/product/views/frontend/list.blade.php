<!-- Breadcrumb -->
<div class="breadcrumb-wrap">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?php echo url('/'); ?>">Početna</a> <span class="divider"></span></li>
            <li class="active"><?php echo $category->group_name ?></li>
        </ul>
    </div>
</div>

<!-- Page title -->
<div class="page-title">
    <div class="container">
        <h1><?php echo $category->group_name ?></h1>
        <hr/>
    </div>
</div>


<!-- Page content -->
<div class="shop-items">
    <div class="container">

        <div class="row">

            <div class="col-md-9 col-md-push-3">


                <!-- Category List starts -->

                @if(count($subCategories))

                <span class="label label-warning">Kategorije</span>

                <div class="row multi-columns-row">

                        @foreach ($subCategories as $subCategory)


                            <div class="col-md-3 col-sm-3 col-xs-12 col-lg-3">
                                <div class="item top-item">
                                    <!-- Use the below link to put HOT icon -->
                                    <?php //<div class="item-icon"><span>HOT</span></div> ?>
                                    <!-- Item image -->
                                    <div class="item-image">
                                        @if($subCategory->description)
                                            @if(is_file(public_path() . '/slike/'))
                                                <a href="{{ route('product.category', array('slug' => Str::slug($subCategory->group_name), 'id' => $subCategory->group_id)) }}">
                                                    <img src="{{ url('slike/' . $product->category->group_name . '/' . $product->productDescription->slika) }}" alt="" class="img-responsive"/>
                                                </a>
                                            @else
                                                <a href="{{ route('product.category', array('slug' => Str::slug($subCategory->group_name), 'id' => $subCategory->group_id)) }}">
                                                    <img src="{{ url(frontend_asset('img/no-image300.jpg')) }}" alt="" class="img-responsive"/>
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{ route('product.category', array('slug' => Str::slug($subCategory->group_name), 'id' => $subCategory->group_id)) }}">
                                                <img src="{{ url(frontend_asset('img/no-image300.jpg')) }}" alt="" class="img-responsive"/>
                                            </a>
                                        @endif
                                    </div>
                                    <!-- Item details -->
                                    <div class="item-details">
                                        <!-- Name -->
                                        <h5><a href="single-item.html">{{ $subCategory->group_name }}</a></h5>

                                        <div class="clearfix"></div>

                                    </div>
                                </div>
                            </div>

                        @endforeach

                </div>
                @endif


                <div class="clearfix"></div>
                <!-- Category List ends -->

                <span class='label label-success'>Proizvodi</span>

                <!-- Product List starts -->
                <div class="row multi-columns-row">

                    @if (!empty($products))

                        @foreach ($products as $product)
                            <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">
                                <div class="item">
                                    <!-- Use the below link to put HOT icon -->
                                    <?php //<div class="item-icon"><span>HOT</span></div> ?>
                                    <!-- Item image -->
                                    <div class="item-image">
                                         @if ($product->productDescription)
                                            @if (is_file(public_path() . '/slike/' . $product->category->group_name . '/' . $product->productDescription->slika))
                                                <a href="{{ route('product', array('slug' => Str::slug($product->proizvod), 'id' => $product->proizvod_id)) }}">
                                                    <img src="{{ url('slike/' . $product->category->group_name . '/' . $product->productDescription->slika) }}" alt="" class="img-responsive"/>
                                                </a>
                                            @else
                                                <a href="{{ route('product', array('slug' => Str::slug($product->proizvod), 'id' => $product->proizvod_id)) }}">
                                                    <img src="{{ url(frontend_asset('img/no-image300.jpg')) }}" alt="" class="img-responsive"/>
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{ route('product', array('slug' => Str::slug($product->proizvod), 'id' => $product->proizvod_id)) }}">
                                                <img src="{{ url(frontend_asset('img/no-image300.jpg')) }}" alt="" class="img-responsive"/>
                                            </a>
                                        @endif
                                    </div>
                                    <!-- Item details -->
                                    <div class="item-details">
                                        <!-- Name -->
                                        <h5><a href="single-item.html">{{ $product->proizvod }}</a></h5>

                                        <div class="clearfix"></div>

                                        <hr/>
                                        <!-- Price -->
                                        <div class="item-price pull-left">{{ cena($product->cena_prodajna) }}
                                            din.
                                        </div>
                                        <!-- Add to cart -->
                                        <div class="pull-right">
                                            <a class="btn btn-success btn-sm add-to-cart" href="{{ route('cart.add', array('id' => $product->proizvod_id, 'quantity' => 1)) }}">
                                                <i class="icon-shopping-cart"></i> U korpu
                                            </a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-md-4 col-sm-4 col-xs-6">
                            <div class="item">Nema proizvoda.</div>
                        </div>
                    @endif

                </div>

                <div class="clearfix"></div>
                <!-- Product List ends -->

                <div class="row">
                    <div class="col-md-12">
                        <?php echo $products->links(); ?>
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

                                    <?php if (isset($featured_item->productDescription->slika) && isset($featured_item->category->group_name)  && is_file(public_path() . '/slike/' . $featured_item->category->group_name . '/' . $featured_item->productDescription->slika)): ?>
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

@section('headScripts')
@parent
<style type="text/css">
    .bx-wrapper img {
        max-height: 150px;
    }
</style>
@stop

@section('footerScripts')
@parent
<script type="text/javascript">
    $(document).ready(function () {
        $('.bxslider').bxSlider({
            slideMargin: 10,
            autoReload: true,
            breaks: [
                {screen: 0, slides: 1, pager: false},
                {screen: 460, slides: 4}
            ],
            captions: true
        });
    });
</script>
@stop

