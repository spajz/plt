<!DOCTYPE html>
<html lang="en">
<head>
<title><?php @print $title ? : 'Admin'; ?></title>
<meta charset="utf-8"/>
<meta name="description" content="Admin"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300"/>

<!--[if IE 7]>
<?php /* admin-ie7 */ ?>
<![endif]-->
<!--[if lte IE 8]>
<?php /* admin-ie8 */ ?>
<![endif]-->

<?php @print $head_scripts; ?>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<?php /* admin-ie9 */ ?>
<![endif]-->
</head>
<body class="login-layout">
    <div id="notification-box"><?php echo Notification::showAll(); ?></div>
        <div class="main-container">
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="login-container">

                            <div class="space-6"></div>

                            <div class="position-relative">
                                <div id="login-box" class="login-box visible widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header blue lighter bigger">
                                                <i class="icon-coffee green"></i>
                                                Login
                                            </h4>

                                            <div class="space-6"></div>

                                            <?php echo Form::open(array('url' => 'admin/login', 'class' => 'form-vertical', 'id' => 'loginform')) ?>
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input name="email" type="text" class="form-control" placeholder="Username" />
                                                            <i class="icon-user"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input name="password" type="password" class="form-control" placeholder="Password" />
                                                            <i class="icon-lock"></i>
                                                        </span>
                                                    </label>

                                                    <div class="space"></div>

                                                    <div class="clearfix">
                                                        <?php
                                                        /*
                                                         <label class="inline">
                                                             <input type="checkbox" class="ace" />
                                                             <span class="lbl"> Remember Me</span>
                                                         </label>
                                                         */
                                                        ?>
                                                        <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                                                            <i class="icon-key"></i>
                                                            Login
                                                        </button>
                                                    </div>

                                                    <div class="space-4"></div>
                                                </fieldset>
                                            <?php echo Form::close() ?>

                                        </div><!-- /widget-main -->

                                    </div><!-- /widget-body -->
                                </div><!-- /login-box -->

                            </div><!-- /position-relative -->
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
        </div><!-- /.main-container -->



    <!--[if !IE]>-->
    <script type="text/javascript">
        window.jQuery || document.write("<script src='<?php echo URL::to('packages/spajz/admin/assets/js/jquery-2.0.3.min.js'); ?>'>" + "<" + "/script>");
    </script>
    <!--<![endif]-->

    <!--[if IE]-->
    <script type="text/javascript">
        window.jQuery || document.write("<script src='<?php echo URL::to('packages/spajz/admin/assets/js/jquery-1.10.2.min.js'); ?>'>" + "<" + "/script>");
    </script>
    <![endif]-->

    <script type="text/javascript">
        if ("ontouchend" in document) document.write("<script src='<?php echo URL::to('packages/spajz/admin/assets/js/jquery.mobile.custom.min.js'); ?>'>" + "<" + "/script>");
    </script>

    <?php /* admin-footer */ ?>

    <?php @print $footer_scripts; ?>

    <!-- inline scripts related to this page -->

</body>

</html>
