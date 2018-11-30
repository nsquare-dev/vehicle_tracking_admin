                  

<div class="row">
    <div class="col-md-12">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?= base_url("admin"); ?>">Home</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="<?= base_url("manageMaintenance"); ?>">Manage Staff</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Staff Details</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-green">
                    <i class="icon-eye "></i>
                    <span class="caption-subject bold uppercase">Staff Details</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light portlet-fit portlet-datatable bordered">
                        <div class="portlet-body ">
                            <div class="profile-sidebar-portlet">
                                <!-- SIDEBAR USERPIC -->
                                <div class="profile-userpic text-center">
                                    <img src="<?php echo $userDetails['profileImage']; ?>" onerror="this.src='<?php echo base_url(); ?>resource/default/default_profile.png'" class="img-thumbnail img-responsive" height="100px"  alt="">
                                </div>

                                <div class="profile-usertitle">
                                    <div class="profile-usertitle-name"> <?php echo ucwords($userDetails['userName']); ?>  </div>
                                </div>

                                <div class="table-responsive">
                                    <!-- STAT -->
                                    <table class="table table-striped table-condensed flip-content">
                                        <tbody>
                                            <tr>
                                                <td > <strong>Email :</strong> </td>
                                                <td ><?php echo $userDetails['email']; ?> </td>
                                            </tr>
                                            <tr>
                                                <td > <strong>Mobile : </strong> </td>
                                                <td ><?php echo $userDetails['mobile']; ?> </td>
                                            </tr>

                                            <tr>
                                                <td > <strong>Status :</strong></td>
                                                <td ><?php
                                                    if ($userDetails['status'] == ACTIVE) {
                                                        echo 'Unblocked';
                                                    } else {
                                                        echo 'Blocked';
                                                    }
                                                    ?> </td>
                                            </tr>
                                            <tr>
                                                <td > <strong>Completed Task : </strong> </td>
                                                <td ><?php echo $userDetails['userCompletedTaskDetails']; ?> </td>
                                            </tr>
                                            <tr>
                                                <td > <strong> Incompleted Task : </strong> </td>
                                                <td ><?php echo $userDetails['userUncompleteTaskDetails']; ?> </td>
                                            </tr>
                                            <tr>
                                                <td > <strong>Completed Time Spent :</strong></td>
                                                <td ><?php echo $userDetails['timeComplet']; ?> </td>
                                            </tr>
                                            <tr>
                                                <td > <strong>Incompleted  Time Spent :</strong></td>
                                                <td ><?php echo $userDetails['timeUncomplet']; ?> </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- END PORTLET MAIN -->
                            <!-- PORTLET MAIN -->


                            <div class="row list-separated profile-stat">
                                <div class="form-group" style="text-align: center;">
                                    <?php if ($userDetails['status'] == ACTIVE) { ?>
                                        <a class="btn btn-danger mt-repeater-add userStatus" data-repeater-create data-action-title="Block" data-action-desc="Are you sure want to block user?" data-id="<?php echo $userDetails['userId']; ?>" data-value="<?php echo NOTACTIVE; ?>" data-url="<?php echo base_url("ManageUser/userStatus"); ?>" data-toggle="modal" href="javascript:void(0)" >
                                            <i class="fa fa-ban"></i> Block User
                                        </a>
                                    <?php } else { ?>
                                        <a class="btn btn-success mt-repeater-add userStatus" data-repeater-create data-action-title="Unblock" data-action-desc="Are you sure want to unblock user?" data-id="<?php echo $userDetails['userId']; ?>" data-value="<?php echo ACTIVE; ?>" data-url="<?php echo base_url("ManageUser/userStatus"); ?>" data-toggle="modal" href="javascript:void(0)" >
                                            <i class="fa fa-check-circle"></i> Un-Block User
                                        </a>
                                    <?php } ?>
                                </div>

                            </div>



                        </div>

                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
                <div class="col-md-8 col-sm-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light portlet-fit portlet-datatable bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class=" icon-list font-dark"></i>
                                <span class="caption-subject font-dark sbold uppercase">Task History</span>
                            </div>

                        </div>
                        <div class="portlet-body">
                             <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered table-hover table-checkable">
                                <thead>
                                    <tr>
                                        <th class="table-checkbox">
                                            Sr.No
                                        </th>
                                        <th> Date</th>
                                        <th> Scooter Id </th>
                                        <th> Task </th>
                                        <th> Task Status </th>
                                        <th> Action </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($userDetails['taskDetails'] as $key => $task) { ?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $key + 1; ?></td>
                                            <td><?php echo date('d-m-Y H:i a', strtotime($task['assignDate'])) ?></td>
                                            <td> <?php echo $task['scooterNumber'] ?></td>
                                            <td> <?php echo $task['issueTitle'] ?></td>
                                            <td> <?php
                                                if ($task['maintStatus'] == MAINTPENDING) {
                                                    echo 'Pending';
                                                } else if ($task['maintStatus'] == MAINTPROGRESS) {
                                                    echo 'Progress';
                                                } else if ($task['maintStatus'] == MAINTCANCEL) {
                                                    echo 'Reassigned to another';
                                                } else {
                                                    if ($task['istaskcomplete'] == TASKCOMPLETEYES) {
                                                        echo 'Completed';
                                                    } else {
                                                        echo 'Uncompleted';
                                                    }
                                                }
                                                ?></td>
                                            <td> 
                                                <a href="javascript:void(0)" class="btn btn-success maintananceDetails" data-id="<?php echo $task['id'] ?>" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
        </div>
    </div>
</div>

<div id="details" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="width: 1000px">
        <div class="modal-content" >
            <div class="modal-header  bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><span class="caption-subject bold uppercase"><i class="fa fa-users"></i> Staff Task Details</span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-3">
                        <h5 ><span class="caption-subject font-black-madison bold">Date:</span> <span id="startDate"></span></h5>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3">
                        <h5><span class="caption-subject font-black-madison bold">Scooter Id: </span><span id="scooterNumber"></span></h5>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3">
                        <h5><span class="caption-subject font-black-madison bold">Task:</span> <span id="issueTitle"></span></h5>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3">
                        <h5><span class="caption-subject font-black-madison bold">Time Spent:</span> <span id="timeSpent"></span></h5>
                    </div>
                    <!-- /.col -->
                </div><br><br>
                <div class="row">
                    <div class="col-sm-12">
                        <label ><span class="caption-subject font-black-madison bold">Location: </span><span id="scooterLocation"></span></label>
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h5><span class="caption-subject font-black-madison bold">User Comment:</span> </h5>
                        <p id="comment"></p>
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <img class="prod-img" id="img1" onerror="this.src='<?php echo base_url(); ?>resource/default/placeholder.png'" height="100px" width="200px">
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3">
                        <img class="prod-img" id="img2" onerror="this.src='<?php echo base_url(); ?>resource/default/placeholder.png'" height="100px" width="200px">
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3">
                        <img class="prod-img" id="img3" onerror="this.src='<?php echo base_url(); ?>resource/default/placeholder.png'" height="100px" width="200px">
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3">
                        <img class="prod-img" id="img4" onerror="this.src='<?php echo base_url(); ?>resource/default/placeholder.png'" height="100px" width="200px">
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>

            </div>
        </div>
    </div>
</div>
<div id="actionModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header bg-warning">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">
                    <i class="fa fa-warning"></i> Warning
                </h4>
            </div>
            <div class="modal-body">

                <h4> <span class="actionEventDesc"></span></h4>
            </div>
            <div class="modal-footer">

                <a href="" type="button" class="btn btn-success confirmAction">Yes <span class="actionEvent"></span></a>
                <button type="button" data-dismiss="modal" class="btn  btn-danger">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="erroractionModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header bg-danger">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">
                    <i class="fa fa-warning"></i> Warning
                </h4>
            </div>
            <div class="modal-body">
                 <h4><span class="actionEventDesc"></span></h4>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
            </div>
        </div>
    </div>
</div>