                  

<div class="row">
    <div class="col-md-12">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?= base_url("admin"); ?>">Home</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="<?= base_url("manageScooter"); ?>">Manage Scooto</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Scooto Details</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-green">
                    <i class="icon-eye"></i>
                    <span class="caption-subject bold uppercase">Scooto Details</span>
                </div>

            </div>
            <div class="row">

                <div class="col-md-4 col-sm-12">

                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light portlet-fit portlet-datatable bordered">
                        <div class="portlet-body ">
                            <div class="profile-usertitle">
                                <h2 class="font-green"><?= $scooterDetails['scooterNumber'] ?></h2>
                            </div>

                            <div class="table-responsive">
                                <table class="table flip-content">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Tracker ID :</strong>
                                            </td>
                                            <td><?= $scooterDetails['tarckId']; ?></td>
                                        </tr>
                                        <tr>
                                            <td > <strong>Distance Travel :</strong> </td>
                                            <td ><?= $scooterDetails['runningDistance'] ?>  </td>
                                        </tr>
                                        <tr>
                                            <td > <strong>Battery :</strong> </td>
                                            <td >20%</td>
                                        </tr>
                                        <tr>
                                            <td > <strong>Status :</strong> </td>
                                            <td ><?= $scooterDetails['status']; ?> </td>

                                        </tr>
                                        <tr>
                                            <td > <strong>Repair Time:</strong></td>
                                            <td ><?= $scooterDetails['time'] ?> </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"> <strong>Location : </strong></td>

                                        </tr>
                                        <tr>
                                            <td colspan="2"><?= $scooterDetails['location'] ?></td>                                        
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row list-separated profile-stat">
                                <div class="form-group" style="text-align: center;">
                                    <?php if ($scooterDetails['isUnderMaint'] == NOTACTIVE) { ?>
                                        <a href="javascript:void(0)" class="btn btn-success userList" data-scooteNumber="<?= $scooterDetails['scooterNumber']; ?>">To Maintenance </a>
                                    <?php } ?>
                    <!--<input type="submit" class="btn btn-warning submit" value="To Charging">-->
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
                                <i class=" icon-list"></i>
                                <span class="caption-subject sbold uppercase">Scooto Usages Details</span>
                            </div>

                        </div>
                        <div class="portlet-body">
                            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered table-hover table-checkable">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th> User Name</th>
                                            <th> Date </th>
                                            <th> Start </th>
                                            <th> End </th>
                                            <th> Action </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($scooterDetails['scooterUsagesDetails'] as $key => $scooterUsagesDetails) { ?>
                                            <tr class="odd gradeX">
                                                <td><?= $key + 1; ?></td>
                                                <td><?= $scooterUsagesDetails['userName'] ?></td>
                                                <td><?= date('d-m-y', strtotime($scooterUsagesDetails['startDate'])) ?></td>
                                                <td><?= date('h:i a', strtotime($scooterUsagesDetails['startTime'])) ?></td>
                                                <td><?= date('h:i a', strtotime($scooterUsagesDetails['endTime'])) ?></td>
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-success scooterDetails" data-id="<?= $scooterUsagesDetails['id'] ?>" title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                     <a href="<?php echo base_url("ManageScooter/view_tracking_map/"); ?><?php echo encode($scooterUsagesDetails['id']) . '/' . encode($scooterUsagesDetails['userId']); ?>"  data-repeater-create class="btn btn-info mt-repeater-add" title="View Tracking">
                                        <i class="fa fa-map-marker"></i>
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
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><span class="caption-subject font-dark bold uppercase"><i class="fa fa-list"></i> Zeep Usage Details</span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                        <h5 ><span class="caption-subject font-black-madison bold">Date:</span> <span id="startDate"></span></h5>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4">
                        <h5><span class="caption-subject font-black-madison bold">Scooter Id:</span> <span id="scooterNumber"></span></h5>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4">
                        <h5 ><span class="caption-subject font-black-madison bold">Type:</span> <span id="totalBill"></span></h5>
                    </div>
                    <!-- /.col -->

                </div><br><br>
                <div class="row">
                    <div class="col-sm-12">
                        <label ><span class="caption-subject font-black-madison bold">Illegal Parking: </span></label><span id="">None</span><br>
                        <label ><span class="caption-subject font-black-madison bold">Geo Fincing Voilation: </span></label><span id="">None</span><br>
                        <label ><span class="caption-subject font-black-madison bold">Speed Limit Voilation: </span></span></label><span id="">None</span><br>
                    </div>
                    <!-- /.col -->
                </div><br>
                <div class="row">
                    <div class="col-sm-6">
                        <label><span class="caption-subject font-black-madison bold">Trip Start: </span></label><span id="startTime"></span>
                        <h5><span id="startLocation"></span></h5>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <h5><span class="caption-subject font-black-madison bold">Trip End:</span> <span id="endTime"></span></h5>
                        <h5><span id="endLocation"></span></h5>

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

<!--Assign Maintenance-->
<div id="userlist" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="width: 1000px">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Maintenance User List</h4>
            </div>
            <div class="modal-body">
                <div class="scroller" style="height:500px" data-always-visible="1" data-rail-visible="1">
                    <div class="portlet-body flip-scroll">
                        <table id="example2" class="display table-bordered" cellspacing="0" width="100%">
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
                <button type="button" data-dismiss="modal" class="btn default">Close</button>

            </div>
        </div>
    </div>
</div>
<div id="assigntask" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="width: 30%">
        <div class="modal-content">
            <form method="post" action="<?= base_url("manageMaintenance/addAdminAssignTask"); ?>" class="addservicesForm" enctype="multipart/form-data">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Task Category</h4>
                </div>
                <div class="modal-body" >
                    <label for="form_control_1">Please Select Task Category</label>
                    <div class="form-group form-md-radios has-success">

                        <input type="hidden" name="scooterNumber" value="<?= $scooterDetails['scooterNumber']; ?>">
                        <input type="hidden" name="userId" value="" id="userId">
                        <input type="hidden" name="location" value="<?= $scooterDetails['location']; ?>">
                        <input type="hidden" name="lat" value="<?= $scooterDetails['lat']; ?>">
                        <input type="hidden" name="lng" value="<?= $scooterDetails['lng']; ?>">
                        <input type="hidden" name="comment" value="<?= 'Monthly Servicing '; ?>">
                        <input type="hidden" name="issueId" value="<?= 0; ?>">
                        <div class="md-radio-list" style="margin-left: 20px">
                            <div class="md-radio">
                                <input type="radio" id="checkbox2_6" name="categoryId" class="category_1" value="" checked="true">
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
                    <button type="submit" class="btn btn-success">Assign</button>
                    <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                    
                </div>
            </form>
        </div>
    </div>
</div>

<!--Confirm box-->
<div id="erroractionModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h3 class="modal-title">Warning!</h3>
            </div>
            <div class="modal-body bg-warning">                
                <h4><span class="actionEventDesc"></span></h4>
            </div>
            <div class="modal-footer bg-warning">
                <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
            </div>
        </div>
    </div>
</div>


