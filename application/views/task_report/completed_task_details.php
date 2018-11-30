                  

<div class="row">
    <div class="col-md-12">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?= base_url("admin"); ?>">Home</a>
                    <i class="fa fa-circle"></i>
                </li> 
                <li>
                    <a href="<?= base_url("ManageReport/view_completed_task"); ?>">Completed Task</a>
                    <i class="fa fa-circle"></i>
                </li> 
                <li>
                    <span>Task Report</span>
                </li>
            </ul> 
        </div>
        <br/>

        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption  font-green">
                    <i class="icon-eye"></i>
                    <span class="caption-subject bold uppercase">Task Report</span>
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
                                    <img src="<?php echo $userDetails['profileImage']; ?>" onerror="this.src='<?php echo base_url("resource/default/default_profile.png"); ?>'" class="img-thumbnail img-responsive" height="100px" alt=""> </div>

                                <div class="profile-usertitle">
                                    <div class="profile-usertitle-name"> <?php echo $userDetails['userName']; ?>  </div>
                                </div>

                            </div>
                            <!-- END PORTLET MAIN -->
                            <!-- PORTLET MAIN -->

                            <!-- STAT -->
                            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered table-hover table-checkable">

                                <tbody>
                                    <tr>

                                        <td class="numeric"> <strong>Email :</strong> </td>
                                        <td class="numeric"><?php echo $userDetails['email']; ?> </td>

                                    </tr>
                                    <tr>

                                        <td class="numeric"> <strong>Mobile : </strong> </td>
                                        <td class="numeric"><?php echo $userDetails['mobile']; ?> </td>

                                    </tr>

                                    <tr>

                                        <td class="numeric"> <strong> Status : </strong></td>
                                        <td class="numeric"><?php
                                            if ($userDetails['status'] == ACTIVE) {
                                                echo 'Unblocked';
                                            } else {
                                                echo 'Blocked';
                                            }
                                            ?> </td>

                                    </tr>
                                    <tr>

                                        <td class="numeric"> <strong>Task Completed: </strong></td>
                                        <td class="numeric"><?php echo $userDetails['usertaskDetails']; ?> </td>

                                    </tr>
                                    <tr>

                                        <td class="numeric"> <strong>Time Spent: </strong></td>
                                        <td class="numeric"><?php echo $userDetails['totalTime']; ?> </td>

                                    </tr>
                                </tbody>
                            </table> 
                                </div>
                            <div class="row list-separated profile-stat">
                                <div class="form-group" style="text-align: center;">
                                    <?php if ($userDetails['status'] == ACTIVE) { ?>
                                        <a class="btn btn-danger mt-repeater-add userStatus" data-repeater-create data-action-title="Block" data-action-desc="Are you sure want to block user?" data-id="<?php echo $userDetails['userId']; ?>" data-value="<?php echo NOTACTIVE; ?>" data-url="<?php echo base_url(); ?>ManageUser/userStatus" data-toggle="modal" href="javascript:void(0)" >
                                            <i class="fa fa-ban"></i> Block User
                                        </a>
                                    <?php } else { ?>
                                        <a class="btn btn-success mt-repeater-add userStatus" data-repeater-create data-action-title="Unblock" data-action-desc="Are you sure want to unblock user?" data-id="<?php echo $userDetails['userId']; ?>" data-value="<?php echo ACTIVE; ?>" data-url="<?php echo base_url(); ?>ManageUser/userStatus" data-toggle="modal" href="javascript:void(0)" >
                                            <i class="fa fa-check-circle"></i> Unblock User
                                        </a>
                                    <?php } ?>

                                </div>
                                <div class="form-group" style="text-align: center;">
                                    <!--<input type="submit" class="btn btn-info mt-repeater-add actionModal" value="Assign Task" style="width: 107px;">-->

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
                            <div class="caption font-green">
                                <i class=" icon-list "></i>
                                <span class="caption-subject  sbold uppercase">Completed Task List</span>
                            </div>

                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_5">
                                <thead>
                                    <tr>
                                        <th class="table-checkbox">
                                            Sr.No
                                        </th>
                                        <th> Date</th>
                                        <th> Zeep Number </th>
                                        <th> Task </th>
                                        <th> Action </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    foreach ($userDetails['taskDetails'] as $key => $task) {
                                        if ($task['scooterStatus'] == NOTACTIVE) {
                                            ?>
                                            <tr class="odd gradeX">
                                                <td><?php echo $key + 1; ?></td>
                                                <td><?php echo date('d-m-Y g:i a', strtotime($task['assignDate'])) ?></td>
                                                <td><?php echo $task['scooterNumber'] ?></td>
                                                <td><?php echo $task['issueTitle'] ?></td>
                                                <td> 
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-success reportDetails" data-id="<?php echo $task['id'] ?>" title="View"> 
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-success mt-repeater-add actionModal2" data-repeater-create data-action-title="Active" data-action-desc="Are you sure want to activate this scooter?" data-id="<?php echo $task['scooterParkId']; ?>" data-value="<?php echo ACTIVE; ?>" data-url="<?php echo base_url("ManageReport/scooterStatus"); ?>" data-toggle="modal" href="javascript:void(0)" title="Set Activate" >
                                                        <i class="fa fa-check-circle"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
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
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><i class="fa fa-tasks"></i> Staff Task Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-3">
                        <h5 >Date: <span id="startDate"></span></h5>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3">
                        <h5>Scooter Id: <span id="scooterNumber"></span></h5>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3">
                        <h5>Task: <span id="issueTitle"></span></h5>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3">
                        <h5>Time Spent: <span id="timeSpent"></span></h5>
                    </div>
                    <!-- /.col -->
                </div><br><br>
                <div class="row">
                    <div class="col-sm-12">
                        <label >Location: <span id="scooterLocation"></span></label>
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h5>User Comment: </h5>
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
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><i class="fa fa-warning"></i> Warning</h4>
            </div>
            <div class="modal-body">

                <h4> <span class="actionEventDesc"></span></h4>
            </div>
            <div class="modal-footer">
                <a href="" type="button" class="btn btn-success confirmAction">Yes <span class="actionEvent"></span></a>
                <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>

            </div>
        </div>
    </div>
</div>
<div id="erroractionModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><i class="fa fa-warning"></i> Warning</h4>
            </div>
            <div class="modal-body">
                <h4><span class="actionEventDesc"></span></h4>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn default">Close</button>
            </div>
        </div>
    </div>
</div>
