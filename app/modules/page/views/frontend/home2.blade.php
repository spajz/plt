<div class="recent-posts blocky">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @if(count($products))
                <ul class="bxslider">
                    @foreach($products as $product)

                    @if(array_get($product->images, 0))


                    <a href="#">
                        <img src="{{ url('media/product/images/large/' . $product->images[0]->image) }}" title="{{{ $product->description }}}">
                    </a>

                    @endif

                    @endforeach
                </ul>
                @endif

            </div>
        </div>
    </div>
</div>

@section('footerScripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('.bxslider').bxSlider({
            minSlides: 1,
            maxSlides: 2,
            slideWidth: 500,
            slideMargin: 10,
            captions: true
        });
    });
</script>
@stop

