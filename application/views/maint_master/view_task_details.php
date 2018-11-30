<div class="row">
    <div class="col-md-12">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?= base_url("admin"); ?>">Home</a>
                    <i class="fa fa-circle"></i>
                </li> 
                <li>
                    <a href="<?= base_url("manageMaintenance/view_user_task"); ?>">User Tasks List</a>
                    <i class="fa fa-circle"></i>
                </li> 
                <li>
                    <span>Task Details</span>
                </li>
            </ul> 
        </div>
        <br/>

        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-eye font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">Task Details</span>
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
                                    <div class="profile-usertitle-name"> <?php echo ucwords($userDetails['userName']); ?>  </div>
                                </div>

                            </div>
                            <!-- END PORTLET MAIN -->
                            <!-- PORTLET MAIN -->

                            <!-- STAT -->
                            
                <table id="example" class="table table-striped table-bordered table-hover table-checkable">

                                <tbody>
                                    <tr>

                                        <td class="numeric"> <strong>Email : </strong> </td>
                                        <td class="numeric"><?php echo $userDetails['email']; ?> </td>

                                    </tr>
                                    <tr>

                                        <td class="numeric"> <strong>Mobile : </strong> </td>
                                        <td class="numeric"><?php echo $userDetails['mobile']; ?> </td>

                                    </tr>

                                    <tr>

                                        <td class="numeric"> <strong>Status : </strong></td>
                                        <td class="numeric">
                                            <?php 
                                           if ($userDetails['status'] == ACTIVE) {
                                                echo 'Unblocked';
                                            } else {
                                                echo 'Blocked';
                                            }
                                            ?> </td>

                                    </tr>
                                   <tr>
                                       <td class="numeric"> <strong>Completed Task : </strong> </td>
                                        <td class="numeric"><?php echo $userDetails['userCompletedTaskDetails']; ?> </td>
                                    </tr>
                                     <tr>
                                         <td class="numeric"> <strong> Uncompleted Task : </strong> </td>
                                        <td class="numeric"><?php echo $userDetails['userUncompleteTaskDetails']; ?> </td>
                                    </tr>
                                    <tr>
                                        <td class="numeric"> <strong> Completed Time Spent : </strong></td>
                                        <td class="numeric"><?php echo $userDetails['timeComplet']; ?> </td>
                                    </tr>
                                    <tr>
                                        <td class="numeric"> <strong> Uncompleted Time Spent : </strong></td>
                                        <td class="numeric"><?php echo $userDetails['timeUncomplet']; ?> </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row list-separated profile-stat">
                                <div class="form-group" style="text-align: center;">
                                    <?php if($userDetails['status'] == ACTIVE){ ?>
                                    <a class="btn btn-danger  btn-sm mt-repeater-add userStatus" data-repeater-create data-action-title="Block" data-action-desc="Are you sure want to block user?" data-id="<?php echo $userDetails['userId']; ?>" data-value="<?php echo NOTACTIVE; ?>" data-url="<?php echo base_url(); ?>ManageUser/userStatus" data-toggle="modal" href="javascript:void(0)" >
                                        <i class="fa fa-ban"></i> Block User
                                    </a>
                                     <?php }else{ ?>
                                    <a class="btn btn-success btn-sm mt-repeater-add userStatus" data-repeater-create data-action-title="Unblock" data-action-desc="Are you sure want to unblock user?" data-id="<?php echo $userDetails['userId']; ?>" data-value="<?php echo ACTIVE; ?>" data-url="<?php echo base_url(); ?>ManageUser/userStatus" data-toggle="modal" href="javascript:void(0)" >
                                        <i class="fa fa-check-circle"></i> Unblock User
                                    </a>
                                    <?php } ?>
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
                            <div class="caption">
                                <i class=" icon-list font-dark"></i>
                                <span class="caption-subject font-dark sbold uppercase">User's Current Tasks</span>
                                
                            </div>
                            <!--<div class="text-right"><small>Note: Page will get refreshed by each 10 sec...</small></div>-->
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
                                        <th> Scooter Number </th>
                                        <th> Task </th>
                                        <th> Task Status</th>
                                        <th> Action </th>
                                    </tr>
                                </thead>

                                <tbody>
                                     <?php foreach ($userDetails['tsakDetails'] as $key => $task) { ?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $key + 1; ?></td>
                                            <td><?php echo date('d-m-Y g:i a', strtotime($task['assignDate'])) ?></td>
                                            <td> <?php echo $task['scooterNumber'] ?></td>
                                            <td> <?php echo $task['issueTitle'] ?></td>
                                            <td> <?php echo $task['maintStatus'] ?></td>
                                            <td> 
                                                <a href="javascript:void(0)" class="btn btn-success userList" data-scooteNumber="<?php echo $task['scooterNumber']; ?>"  data-userId="<?php echo $userDetails['userId']; ?>" data-issueTitle="<?php echo $task['issueTitle']; ?>" data-id="<?php //echo $task['id']    ?>" title="Reassign">
                                                    <i class="fa fa-retweet"></i>
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

<div id="userlist" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="width: 1000px">
        <div class="modal-content" >
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><i class="fa fa-users"></i> Maintenance User List</h4>
            </div>
            <div class="modal-body">
                <div class="scroller" style="height:500px" data-always-visible="1" data-rail-visible="1">
                    <div class="portlet-body flip-scroll">
                        <table id="example" class="display table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th> Sr.No </th>
                                    <th> User Name </th>
                                    <th> Mobile </th>
                                    <th> Email </th>
                                    <th> Pending Task </th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody id="userdata">


                            </tbody>

                            </tbody>
                        </table>
                    </div> 
                </div></div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="assigntask" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="width: 30%">
        
        <div class="modal-content">
            <form method="post" action="<?php echo base_url("manageMaintenance/addAssignTask"); ?>" class="addservicesForm" enctype="multipart/form-data">

                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title"><i class="fa fa-list"> </i> Task Category</h4> 
                </div>
                <div class="modal-body" >
                    <label for="form_control_1">Please Select Task Category</label>
                    <div class="form-group form-md-radios has-success">

                        <input type="hidden" name="scooterNumber" value="" id="scooteNumber">
                        <input type="hidden" name="userId" value="" id="userId">
                        <input type="hidden" name="uncomplete" value="complete">
                        <div class="md-radio-list" style="margin-left: 20px">
                            <div class="md-radio">
                                <input type="radio" id="checkbox2_6" name="categoryId" class="category_1" value="" checked="">
                                <label for="checkbox2_6">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span> <label class="cat_1" style="padding-left: 0px;">Option 1 </label> </label>
                            </div>
                            <div class="md-radio">
                                <input type="radio" id="checkbox2_7" name="categoryId" class="category_2" value="">
                                <label for="checkbox2_7" class="cat2">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span>  <label class="cat_2" style="padding-left: 0px;">Option 2 </label> </label>
                            </div>
                            <div class="md-radio">
                                <input type="radio" id="checkbox2_8" name="categoryId" class="category_3" value="">
                                <label for="checkbox2_8">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span> <label class="cat_3" style="padding-left: 0px;"> Option 3  </label></label>
                            </div>
                            <div class="md-radio">
                                <input type="radio" id="checkbox2_9" name="categoryId" class="category_4" value="">
                                <label for="checkbox2_9">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span>  <label class="cat_4" style="padding-left: 0px;">Option 4  </label></label>
                            </div>
                            <div class="md-radio">
                                <input type="radio" id="checkbox2_10" name="categoryId" class="category_5" value="">
                                <label for="checkbox2_10">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span> <label class="cat_5" style="padding-left: 0px;"> Option 5  </label></label>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">                    
                    <button type="submit" class="btn btn-success btn-sm">Assign</button>
                    <button type="button" data-dismiss="modal" class="btn btn-danger btn-sm">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="actionModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><i class="fa fa-warning"></i> <span class="actionEventTitle"></span> <?php //echo $pagetitle;   ?></h4>
            </div>
            <div class="modal-body">
                <h4><span class="actionEventDesc"></span></h4>
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
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><i class="fa fa-warning"> Warning</h4>
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
<script>
//setInterval(function () { window.location.reload(); }, 10000);    
</script>