<div class="row">
    <div class="col-md-12">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?= base_url("admin"); ?>">Home</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Manage Staff</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption  font-green">
                    <i class="icon-list"></i>
                    <span class="bold uppercase">Manage Staff</span>
                </div>
                <div class="actions">
                    <div class="btn-group">
                        <a href="#addModal" data-toggle="modal" class="btn sbold btn-success">
                            <i class="fa fa-user-plus"></i> Add Maintenance User

                        </a>
                    </div>

                </div>
            </div>
            <?php //$this->load->helper('common_helper'); ?>
            <!-- BEGIN SAMPLE TABLE PORTLET-->
            <div class="portlet-body flip-scroll">
                <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered table-hover table-checkable">
                    <thead>
                        <tr>
                            <th> Sr.No </th>
                            <th> User Name </th>
                            <th> Mobile </th>
                            <th> Email </th>
                            <th> Status </th>
                            <th> Pending Task </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($user as $key => $user) { ?>
                            <tr>
                                <td > <?= $key + 1; ?> </td>
                                <td > <?= ucwords($user['userName']) ?> </td>
                                <td > <?= $user['mobile'] ?> </td>
                                <td > <?= $user['email'] ?> </td>
                                <td > <?php
                                    if ($user['status'] == ACTIVE) {
                                        echo '<h6><span class="label label-success">Un-Blocked</span></h6>';
                                    } else {
                                        echo '<h6><span class="label label-danger">Blocked</span></h6>';
                                    }
                                    ?> </td>
                                <td class="text-center"> <?= $user['count'] ?> </td>
                                <td>                                    
                                    <a href="<?= base_url("manageMaintenance/getMaintdetails/"); ?><?= encode($user['id']); ?>"  data-repeater-create class="btn btn-sm btn-success mt-repeater-add" title="View Details">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <?php if ($user['status'] == ACTIVE) { ?>
                                        <a class="btn btn-sm btn-danger mt-repeater-add actionModalConfirm" data-repeater-create data-action-title="Deactive" data-action-desc="Are you sure want to deactivate this user?" data-id="<?= $user['id']; ?>" data-value="<?= NOTACTIVE; ?>" data-url="<?= base_url('manageMaintenance/userStatus'); ?>" data-toggle="modal" href="javascript:void(0)" title="Set Deactivate">
                                            <i class="fa fa-times-circle-o"></i>
                                        </a>
                                    <?php } else { ?>
                                        <a class="btn btn-sm btn-success mt-repeater-add actionModalConfirm" data-repeater-create data-action-title="Active" data-action-desc="Are you sure want to activate this user?" data-id="<?= $user['id']; ?>" data-value="<?= ACTIVE; ?>" data-url="<?= base_url('manageMaintenance/userStatus'); ?>" data-toggle="modal" href="javascript:void(0)" title="Set Activate">
                                            <i class="fa fa-check-circle-o"></i>
                                        </a>
                                    <?php }
                                    ?> 
                                    <a class="btn btn-sm btn-danger mt-repeater-add actionModalConfirm" data-repeater-create data-action-title="Remove" data-action-desc="Are you sure want to remove this user?" data-id="<?= $user['id']; ?>" data-value="<?= DELETED; ?>" data-url="<?= base_url('manageMaintenance/removeRecord'); ?>" data-toggle="modal" href="javascript:void(0)" title="Remove">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>

                    </tbody>
                </table>
            </div>
            </div>
            <!-- END SAMPLE TABLE PORTLET-->   
        </div>
    </div>
</div>
<!-- add modal-->
<div id="addModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="" class="addservicesForm" id="adduser" enctype="multipart/form-data">     
                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title"><i class="fa fa-plus-square"></i> Maintenance Staff Registration</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" id="errormsg"></div>
                    <div class="alert alert-success" id="successmsg"></div>
                    <div class=""  data-always-visible="1" data-rail-visible="1">                          
                        <div class="form-group">
                            <label class="control-label">Mobile Number<span class="required">*</span></label>
                            <div class="input-group">
                                <span class="input-group-addon">+65</span>
                                <input type="text" name="mobile" placeholder="Enter Mobile Number" class="form-control only_number" maxlength="8" required>                               
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Full Name<span class="required">*</span></label>
                            <input type="text" name="userName" placeholder="Enter Full Name" class="form-control only_letter" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Email<span class="required">*</span></label>
                            <input type="email" name="email" placeholder="Enter Email" class="form-control modalField" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Password<span class="required">*</span></label>
                            <input type="password" name="password" placeholder="Enter Password" class="form-control modalField" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Confirm Password<span class="required">*</span></label>
                            <input type="password" name="cpassword" placeholder="Enter Confirm Password" class="form-control modalField" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success adduser" data-url="<?= base_url("manageMaintenance/addUser"); ?>">Save</button>                    
                    <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>                    
                </div>
            </form>
        </div>
    </div>
</div>
<!--Warning Model -->
<div id="actionModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"> 
            <div class="modal-header bg-warning">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h3 class="modal-title"><i class="fa fa-warning"></i> Warning</h3>

            </div> 
            <div class="modal-body">                
                <h4><span class="actionEventDesc"></span></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success confirmAction">Yes <span class="actionEvent"></span></button>
                <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>

            </div>
        </div>
    </div>
</div>
