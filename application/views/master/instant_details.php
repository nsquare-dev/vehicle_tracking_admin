                  

<div class="row">
    <div class="col-md-12">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?= base_url("admin"); ?>">Home</a>
                    <i class="fa fa-circle"></i>
                </li> 
                <li>
                    <a href="<?= base_url("ManageInstant"); ?>">Instant Support</a>
                    <i class="fa fa-circle"></i>
                </li> 
                <li>
                    <span>Support Details</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption  font-green">
                    <i class="icon-eye"></i>
                    <span class="caption-subject bold uppercase">Support Details</span>
                </div>

            </div>

            <?php
            if ($this->session->has_userdata('message')) {
                ?>
                <div class="alert <?= $this->session->flashdata('class'); ?>">
                    <?= $this->session->flashdata('message'); ?>
                </div>
                <?php
            }
            ?>


            <div class="row">
                <div class="col-sm-3 col-md-3">
                    <label><strong>Date :</strong></label> <span id="startDate"> <?php echo date('d/m/Y', strtotime($instantDetails['createdDate'])); ?></span>
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-md-3">
                    <label><strong> Scooter Id:</strong></label><span id="scooterNumber">&nbsp;<?php echo $instantDetails['scooterNumber']; ?></span>
                </div>
                <div class="col-sm-3 col-md-3">
                    <label><strong> Tracker Id:</strong></label><span id="tarckId"> &nbsp;<?= ($instantDetails['tarckId']) ? $instantDetails['tarckId'] : "--"; ?> </span>
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-md-3">
                    <label><strong> Email:</strong></label><span id="issueTitle">&nbsp;<?php echo $instantDetails['email']; ?></span>
                </div>
                <!-- /.col -->

                <div class="col-sm-12 col-md-12">
                    <label><strong>Location:</strong></label><span id="scooterLocation">&nbsp;<?php echo $instantDetails['location']; ?></span>
                </div>
                <!-- /.col -->

                <div class="col-sm-12 col-md-12">
                    <label><strong> User Comment:</strong></label>
                    <p id="comment"><?php echo ucfirst($instantDetails['comment']); ?></p>
                </div>
                <!-- /.col -->

                <div class="col-sm-12 col-md-12">
                    <label><strong>User Selected Option: </strong></label>
                    <?php foreach ($option as $key => $option) { ?>
                        <p id="comment"><?php echo ++$key . ') ' . $option['name'] . '.'; ?></p>
                    <?php } ?>
                </div>
                <!-- /.col -->
            </div>
            <div class="row">
                <?php if (file_exists($instantDetails['image_1'])) { ?>
                    <div class="col-sm-3">
                        <img class="prod-img" id="img1" src="<?php echo $instantDetails['image_1']; ?>" onerror="this.src='<?php echo base_url(); ?>resource/default/placeholder.png'" height="100px" width="200px">
                    </div>
                    <?php
                }
                if (file_exists($instantDetails['image_2'])) {
                    ?>
                    <!-- /.col -->
                    <div class="col-sm-3">
                        <img class="prod-img" id="img2" src="<?php echo $instantDetails['image_2']; ?>" onerror="this.src='<?php echo base_url(); ?>resource/default/placeholder.png'" height="100px" width="200px">
                    </div>
                    <?php
                }
                if (file_exists($instantDetails['image_3'])) {
                    ?>
                    <!-- /.col -->
                    <div class="col-sm-3">
                        <img class="prod-img" id="img3" src="<?php echo $instantDetails['image_3']; ?>" onerror="this.src='<?php echo base_url(); ?>resource/default/placeholder.png'" height="100px" width="200px">
                    </div>
                    <?php
                }
                if (file_exists($instantDetails['image_4'])) {
                    ?>
                    <!-- /.col -->
                    <div class="col-sm-3">
                        <img class="prod-img" id="img4" src="<?php echo $instantDetails['image_4']; ?>" onerror="this.src='<?php echo base_url(); ?>resource/default/placeholder.png'" height="100px" width="200px">
                    </div>
                <?php }
                ?>
                <!-- /.col -->
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="btn-group btn-group-justified">
                        <?php
                        if ($instantDetails['scooterStatus'] == RESERVE && $rideStatus->rideStatus == RIDERUNNING) {
                            ?>

                            <div class="btn-group">
                                <button class="btn btn-danger" id="stopRide" data-url="<?= base_url("ManageInstant/stopride/" . encode($instantDetails['scooterNumber']) . "/" . encode($instantDetails['id'])); ?>"><i class="fa fa-stop-circle"></i> Stop Ride</button>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="btn-group">
                            <a href="javascript:void(0)" class="btn btn-success userList" data-scooteNumber="<?= $instantDetails['scooterNumber']; ?>"><i class="fa fa-share-square-o"></i> Assign Maintenance</a>
                        </div>
                        <div class="btn-group">                        
                        <!--    <button type="button" class="btn btn-success"><i class="fa fa-reply"></i>  Refund</button>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning"><i class="fa fa-rupee"></i>  Apply Fine</button>
                        </div>-->
                    </div>
                </div>

                <!--div class="col-sm-3 col-md-3">
                 <input type="submit" class="btn green submit" value="To Maintanance ">
                </div-->
                <!-- /.col -->
            </div>

            <?php if ($instantDetails['maintId'] != '') { ?>
                <div class="row">
                    <div class="col-sm-12">
                        <span style="color:red">Under maintenance <?php echo $instantDetails['maintStatus']; ?> </span>
                    </div>
                </div>
            <?php } ?>
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
                        </table>
                    </div> 
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="assigntask" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="width: 30%">

        <div class="modal-content">
            <form method="post" action="<?php echo base_url(); ?>manageMaintenance/addAssignTask" class="addservicesForm" enctype="multipart/form-data">

                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title"><i class="fa fa-list"></i>
                        Task Category</h4>
                </div>
                <div class="modal-body" >
                    <label for="form_control_1">Please Select Task Category</label>
                    <div class="form-group form-md-radios has-success">

                        <input type="hidden" name="scooterNumber" value="<?php echo $instantDetails['scooterNumber']; ?>">
                        <input type="hidden" name="userId" value="" id="userId">
                        <input type="hidden" name="location" value="<?php echo $instantDetails['location']; ?>">
                        <input type="hidden" name="lat" value="<?php echo $instantDetails['lat']; ?>">
                        <input type="hidden" name="lng" value="<?php echo $instantDetails['lng']; ?>">
                        <input type="hidden" name="comment" value="<?php echo $instantDetails['comment']; ?>">
                        <input type="hidden" name="issueId" value="<?php echo $instantDetails['id']; ?>">
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
                    <input type="submit" class="btn btn-success" value="Assign">
                    <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>                    
                </div>
            </form>
        </div>
    </div>
</div>
<!--<div id="actionModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><span class="actionEventTitle"></span> <?php //echo $pagetitle;       ?></h4>
            </div>
            <div class="modal-body">
                <h4> Are you sure<span class="actionEventDesc"></span></h4>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn default">Close</button>
                <a href="" type="button" class="btn default green confirmAction">Yes <span class="actionEvent"></span></a>
            </div>
        </div>
    </div>
</div>-->
<!--Maintenance progress start pop-up-->
<!--<div id="progress" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><span class="actionEventTitle"></span> </h4>
            </div>
            <div class="modal-body">
                <h4> Already Maintenance progress start not resign scooter <span class="actionEventDesc"></span></h4>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn default">Close</button>
              
            </div>
        </div>
    </div>
</div>-->
<div id="erroractionModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><i class="fa fa-warning"></i> Warning</h4>
            </div>
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4><span class="actionEventDesc"></span></h4>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="confirmStopRide" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><i class="fa fa-warning"></i> Warning</h4>
            </div>
            <div class="modal-body">

                <h4>Are you sure want to stop ride?<span class="actionEventDesc"></span></h4>
            </div>
            <div class="modal-footer">                
                <a href="" type="button" class="btn btn-success confirmAction">Yes <span class="actionEvent"></span></a>
                <button type="button" data-dismiss="modal" class="btn btn-danger">No</button>
            </div>
        </div>
    </div>
</div>