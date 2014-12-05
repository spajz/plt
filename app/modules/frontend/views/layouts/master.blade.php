<!DOCTYPE html>
<html lang="en">
<head>
<title><?php @print $page_title ? : 'Plutos'; ?></title>
<meta charset="utf-8"/>
<meta name="description" content=""/>
<meta name="keywords" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,600italic,600&subset=latin,latin-ext' rel='stylesheet' type='text/css'>

{{ HTML::style('packages/module/frontend/assets/css/bootstrap.min.css') }}
{{ HTML::style('packages/module/frontend/assets/css/animate.min.css') }}
{{ HTML::style('packages/module/frontend/assets/css/ddlevelsmenu-base.css') }}
{{ HTML::style('packages/module/frontend/assets/css/ddlevelsmenu-topbar.css') }}
{{ HTML::style('packages/module/frontend/assets/css/jquery.countdown.css') }}
{{ HTML::style('packages/module/frontend/assets/css/font-awesome.min.css') }}
{{ HTML::style('packages/module/frontend/assets/css/multi-columns-row.css') }}
{{ HTML::style('packages/module/frontend/assets/css/jquery.bxslider.css') }}
{{ HTML::style('packages/module/frontend/assets/css/ui.fancytree.min.css') }}
{{ HTML::style('packages/module/frontend/assets/css/fancybox/jquery.fancybox.css') }}

{{ HTML::style('packages/module/frontend/assets/css/style.css') }}
{{ HTML::style('packages/module/frontend/assets/css/added.css') }}

<script type="text/javascript">
    var baseUrl = '<?php echo rtrim(url(''), '/').'/'; ?>';
    var assetUrl = '<?php echo rtrim(url('packages/module/frontend/assets'), '/').'/'; ?>';
</script>

@section('headScripts')
@show

</head>

<body>
<div id="fb-root"></div>
<script>
    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=224328891068637";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<div id="notification-box"><?php //echo Notification::show(); ?></div>

{{ $cart or '' }}

{{ $header or '' }}

{{ $title or '' }}

{{ $content or '' }}

{{ $bannerHot or '' }}

{{ $bannerNewsletter or '' }}

{{ $bannerSocial or '' }}

{{ $footer or '' }}

<!-- Scroll to top -->
<span class="totop"><a href="#"><i class="icon-chevron-up"></i></a></span>

{{ HTML::script('packages/module/frontend/assets/js/jquery.js') }}
{{ HTML::script('packages/module/frontend/assets/js/jquery-ui.min.js') }}
{{ HTML::script('packages/module/frontend/assets/js/jquery.cookie.js') }}
{{ HTML::script('packages/module/frontend/assets/js/bootstrap.min.js') }}
{{ HTML::script('packages/module/frontend/assets/js/ddlevelsmenu.js') }}
{{ HTML::script('packages/module/frontend/assets/js/jquery.carouFredSel-6.2.1-packed.js') }}
{{ HTML::script('packages/module/frontend/assets/js/jquery.countdown.min.js') }}

{{ HTML::script('packages/module/frontend/assets/js/navgoco/jquery.cookie.min.js') }}
{{ HTML::script('packages/module/frontend/assets/js/jquery.navgoco.min.js') }}

{{ HTML::script('packages/module/frontend/assets/js/filter.js') }}
{{ HTML::script('packages/module/frontend/assets/js/respond.min.js') }}
{{ HTML::script('packages/module/frontend/assets/js/html5shiv.js') }}
{{ HTML::script('packages/module/frontend/assets/js/ie-row-fix.js') }}
{{ HTML::script('packages/module/frontend/assets/js/jquery.bxslider-rahisified.js') }}
{{ HTML::script('packages/module/frontend/assets/js/jquery.fancytree-all.js') }}
{{ HTML::script('packages/module/frontend/assets/js/jquery.fancytree.persist.js') }}
{{ HTML::script('packages/module/frontend/assets/js/jquery.fancybox.pack.js') }}

{{ HTML::script('packages/module/frontend/assets/js/custom.js') }}
{{ HTML::script('packages/module/frontend/assets/js/added.js') }}


<script type="text/javascript">
    $(document).ready(function () {

        $('.nav').navgoco({
            onClickBefore: function (e, submenu) {
                console.log($(e.target).attr('class'));
                alert(e.target.nodeName);
                alert('Clicked on ' + (submenu === false ? 'leaf' : 'branch') + ' `' + $(this).text() + '`');
            }
        });

        function setInfo($data) {
            $('#notification-box').html($data.msg);
            $('#notification-box').slideDown();
            $('.total-items').text($data.total_items);
            $('.total-quantity').text($data.total_quantity);
            $('.total-price').text($data.total_price);
        }

        function addToCart($href) {
            $.ajax({
                type: 'get',
                url: $href,
                dataType: 'json',
                success: function ($data) {
                    setInfo($data);
                }
            });
        }

        $('.add-to-cart-single').on('click', function (e) {
            e.preventDefault();

            var $quantity = $('.quantity').val();

            addToCart($(this).attr('href') + '/' + $quantity);
        })

        $('.add-to-cart').on('click', function (e) {
            e.preventDefault();
            addToCart($(this).attr('href'));
        })


    });
</script>

<script type="text/javascript">
    (function () {
        var po = document.createElement('script');
        po.type = 'text/javascript';
        po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(po, s);
    })();
</script>

@section('footerScripts')
@show

</body>
</html>