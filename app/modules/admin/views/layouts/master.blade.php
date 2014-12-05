<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo isset($title) ? $title : 'Admin'; ?></title>
<meta charset="utf-8"/>
<meta name="description" content=""/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<script type="text/javascript">
    var baseUrl = '<?php echo url(); ?>';
    var assetUrl = '<?php echo assets_admin(); ?>';
    var cmsLanguage = '<?php echo Session::get('admin_language'); ?>';
</script>

<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300"/>

{{ HTML::style('packages/module/admin/assets/css/bootstrap.min.css') }}
{{ HTML::style('packages/module/admin/assets/css/font-awesome.min.css') }}
{{ HTML::style('packages/module/admin/assets/css/ace.min.css') }}
{{ HTML::style('packages/module/admin/assets/css/ace-skins.min.css') }}
{{ HTML::style('packages/module/admin/assets/css/dynatree/ui.dynatree.css') }}
{{ HTML::style('packages/module/admin/assets/css/multi-columns-row.css') }}
{{ HTML::style('packages/module/admin/assets/css/jquery.fancybox.css') }}

{{ HTML::style('packages/module/admin/assets/css/jquery.Jcrop.min.css') }}
{{ HTML::style('packages/module/admin/assets/css/skin-win8/ui.fancytree.min.css') }}


{{ HTML::style('packages/module/admin/assets/css/added.css') }}

<!--[if IE 7]>
{{ HTML::style('packages/module/admin/assets/css/font-awesome-ie7.min.css') }}
<![endif]-->
<!--[if lte IE 8]>
{{ HTML::style('packages/module/admin/assets/css/ace-ie.min.css') }}
<![endif]-->

<?php  if(isset($head_scripts)) echo $head_scripts; ?>
@section('head_scripts')
@show
<?php // <link rel="stylesheet" href="assets/css/prettify.css"/>  ?>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
{{ HTML::script('packages/module/admin/assets/js/html5shiv.js') }}
{{ HTML::script('packages/module/admin/assets/js/respond.min.js') }}
<![endif]-->
</head>
<body>
<?php //var_dump(Session::all()); ?>
<div id="notification-box">
    <?php echo Msg::show(); ?>
</div>
<div id="ajax-loader">
    <img src="{{ assets_admin('img/loader.gif') }}">
</div>

<?php if(isset($navbar)) echo $navbar; ?>

<div class="main-container" id="main-container">
    <script type="text/javascript">
        try {
            ace.settings.check('main-container', 'fixed')
        } catch (e) {
        }
    </script>

    <div class="main-container-inner">
        <a class="menu-toggler" id="menu-toggler" href="#">
            <span class="menu-text"></span>
        </a>

        <?php if(isset($sidebar)) echo $sidebar; ?>

        <div class="main-content">
            @section('sidemenu_news_after')
                @show

            <?php if(isset($breadcrumbs)) echo $breadcrumbs; ?>

            <?php if(isset($content)) echo $content; ?>

            <?php // if(isset($settings)) echo $settings; ?>

        </div>
        <!-- /.main-content -->

    </div>
    <!-- /.main-container-inner -->

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="icon-double-angle-up icon-only bigger-110"></i>
    </a>
</div>
<!-- /.main-container -->

<!--[if !IE]>-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<!--<![endif]-->
<!--[if IE]>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<![endif]-->

<!--[if !IE]>-->
<script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo URL::to('packages/module/admin/assets/js/jquery-2.0.3.min.js'); ?>'>" + "<" + "/script>");
</script>
<!--<![endif]-->

<!--[if IE]-->
<script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo URL::to('packages/module/admin/assets/js/jquery-1.10.2.min.js'); ?>'>" + "<" + "/script>");
</script>
<![endif]-->

<script type="text/javascript">
    if ("ontouchend" in document) document.write("<script src='<?php echo URL::to('packages/module/admin/assets/js/jquery.mobile.custom.min.js'); ?>'>" + "<" + "/script>");
</script>

{{ HTML::script('packages/module/admin/assets/js/bootstrap.min.js') }}
{{ HTML::script('packages/module/admin/assets/js/typeahead-bs2.min.js') }}
{{ HTML::script('packages/module/admin/assets/js/jquery-ui-1.10.3.full.min.js') }}
{{ HTML::script('packages/module/admin/assets/js/jquery.dataTables.min.js') }}
{{ HTML::script('packages/module/admin/assets/js/jquery.dataTables.bootstrap.js') }}
{{ HTML::script('packages/module/admin/assets/js/ace-elements.min.js') }}
{{ HTML::script('packages/module/admin/assets/js/ace.min.js') }}
{{ HTML::script('packages/module/admin/assets/js/ace-extra.min.js') }}
{{ HTML::script('packages/module/admin/assets/ckeditor/ckeditor.js') }}
{{ HTML::script('packages/module/admin/assets/js/jquery.cookie.js') }}
{{ HTML::script('packages/module/admin/assets/js/dynatree/jquery.dynatree.min.js') }}
{{ HTML::script('packages/module/admin/assets/js/ie-row-fix.js') }}
{{ HTML::script('packages/module/admin/assets/js/jquery.fancybox.pack.js') }}
{{ HTML::script('packages/module/admin/assets/js/jquery.fancybox-media.js') }}

{{ HTML::script('packages/module/admin/assets/js/jquery.Jcrop.min.js') }}
{{ HTML::script('packages/module/admin/assets/js/jquery.color.js') }}
{{ HTML::script('packages/module/admin/assets/js/jquery.dataTables.columnFilter.js') }}
{{ HTML::script('packages/module/admin/assets/js/jquery.dataTables.filterClear.js') }}

{{ HTML::script('packages/module/admin/assets/js/dtPluginColumData.js') }}
{{ HTML::script('packages/module/admin/assets/js/jquery.fancytree-all.js') }}
{{ HTML::script('packages/module/admin/assets/js/jquery.pjax.js') }}

{{ HTML::script('packages/module/admin/assets/js/added.js') }}

<?php if(isset($footer_scripts)) echo $footer_scripts; ?>

@section('footer_scripts')
@show

</body>
</html>