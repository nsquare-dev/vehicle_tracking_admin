<div class="row">
    <div class="col-md-12">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?= base_url("admin"); ?>">Home</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="<?= base_url("manageUser"); ?>">Manage Parking</a>
                    <i class="fa fa-circle"></i>                    
                </li>
                <li>
                    <span>User Details</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-eye font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">User Details</span>
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
                                    <img src="<?php echo $userDetails['profileImage']; ?>" onerror="this.src='<?php echo base_url("resource/default/default_profile.png"); ?>'" class="img-thumbnail img-responsive" width="100px" height="100px" alt="Profile Image"> </div>

                                <div class="profile-usertitle">
                                    <div class="profile-usertitle-name"> <?php echo ucwords($userDetails['userName']); ?>  </div>
                                </div>

                            </div>
                            <!-- END PORTLET MAIN -->
                            <!-- PORTLET MAIN -->

                            <!-- STAT -->
                            
                <div class="table-responsive">
                                <table class="table flip-content">

                                    <tbody>
                                        <tr>

                                            <td class="numeric"> <strong>Email :</strong> </td>
                                            <td class="numeric"><?php echo $userDetails['email']; ?> </td>

                                        </tr>
                                        <tr>

                                            <td class="numeric"> <strong>Mobile :</strong> </td>
                                            <td class="numeric"><?php echo $userDetails['mobile']; ?> </td>

                                        </tr>
                                        <tr>

                                            <td class="numeric"> <strong> Status :</strong></td>
                                            <td class="numeric"><?php
                                                if ($userDetails['status'] == ACTIVE) {
                                                    echo 'Unblocked';
                                                } else {
                                                    echo 'Blocked';
                                                }
                                                ?> </td>

                                        </tr>
                                        <tr>

                                            <td class="numeric"> <strong>Distance Travel :</strong></td>
                                            <td class="numeric"><?php echo $userDetails['totalRunningDistance']; ?> </td>

                                        </tr>
                                        <tr>

                                            <td class="numeric"> <strong>Time Spent :</strong></td>
                                            <td class="numeric"><?php echo $userDetails['totalRunningTime']; ?> </td>

                                        </tr>
                                    </tbody>
                                </table>
                           </div>
                            <div class="row list-separated profile-stat">
                                <div class="form-group text-center">
                                    <?php if ($userDetails['status'] == ACTIVE) { ?>
                                        <a class="btn btn-danger mt-repeater-add userStatus" data-repeater-create data-action-title="Block" data-action-desc="Are you sure want to block user?" data-id="<?php echo $userDetails['userId']; ?>" data-value="<?php echo NOTACTIVE; ?>" data-url="<?php echo base_url("ManageUser/userStatus"); ?>" data-toggle="modal" href="javascript:void(0)" >
                                            <i class="fa fa-ban"></i> Block User
                                        </a>
                                    <?php } else { ?>
                                        <a class="btn btn-success mt-repeater-add userStatus" data-repeater-create data-action-title="Unblock" data-action-desc="Are you sure want to unblock user?" data-id="<?php echo $userDetails['userId']; ?>" data-value="<?php echo ACTIVE; ?>" data-url="<?php echo base_url("ManageUser/userStatus"); ?>" data-toggle="modal" href="javascript:void(0)" >
                                            <i class="fa fa-check-circle"></i> Unblock User
                                        </a>
                                    <?php } ?>

                                </div>
                                <!--<div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary mt-repeater-add actionModal" data-value="Send Promo"> <i class="fa fa-send-o"></i> Send Promo</button>
                                    <button type="submit" class="btn btn-primary mt-repeater-add actionModal" data-value="Send Offer"> <i class="fa fa-send-o"></i> Send Offer</button>
 
                                </div>-->
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
                                <i class="icon-list font-dark"></i>
                                <span class="caption-subject font-dark sbold uppercase">User History Details</span>
                            </div>

                        </div>
                        
                           <div class="portlet-body">
                            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered table-hover table-checkable">
                                    <thead>
                                        <tr>
                                            <th>
                                                Sr.No
                                            </th>
                                            <th> Number </th>
                                            <th> Date</th>
                                            <th> Start Time </th>
                                            <th> End Time</th>
                                            <th> Trip Cost </th>
                                            <th> Action </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $rideDetail = $userDetails['rideDetails'];
                                        if (is_array($rideDetail) || is_object($rideDetail)) {
                                            $i = 1;
                                            foreach ($rideDetail as $key => $ride) {
                                                ?>
                                                <tr class="odd gradeX">
                                                     <td > <?php echo $i++; ?> </td>
                                                    <td> <?php echo $ride['scooterNumber'] ?></td>
                                                    <td><?php echo date('d-m-y', strtotime($ride['startDate'])) ?></td>
                                                    <td> <?php echo date('h:i a', strtotime($ride['startTime'])) ?></td>
                                                    <td> <?php echo date('h:i a', strtotime($ride['endTime'])) ?></td>
                                                    <td> <?php echo '$' . $ride['totalBill'] . ' SGD' ?> </td>
                                                    <td> 
                                                        <a href="javascript:void(0)" class="btn btn-success details" data-id="<?php echo $ride['id'] ?>" title="View">
                                                            <i class="fa fa-eye"></i>                                                
                                                        </a>
                                                         <a href="<?php echo base_url("ManageScooter/view_tracking_map/"); ?><?php echo encode($ride['id']) . '/' . encode($ride['userId']); ?>"  data-repeater-create class="btn btn-info mt-repeater-add" title="View Tracking">
                                        <i class="fa fa-map-marker"></i>
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
                <h4 class="modal-title"><i class="fa fa-user"></i> User Trip Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-3">
                        <h3>Date: <span id="startDate"  class="label label-default "></span></h3>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3">
                        <h3>Scooter ID: <span id="scooterNumber"  class="label label-default"></span></h3>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3">
                        <h3>Trip Cost: <span id="totalBill" class="label label-default"></span></h3>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3">
                        <h3>User Rating: 
                            <span id="rating"  class="label label-default"></span></h3>
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <h3><label >Trip Start: <span id="startTime"  class="label label-default"></span></label></h3>
                        <span id="startLocation"></span>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <h3>Trip End: <span id="endTime"  class="label label-default"></span></h3>
                        <span id="endLocation"></span>

                    </div>
                    <!-- /.col -->

                </div>
                <div class="row">

                    <div class="col-sm-12">
                        <h3>User Comment: </h3>
                        <p id="comment">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>

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
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><i class="fa fa-warning"></i> Error</h4>
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