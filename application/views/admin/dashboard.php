 <div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?= base_url("admin"); ?>">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>Dashboard</span>
        </li>
    </ul> 
</div>
<br/>

<div class="row widget-row">
    <div class="col-md-12">
        <div class="note note-success">
            <p><?=TITLE;?> is amazing, cost effective and hassle free way to explore India and enjoy!</p>
        </div>
    </div>
    <div class="col-md-4">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
            <h4 class="widget-thumb-heading font-green">Parking Listing</h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-green icon-user"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-body-stat" ><?php echo $result['totalParking']; ?></span>
                </div>
                <a class="title" href="<?php echo base_url("manageScooter/view_parking_list"); ?>">More Info
                    <i class="icon-arrow-right"></i>
                </a>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
    <div class="col-md-4">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
            <h4 class="widget-thumb-heading font-green"><?=TITLE;?> Listing</h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-green icon-user"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-body-stat" ><?php echo $result['totalScooter']; ?></span>
                </div>
                <a class="title" href="<?php echo base_url("manageScooter"); ?>"> More Info
                    <i class="icon-arrow-right"></i>
                </a>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
    <div class="col-md-4">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
            <h4 class="widget-thumb-heading font-green"><?=TITLE;?> User Listing</h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-green icon-user"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-body-stat" ><?php echo $result['totalUser']; ?></span>
                </div>
                <a class="title" href="<?php echo base_url("manageUser"); ?>"> More Info
                    <i class="icon-arrow-right"></i>
                </a>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>

    

</div>
<div class="row widget-row">
    <div class="col-md-4">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
            <h4 class="widget-thumb-heading font-green">Live Tracking </h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-green icon-trophy"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-body-stat" ><?php echo $result['totalOnRideScooter']; ?></span>
                </div>
                <a class="title" href="<?php echo base_url("ManageMap/view_running_scooter"); ?>"> More Info
                    <i class="icon-arrow-right"></i>
                </a>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
    
    <div class="col-md-4">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
            <h4 class="widget-thumb-heading font-green">Staff Management</h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-green icon-settings"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-body-stat" ><?php echo $result['totalMaintUser']; ?></span>
                </div>
                <a class="title" href="<?php echo base_url('manageMaintenance'); ?>"> More Info
                    <i class="icon-arrow-right"></i>
                </a>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
    
</div>  
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">


        </div>
    </div>
</div>



