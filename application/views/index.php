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
        <link rel="shortcut icon" href="resource/ico/favicon.png">
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/font-awesome/css/font-awesome.min.css"); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/simple-line-icons/simple-line-icons.min.css"); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/bootstrap/css/bootstrap.min.css"); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css"); ?>" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo base_url("assets/global/css/components.min.css"); ?>" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo base_url("assets/global/css/plugins.min.css"); ?>" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="<?php echo base_url("assets/pages/css/about.min.css"); ?>" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?php echo base_url("assets/layouts/layout/css/layout.min.css"); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/layouts/layout/css/themes/green.min.css"); ?>" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?php echo base_url("assets/layouts/layout/css/custom.min.css"); ?>" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" />

        <style>
            @media (min-width: 992px)
            {
                .page-content-wrapper .page-content 
                {
                    margin-left: 0px !important;
                    margin-top: 0;
                    min-height: 600px;
                    padding: 25px 20px 10px;
                }
                .page-sidebar-closed .page-content-wrapper .page-content {
                    margin-left: 0px!important;
                }
            }
        </style>
    </head>
    <!-- END HEAD -->
    <!-- page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white page-sidebar-closed-->
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white ">
        <div class="page-wrapper">
            <!-- BEGIN HEADER -->
            <div class="page-header navbar navbar-fixed-top">
                <!-- BEGIN HEADER INNER -->
                <div class="page-header-inner ">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <a href="index.html">
                            <img src="<?php echo base_url("assets/pages/img/logo_white.png"); ?>" alt="logo" class="logo-default" width="95px;"/> 

                        </a>
                        <div class="menu-toggler sidebar-toggler">
                            <span></span>
                        </div>
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                        <span></span>
                    </a>
                    <!-- END RESPONSIVE MENU TOGGLER -->
                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">


                            <!-- END INBOX DROPDOWN -->
                            <!-- BEGIN TODO DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <li class="dropdown dropdown-extended dropdown-tasks" id="header_task_bar">
                                <a href="<?php echo base_url("login"); ?>">
                                    <i class="icon-user"></i>  Login

                                </a>

                            </li>


                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END HEADER INNER -->
            </div>
            <!-- END HEADER -->
            <!-- BEGIN HEADER & CONTENT DIVIDER -->
            <div class="clearfix"> </div>
            <!-- END HEADER & CONTENT DIVIDER -->
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN SIDEBAR -->
                <div class="page-sidebar-wrapper">

                </div>
                <!-- END SIDEBAR -->
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">


                        <!-- BEGIN CONTENT HEADER -->
                        <div class="row margin-bottom-40 about-header">
                            <div class="col-md-12">
                                <h1><?= strtoupper(TITLE);?></h1>
                                <h2>Scooto is amazing, cost effective and hassle free way to explore India and enjoy! </h2>
                                <a href="<?php echo base_url(); ?>login"><button type="button" class="btn btn-danger">GO TO DASHBOARD</button></A>
                            </div>
                        </div>


                        <!-- END LINKS BLOCK -->
                    </div>
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->
                <!-- BEGIN QUICK SIDEBAR -->
                <a href="javascript:;" class="page-quick-sidebar-toggler">
                    <i class="icon-login"></i>
                </a>

                <!-- END QUICK SIDEBAR -->
            </div>
            <!-- END CONTAINER -->
            <!-- BEGIN FOOTER -->
            <div class="page-footer">
                <div class="page-footer-inner">  2017 &copy; E-SCOOTER
                </div>
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END FOOTER -->
        </div>
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
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?php echo base_url(); ?>assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?php echo base_url(); ?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>