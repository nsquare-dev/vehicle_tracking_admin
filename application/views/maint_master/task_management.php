<style>
    .nav-tabs.nav-justified > li >a{
        color: #337ab7;
    }
</style>
<!-- BEGIN : STEPS -->
<div class="row">
    <div class="col-md-12">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?= base_url("admin"); ?>">Home</a>
                    <i class="fa fa-circle"></i>
                </li> 
                <li>
                    <span>User Tasks List</span>
                </li>
            </ul> 
        </div>
        <br/>

        <div class="portlet light portlet-fit">
            <div class="portlet-title">
                <div class="caption font-green">
                    <i class="icon-list"></i>
                    <span class="bold uppercase">Task Management</span>
                </div>
            </div>
            <div class="portlet-body ">
                <ul class="nav nav-tabs nav-justified bg-success">
                    <li class="active">
                        <a href="<?php echo base_url("manageMaintenance/view_user_task"); ?>">User Tasks List</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url("manageMaintenance/view_admin_task"); ?>">Admin Tasks List</a>
                    </li>
                </ul> 

                <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered table-hover table-checkable">
                        <thead>
                            <tr>
                                <th> Sr.No </th>
                                <th> User Name </th>
                                <th> Mobile </th>
                                <th> Email </th>
                                <th> Total Task </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userDetails as $key => $user) { ?>
                                <tr>
                                    <td > <?php echo $key + 1; ?> </td>
                                    <td > <?php echo ucwords($user['userName'])?> </td>
                                    <td > <?php echo $user['mobile'] ?> </td>
                                    <td > <?php echo $user['email'] ?> </td>
                                    <td class="text-center"> <?php echo $user['count'] ?>  </td>
                                    <td > 
                                        <a href="<?php echo base_url("manageMaintenance/getMaintTaskdetails/"); ?><?php echo encode($user['userId']); ?>"  data-repeater-create class="btn btn-success mt-repeater-add" title="View Details">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>

                                </tr>
                            <?php } ?>
                        </tbody>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div> 

