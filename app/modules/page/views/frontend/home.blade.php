<!-- Breadcrumb -->
<div class="breadcrumb-wrap">
    <div class="container">
        &nbsp;
    </div>
</div>

<!-- Page title -->
<div class="page-title">
    <div class="container">
        <h1>Dobrodosli</h1>
        <hr/>
    </div>
</div>

<div class="shop-items">
    <div class="container">

        <div class="row">

            <div class="col-md-9 col-md-push-3">

                <!-- Items List starts -->

                <div class="row">
                    <div class="col-md-12">

                        @if(count($specials))
                        <ul class="bxslider">
                            @foreach($specials as $product)

                            @if(array_get($product->images, 0))

                            <li>

                                <a href="{{ route('product', array('slug' => Str::slug($product->proizvod), 'id' => $product->proizvod_id)) }}">
                                    <img src="{{ url('media/product/images/large/' . $product->images[0]->image) }}" title="{{{ $product->description }}}">
                                </a>

                            </li>

                            @endif

                            @endforeach
                        </ul>
                        @endif

                    </div>
                </div>


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


            </div>


            <div class="col-md-3 col-md-pull-9">

                @include('frontend::_partials.category_menu')

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
                {screen: 460, slides: 2}
            ],
            captions: true
        });
    });
</script>
@stop

