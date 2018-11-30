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
        <link rel="shortcut icon" href="<?= base_url("assets/pages/img/favicon.ico"); ?>">
        <!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />-->
        <link href="<?php echo base_url("assets/global/plugins/font-awesome/css/font-awesome.min.css"); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/simple-line-icons/simple-line-icons.min.css"); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/bootstrap/css/bootstrap.min.css"); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css"); ?>" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->

        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?php echo base_url("assets/global/plugins/datatables/datatables.min.css"); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css"); ?>" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->

        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo base_url("assets/global/css/components.min.css"); ?>" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo base_url("assets/global/css/plugins.min.css"); ?>" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <link href="<?php echo base_url("assets/pages/css/profile.min.css"); ?>" rel="stylesheet" type="text/css" />
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?php echo base_url("assets/layouts/layout/css/layout.min.css"); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/layouts/layout/css/themes/green.min.css"); ?>" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?php echo base_url("assets/layouts/layout/css/custom.min.css"); ?>" rel="stylesheet" type="text/css" />

        <!-- jquery js -->
        <script src="<?php echo base_url("assets/global/plugins/jquery.min.js"); ?>" type="text/javascript"></script>
        
        <link href="<?php echo base_url("assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css"); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"); ?>" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <!--<link rel="shortcut icon" href="<?php echo base_url("assets/layouts/layout/img/icon.png"); ?>" /> </head>-->
        <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <div class="page-wrapper">
            <!-- BEGIN HEADER -->
            <div class="page-header navbar navbar-fixed-top">
                <!-- BEGIN HEADER INNER -->
                <div class="page-header-inner ">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <a href="<?= base_url("admin"); ?>">
                            <img src="<?= base_url("assets/pages/img/logo_white.png"); ?>" alt="logo" class="logo-default" width="100px" /> 
                            <!--<h4 style="color:white"> E-Scooter </h4>-->
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
                            <!-- BEGIN NOTIFICATION DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <i class="icon-bell"></i>
                                    <span class="badge badge-default" id="noti_cntr"> 0 </span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="external">
                                        <h3>
                                            <span class="bold" id="noti_cnt">0 pending</span> notifications
                                        </h3>
                                        <a href="<?= base_url('admin/notifications') ?>">view all</a>
                                    </li>
                                    <li>
                                        <ul class="dropdown-menu-list scroller" id="noti_content" style="height: 250px;" data-handle-color="#637283">


                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <!-- END NOTIFICATION DROPDOWN --> 

                            <!-- BEGIN USER LOGIN DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <li class="dropdown dropdown-user">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <?php if ($this->session->image != "") { ?>

                                        <img alt="" class="img-circle" src="<?php echo base_url("profile-img/"); ?><?php echo $this->session->image; ?>" alt="Profile image" onerror="this.src='<?php echo base_url("resource/default/default_profile.png"); ?>'" />
                                    <?php } else { ?>
                                        <img alt="" class="img-circle" src="<?php echo base_url("assets/layouts/layout/img/tanaji.jpg"); ?>" />
                                    <?php } ?>
                                    <span class="username username-hide-on-mobile"> <?php echo $this->session->name; ?> </span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">
                                    <li>
                                        <a href="<?php echo base_url("admin/profile"); ?>">
                                            <i class="icon-user"></i> My Profile </a>
                                    </li> 
                                    <li>

                                        <a class="logout" data-action-title="Logout" data-action-desc="Logout" data-url="<?php echo base_url("admin/logout"); ?>" data-toggle="modal" href="#" ><i class="icon-logout"></i> Log Out</a>

                                    </li>
                                </ul>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->

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