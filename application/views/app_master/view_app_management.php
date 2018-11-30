<div class="row">
    <div class="col-md-12">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?= base_url("admin"); ?>">Home</a>
                    <i class="fa fa-circle"></i>
                </li>  
                <li>
                    <span>Manage App</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">                    
                    <span class="bold uppercase font-green-sharp"><i class="icon-settings"></i> Manage App</span>
                </div>
            </div>
            <div class="tab-pane active" id="tab_1_1">
                <div class="row">
                    <div class="col-md-6">
                        <!-- BEGIN BLOCK BUTTONS PORTLET-->
                        <div class="portlet light">
                            <div class="portlet-title">
                                <h4 class="font-green-sharp bold uppercase"><i class="fa fa-money"></i> TopUp Plans</h4>
                            </div>
                            <div class="portlet-body">
                                 <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered table-hover table-checkable">
                                    <tbody>
                                        <tr>
                                            <th colspan="2"> Current Plans </th>
                                        </tr>
                                        <?php foreach ($topup as $key => $topup) { ?>
                                            <tr>
                                                <td > <?php echo '$' . $topup['price'] . ' SGD' ?>  </td>
                                                <td > <?php
                                                    if ($topup['bonus'] != '') {
                                                        echo '+free bonus $' . $topup['bonus'] . ' SGD';
                                                    }
                                                    ?> </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <div class="text-center">
                                    <a href="#" data-toggle="modal" model-name="topup" data-url="AppManagement/gettopup/" class="btn sbold btn-success btn-lg getdata" ><i class="fa fa-pencil-square"></i> Edit</a>
                                </div>
                            </div>
                            </div>
                        </div>

                        <!-- END BLOCK BUTTONS PORTLET--> 
                        <!-- BEGIN BLOCK BUTTONS PORTLET-->
                        <div class="portlet light">
                            <div class="portlet-title">
                                <h4 class="font-green-sharp bold uppercase"><i class="fa fa-money"></i> Penalty Charges</h4>
                            </div>
                            <div class="portlet-body">
                                <ul class="list-group">
                                    <li class="list-group-item"><?php echo '$' . $config['illgelParking'] ?> for illegal Parking</li>
                                    <li class="list-group-item"><?php echo '$' . $config['limitViolation'] ?> for Speed Limit Voliation</li>
                                    <li class="list-group-item"><?php echo '$' . $config['rashDriving'] ?> for Rash Driving</li>
                                    <li class="list-group-item">*Note this charges gets include in user ride bill</li>
                                </ul>                                
                                <div class="text-center">
                                    <a href="#" data-toggle="modal" model-name="penaltyCharges" data-url="AppManagement/getData/" class="btn sbold btn-success btn-lg getdata"><i class="fa fa-pencil-square"></i> Edit</a>
                                </div>
                            </div> 
                        </div>
                        <!-- END BLOCK BUTTONS PORTLET-->
                    </div>
                    <div class="col-md-6">
                        <!-- BEGIN BLOCK BUTTONS PORTLET-->
                        <div class="portlet light">
                            <div class="portlet-title">
                                <h4 class="font-green-sharp bold uppercase"><i class="fa fa-gear"></i> App Configuration</h4>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table class="table table table-striped table-condensed table-bordered flip-content">
                                        <tr>
                                            <td class="bold"> 
                                                Deposit Amount
                                            </td>
                                            <td>
                                                $<?php echo $config['depositAmount']; ?> SGD
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bold"> 
                                                View Scooters in Radious
                                            </td>
                                            <td>
                                                <?php echo $config['scooterRadius']; ?> KM
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bold"> 
                                                Reservation can cancel in
                                            </td>
                                            <td>
                                                <?php echo $config['scooterCancelTime']; ?> Min
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bold"> 
                                                Charges Per Min
                                            </td>
                                            <td>
                                                $<?php echo $config['scooterPerMinChrages']; ?> SGD
                                            </td>
                                        </tr>
                                        <!--<tr>
                                            <td class="bold"> 
                                                Base Fare
                                            </td>
                                            <td>
                                                $<?php echo $config['scooterBaseFair']; ?> SGD
                                            </td>
                                        </tr>-->
                                        <tr>
                                            <td class="bold"> 
                                                Own Refferal Amount
                                            </td>
                                            <td>
                                                $<?php echo $config['ownRefferralAmount']; ?> SGD
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bold"> 
                                                Other Refferal Amount
                                            </td>
                                            <td>
                                                $<?php echo $config['anotherRefferralAmount']; ?> SGD
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="text-center">
                                        <a href="#" id="edit_config" data-toggle="modal" model-name="updateConfig" class="btn sbold btn-success btn-lg" data-value='<?= json_encode($config) ?>'><i class="fa fa-pencil-square"></i> Edit</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END BLOCK BUTTONS PORTLET-->                       

                    </div>
                    <!-- END BLOCK BUTTONS PORTLET-->
                </div>
            </div>

        </div>
    </div>
</div>
</div>
<!-- Update Configuration-->
<div id="updateConfig" class="modal fade" tabindex="-1" area-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php echo form_open('appManagement/updateConfig', 'class="form-horizontal" id="frm_config"'); ?>
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-gear"></i> Update Configuration</h4>
            </div>

            <div class="modal-body">
                <div class="alert alert-danger" id="errormsg_edit"></div>
                <div class="alert alert-success" id="successmsg_edit"></div>
                <input type="hidden" name="field_confId" id="field_confId">
                <div class="form-group">
                    <label class="control-label col-sm-4" for="field_deposite_amount">Deposite Amount:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="field_deposite_amount" name="field_deposite_amount">
                    </div>

                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="field_radious">Radious:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="field_radious" name="field_radious">
                    </div>

                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="field_cancel_min">Reservation Cancel In Min:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="field_cancel_min" name="field_cancel_min">
                    </div>

                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="field_charges_per_min">Charges Per Min:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="field_charges_per_min" name="field_charges_per_min">
                    </div>

                </div>
                <!--<div class="form-group">
                    <label class="control-label col-sm-4" for="field_base_fare">Base Fare:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="field_base_fare" name="field_base_fare">
                    </div>

                </div>-->
                <div class="form-group">
                    <label class="control-label col-sm-4" for="field_own_refferal_amount">Own Refferal Amount:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="field_own_refferal_amount" name="field_own_refferal_amount">
                    </div>

                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="field_other_refferal_amount">Other Refferal Amount:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="field_other_refferal_amount" name="field_other_refferal_amount">
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success submitConfig">Update</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>

    </div>
</div>

<!--add topup-->
<div id="topup" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="<?php echo base_url("AppManagement/updateTopUp"); ?>" id="frmtopup" class="form" enctype="multipart/form-data">
                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title"><i class="fa fa-money"></i> Update TOP-UP Plans</h4>
                </div>
                <div class="modal-body">
                    <div id="errormsg_topup" class="alert alert-danger"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Plan Amount<span class="required">*</span></label>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Free bonus with plan amount</label>
                        </div>
                    </div>
                    <div>
                        <div class="" style="//height:150px" data-always-visible="1" data-rail-visible="1">
                            <div class="field_wrapper">
                                <div class="inputfield"></div>
                                <br>             
                            </div>
                        </div>
                        <a href="javascript:void(0);" class="btn btn-link pull-right add_button" title="Add field"><i class="fa fa-plus-circle"></i> Add More</a><br>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" value="Update">
                    <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>                    
                </div>
            </form>
        </div>
    </div>
</div>

<!--add Penalty Charges-->
<div id="penaltyCharges" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="frmsubmitpanelty" class="form-horizontal" enctype="multipart/form-data">
                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title"><i class="fa fa-money"></i> Update Penalty Charges </h4>
                </div>

                <div class="modal-body">
                    <div class="" style="//height:150px" data-always-visible="1" data-rail-visible="1">
                        <div id="errormsg" class="alert alert-danger"></div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Enter Charges for Illegal Parking<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="illegalParking" class="form-control only_number" placeholder="Enter Charges for Illegal Parking" required>
                                <span id="parkingName_error" style="color:red"></span>
                            </div>


                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Enter Charges for Speed Limit Violation <span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="limitViolation" class="form-control only_number" placeholder="Enter Charges for Speed Limit Violation" required>
                                <span id="parkingName_error" style="color:red"></span>
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Enter Charges for Rash Driving<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="rashDriving" class="form-control only_number" placeholder="Enter Charges for Rash Driving" required>
                                <span id="parkingName_error" style="color:red"></span>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <input type="submit" class="btn btn-success"  value="Update">
                    <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
