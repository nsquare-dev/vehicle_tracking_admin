<!DOCTYPE html>

<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title><?= TITLE ?>- <?= SHORT_DESC ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link rel="shortcut icon" href="resource/ico/favicon.png">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/font-awesome/css/font-awesome.min.css"); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/simple-line-icons/simple-line-icons.min.css"); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/bootstrap/css/bootstrap.min.css"); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css"); ?>" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?php echo base_url("assets/global/plugins/select2/css/select2.min.css"); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/select2/css/select2-bootstrap.min.css"); ?>" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo base_url("assets/global/css/components.min.css"); ?>" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo base_url("assets/global/css/plugins.min.css"); ?>" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="<?php echo base_url("assets/pages/css/login-2.min.css"); ?>" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="<?= base_url("assets/pages/img/favicon.ico"); ?>" /> </head>
    <!-- END HEAD -->

    <body class="login">
        <!-- BEGIN LOGO -->
        <div class="logo" >

            <div class="text-center">
                <a href="<?php echo base_url(); ?>">
                    <h1>
                        <img src="<?php echo base_url("assets/pages/img/logo_white.png"); ?>" alt="" />
                    </h1>                
                </a>
                <span class="label font-white text-primary">It's a smart move</span>
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content" style="margin-top: -15px;">
            <!-- BEGIN LOGIN FORM -->
            <?php
            $attributes = array('class' => 'login-form', 'id' => 'myform');
            echo form_open('admin/login', $attributes);
            ?>

            <h2 class="font-white text-center uppercase"><strong>Admin Login</strong></h2>
            <?php if ($this->session->flashdata('wrong') != "") { ?>	
                <div class="alert alert-danger alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <span><strong>Error! </strong> <?php echo $this->session->flashdata('wrong'); ?></span>
                </div>		
            <?php } ?>

            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label visible-ie8 visible-ie9">Username</label>
                <input class="form-control form-control-solid placeholder-no-fix no_space" type="email" autocomplete="off" placeholder="Enter email" name="username" value="<?php echo set_value('username'); ?>"/> 
            </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Password</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Enter password" name="password" value="<?php echo set_value('password'); ?>"/> 
            </div>
            <div class="form-actions">
                <!--<a href="javascript:void(0);" id="forget-password" class="forget-password">Forgot Password?</a> -->
                <button type="submit" class="btn btn-lg btn-flat green uppercase pull-right">Login</button>                     
            </div>

        </form>
        <!-- END LOGIN FORM -->
        <!-- BEGIN FORGOT PASSWORD FORM -->
        <form class="forget-form" action="" method="post">
            <h3 class="font-green">Forget Password ?</h3>
            <p> Enter your e-mail address below to reset your password. </p>
            <div class="form-group">
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" /> </div>
            <div class="form-actions">
                <button type="button" id="back-btn" class="btn green btn-outline">Back</button>
                <button type="submit" class="btn btn-success uppercase pull-right">Submit</button>
            </div>
        </form>
        <!-- END FORGOT PASSWORD FORM -->
        <!-- BEGIN REGISTRATION FORM -->
        <form class="register-form" action="<?php echo base_url(); ?>user/user/add" method="post">
            <h3 class="font-green">Sign Up</h3>
            <p class="hint"> Enter your personal details below: </p>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Full Name</label>
                <input class="form-control placeholder-no-fix" type="text" placeholder="Full Name" name="name" /> </div>
            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label visible-ie8 visible-ie9">Email</label>
                <input class="form-control placeholder-no-fix no_space" type="text" placeholder="Email" name="email" /> </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Phone</label>
                <input class="form-control placeholder-no-fix" type="text" placeholder="Phone number" name="phone" /> </div>	
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Address</label>
                <input class="form-control placeholder-no-fix" type="text" placeholder="Address" name="address" /> </div>

            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Password</label>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="Password" name="password" /> </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" name="rpassword" /> </div>
            <div class="form-group margin-top-20 margin-bottom-20">
                <label class="mt-checkbox mt-checkbox-outline">
                    <input type="checkbox" name="tnc" /> I agree to the
                    <a href="javascript:;">Terms of Service </a> &
                    <a href="javascript:;">Privacy Policy </a>
                    <span></span>
                </label>
                <div id="register_tnc_error"> </div>
            </div>
            <div class="form-actions">
                <button type="button" id="register-back-btn" class="btn green btn-outline">Back</button>
                <button type="submit" id="register-submit-btn" class="btn btn-success uppercase pull-right">Submit</button>
            </div>
        </form>
        <!-- END REGISTRATION FORM -->
    </div>
    <div class="copyright"> 2017-<?=date("Y")?> &copy; <?=WEB_ADD?></div>
    <!--[if lt IE 9]>
<script src="<?php echo base_url(); ?>assets/global/plugins/respond.min.js"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
    <!-- BEGIN CORE PLUGINS -->
    <script src="<?php echo base_url(); ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="<?php echo base_url(); ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <!--<script src="<?php echo base_url(); ?>assets/global/scripts/app.min.js" type="text/javascript"></script>-->
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <!--<script src="<?php echo base_url(); ?>assets/pages/scripts/login.min.js" type="text/javascript"></script>-->
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <!-- END THEME LAYOUT SCRIPTS -->
    <script>
        $(document).on("keypress", '.no_space', function (e) {
//e.preventDefault();   
//       $('input.nospace').keydown(function(e) {
            if (e.keyCode == 32) {
                return false;
            }
//});
        });
    </script>
</body>

</html>