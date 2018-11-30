<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->

    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->

        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>
            <!-- END SIDEBAR TOGGLER BUTTON -->

            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'dashboard') ? 'active' : ''; ?>">
                <a href="<?=base_url("admin"); ?>" class="nav-link nav-toggle">
                    <i class="fa fa-dashboard"></i>
                    <span class="title">Dashboard</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'view_parking') ? 'active' : ''; ?>">
                <a href="<?=base_url("manageScooter/view_parking_list"); ?>" class="nav-link nav-toggle">
                    <i class="fa fa fa-map-o"></i>
                    <span class="title">Manage Parking</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'view_restricted_parking') ? 'active' : ''; ?>">
                <a href="<?=base_url("manageScooter/view_restricted_parking_list"); ?>" class="nav-link nav-toggle">
                    <i class="fa fa-ban"></i>
                    <span class="title">Manage Restricted Area</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'view_scooter') ? 'active' : ''; ?>">
                <a href="<?=base_url("manageScooter"); ?>" class="nav-link nav-toggle">
                    <i class="fa fa-bicycle"></i>
                    <span class="title">Manage Scooto</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'view_user') ? 'active' : ''; ?>">
                <a href="<?=base_url("manageUser"); ?>" class="nav-link nav-toggle">
                    <i class="fa fa-users"></i>
                    <span class="title">Manage User</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'view_maint_user') ? 'active' : ''; ?>">
                <a href="<?=base_url("manageMaintenance"); ?>" class="nav-link nav-toggle">
                    <i class="fa fa-group"></i>
                    <span class="title">Manage Staff</span>
                    <span class="selected"></span>
                </a>
            </li>
            <!--Task Management -->
            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'task') ? 'active' : ''; ?>">
                <a href="<?=base_url("manageMaintenance/view_user_task"); ?>" class="nav-link nav-toggle">
                    <i class="fa fa-tasks"></i>
                    <span class="title">Manage Tasks</span>
                    <span class="selected"></span>
                </a>
            </li>
            <!--Task report -->
            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'taskreport') ? 'active' : ''; ?>">
                <a href="<?=base_url("ManageReport/view_completed_task"); ?>" class="nav-link nav-toggle">
                    <i class="fa fa-flag"></i>
                    <span class="title">Manage Task Report</span>
                    <span class="selected"></span>
                </a>
            </li>


            <!--Instance Support-->
            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'Instance') ? 'active' : ''; ?>">
                <a href="<?=base_url("ManageInstant"); ?>" class="nav-link nav-toggle">
                    <i class="fa fa-support"></i>
                    <span class="title">Instant Support</span>
                    <span class="selected"></span>
                </a>
            </li>
            <!--Map -->
            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'Map') ? 'active' : ''; ?>">
                <a href="<?=base_url("ManageMap"); ?>" class="nav-link nav-toggle">
                    <i class="fa fa-map"></i>
                    <span class="title">View Map</span>
                    <span class="selected"></span>
                </a>
            </li>
            <!--Live Tracking -->
            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'live') ? 'active' : ''; ?>">
                <a href="<?=base_url("ManageMap/view_running_scooter"); ?>" class="nav-link nav-toggle">
                    <i class="fa fa-street-view"></i>
                    <span class="title">Live Tracking</span>
                    <span class="selected"></span>
                </a>
            </li>

            <!--Manage Manuals -->
            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'Manuals') ? 'active' : ''; ?>">
                <a href="<?=base_url("ManageManauals"); ?>" class="nav-link nav-toggle">
                    <i class="fa fa-cogs"></i>
                    <span class="title">Manage Manuals</span>
                    <span class="selected"></span>
                </a>
            </li>

            <!--Manage Knowledge -->
            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'Knowledge') ? 'active' : ''; ?>">
                <a href="<?=base_url("ManageKnowledge"); ?>" class="nav-link nav-toggle">
                    <i class="fa fa-cogs"></i>
                    <span class="title">Manage Knowledge Base</span>
                    <span class="selected"></span>
                </a>
            </li>
             <!--Manage Instruction -->
            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'Instruction') ? 'active' : ''; ?>">
                <a href="<?=base_url("ManageInstruction"); ?>" class="nav-link nav-toggle">
                    <i class="fa fa-cogs"></i>
                    <span class="title">Manage Instruction</span>
                    <span class="selected"></span>
                </a>
            </li>
            <!--Manage App -->
            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'manage_app') ? 'active' : ''; ?>">
                <a href="<?=base_url("appManagement"); ?>" class="nav-link nav-toggle">
                    <i class="fa fa-cogs"></i>
                    <span class="title">Manage App</span>
                    <span class="selected"></span>
                </a>
            </li>
            <!--Manage Promocode -->
            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'mgt_promocode') ? 'active' : ''; ?>">
                <a href="<?=base_url("ManagePromoCodes"); ?>" class="nav-link nav-toggle">
                    <i class="fa fa-money"></i>
                    <span class="title">Manage Promo Codes</span>
                    <span class="selected"></span>
                </a>
            </li>

            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'mgt_command') ? 'active' : ''; ?>">
                <a href="<?=base_url("command"); ?>" class="nav-link nav-toggle">
                    <i class="fa fa-cogs"></i>
                    <span class="title">Manage Command</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'svr_command') ? 'active' : ''; ?>">
                <a href="<?=base_url("command/command_list"); ?>" class="nav-link nav-toggle">
                    <i class="fa fa-cogs"></i>
                    <span class="title">Configure Devices</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'Notifications') ? 'active' : ''; ?>">
                <a href="<?=base_url('admin/notifications'); ?>" class="nav-link nav-toggle">
                    <i class="fa fa-bell-o"></i>
                    <span class="title">View Notifications</span>
                    <span class="selected"></span>
                </a>
            </li>
            
<!--            <li class="nav-item <?=(isset($top_menu) && $top_menu == 'Instance') ? 'active' : ''; ?>">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-home"></i>
        <span class="title">Instance Support</span>
        <span class="selected"></span>
        <span class="arrow open"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item <?=(isset($sub_menu) && $sub_menu == 'Instance') ? 'active' : ''; ?>">
            <a href="<?=base_url(); ?>user/user" class="nav-link ">
                <i class="icon-bar-chart"></i>
                <span class="title">Dashboard</span>
                <span class="selected"></span>
            </a>
        </li>
    </ul>
</li>-->





        </ul>
        <!-- END SIDEBAR MENU -->
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>
<!-- END SIDEBAR -->


<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">


        <?php if ($this->session->flashdata('success') != "") { ?>	
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                <strong>Success!</strong> <?=$this->session->flashdata('success'); ?>
            </div>		
        <?php } if ($this->session->flashdata('error') != "") { ?>	
            <div class="alert alert-error alert-danger">
                <button type="button" class="alert alert-error close" data-dismiss="alert" aria-hidden="true"></button>
                <strong>Error!</strong> <?=$this->session->flashdata('error'); ?>
            </div>			
        <?php } ?>							