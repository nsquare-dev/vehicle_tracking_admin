                  

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-eye font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">Scooter User Deatils</span>
                </div>

            </div>
            <div class="row">

                <div class="col-md-4 col-sm-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light portlet-fit portlet-datatable bordered">


                        <div class="portlet-body ">
                            <div class="profile-sidebar-portlet">
                                <!-- SIDEBAR USERPIC -->
                                <div class="profile-userpic">
                                    <img src="<?php echo $userDetails['profileImage']; ?>" onerror="this.src='<?php echo base_url(); ?>resource/default/default_profile.png'" class="img-responsive" style="height:100px; width:100px"  alt=""> </div>

                                <div class="profile-usertitle">
                                    <div class="profile-usertitle-name"> <?php echo $userDetails['userName']; ?>  </div>
                                </div>

                            </div>
                            <!-- END PORTLET MAIN -->
                            <!-- PORTLET MAIN -->

                            <!-- STAT -->
                            <table class="table table table-striped table-condensed flip-content">

                                <tbody>
                                    <tr>

                                        <td class="numeric"> Email </td>
                                        <td class="numeric"><?php echo $userDetails['email']; ?> </td>

                                    </tr>
                                    <tr>

                                        <td class="numeric"> Mobile </td>
                                        <td class="numeric"><?php echo $userDetails['mobile']; ?> </td>

                                    </tr>
                                     <tr>

                                        <td class="numeric"> Status</td>
                                        <td class="numeric"><?php if($userDetails['status']== ACTIVE){ echo 'Unblocked'; }else{ echo 'Blocked'; } ?> </td>

                                    </tr>
                                    <tr>

                                        <td class="numeric"> Distance Travel</td>
                                        <td class="numeric"><?php echo $userDetails['totalRunningDistance']; ?> </td>

                                    </tr>
                                    <tr>

                                        <td class="numeric"> Time Spent</td>
                                        <td class="numeric"><?php echo $userDetails['totalRunningTime']; ?> </td>

                                    </tr>
                                </tbody>
                            </table>





                            <div class="row list-separated profile-stat">
                                <div class="form-group" style="text-align: center;">
                                   <?php if($userDetails['status'] == ACTIVE){ ?>
                                        <a class="btn btn-info mt-repeater-add userStatus" data-repeater-create data-action-title="Block" data-action-desc="Are you sure want to block user?" data-id="<?php echo $userDetails['userId']; ?>" data-value="<?php echo NOTACTIVE; ?>" data-url="<?php echo base_url(); ?>ManageUser/userStatus" data-toggle="modal" href="javascript:void(0)" >Block User</a>
                                        <?php }else{ ?>
                                        <a class="btn btn-info mt-repeater-add userStatus" data-repeater-create data-action-title="Unblock" data-action-desc="Are you sure want to unblock user?" data-id="<?php echo $userDetails['userId']; ?>" data-value="<?php echo ACTIVE; ?>" data-url="<?php echo base_url(); ?>ManageUser/userStatus" data-toggle="modal" href="javascript:void(0)" >Unblock User</a>
                                 <?php } ?>
                                   
                                </div>
                                <div class="form-group" style="text-align: center;">
                                    <input type="submit" class="btn btn-info mt-repeater-add actionModal" value="Send Promo" style="width: 107px;">
                                    <input type="submit" class="btn btn-info mt-repeater-add actionModal" value="Send Offer" style="width: 107px;">
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
                                <span class="caption-subject font-dark sbold uppercase">User History Details</span>
                            </div>

                        </div>
                        <div class="portlet-body flip-scroll">
                    <table id="example" class="display table-bordered" cellspacing="0" width="100%">   
                        <thead>
                                    <tr>
                                        <th class="table-checkbox">
                                            Sr.No
                                        </th>
                                        <th> Scooter Number </th>
                                        <th> Date</th>
                                        <th> Start Time </th>
                                        <th> End Time</th>
                                        <th> Trip Cost </th>
                                        <th> Action </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                  $rideDetail=$userDetails['rideDetails'];
                                  if (is_array($rideDetail) || is_object($rideDetail)){
                                      $i = 1;
                                      foreach ($rideDetail as $key => $ride) { ?>
                                        <tr class="odd gradeX">
                                            < <td > <?php echo $i++; ?> </td>
                                            <td> <?php echo $ride['scooterNumber'] ?></td>
                                            <td><?php echo date('d-m-y',strtotime($ride['startDate'])) ?></td>
                                            <td> <?php echo date('h:i a',strtotime($ride['startTime'])) ?></td>
                                            <td> <?php echo date('h:i a',strtotime($ride['endTime'])) ?></td>
                                            <td> <?php echo '$'.$ride['totalBill'].' SGD' ?> </td>
                                            <td> <a href="javascript:void(0)" class="btn btn-info details" data-id="<?php echo $ride['id'] ?>">View</a></td>
                                        </tr>
<?php } } ?>
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
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">User Trip Details</h4>
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
                        <h5>Trip Cost: $<span id="totalBill"></span></h5>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3">
                        <h5>User Rating: <span id="rating"></span> <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star"></span>
                        <span class="fa fa-star"></span></h5>
                        
                    </div>
                    <!-- /.col -->
                </div><br><br>
                <div class="row">
                    <div class="col-sm-6">
                        <label >Trip Start: <span id="startTime"></span></label>
                        <h5><span id="startLocation"></span></h5>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <h5>Trip End: <span id="endTime"></span></h5>
                        <h5><span id="endLocation"></span></h5>

                    </div>
                    <!-- /.col -->

                </div>
                <div class="row">

                    <div class="col-sm-12">
                        <h5>User Comment: </h5>
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
                <button type="button" data-dismiss="modal" class="btn default">Close</button>

            </div>
        </div>
    </div>
</div>
<div id="actionModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-body">
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4><span class="actionEventDesc"></span></h4>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn default">Close</button>
                <a href="" type="button" class="btn default green confirmAction">Yes <span class="actionEvent"></span></a>
            </div>
        </div>
    </div>
</div>
<div id="erroractionModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-body">
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4><span class="actionEventDesc"></span></h4>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn default">Close</button>
            </div>
        </div>
    </div>
</div>