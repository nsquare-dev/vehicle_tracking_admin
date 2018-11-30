<div class="row">
    <div class="col-md-12">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?= base_url("admin"); ?>">Home</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Manage User</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption  font-green">
                    <i class="icon-list"></i>
                    <span class="bold uppercase">Scooto User Listing</span>
                </div>
            </div>

            <!-- BEGIN SAMPLE TABLE PORTLET-->
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered table-hover table-checkable">
                    <thead>
                        <tr>
                            <th> Sr.No </th>
                            <th> UserName </th>
                            <th> Mobile </th>
                            <th> Email </th>
                            <th> Status </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($user as $key => $user) {
                            ?>
                            <tr>
                                <td > <?=$i++; ?> </td>
                                <td > <?=ucwords($user['userName']) ?> </td>
                                <td > <?=$user['mobile'] ?> </td>
                                <td > <?=$user['email'] ?> </td>
                                <td > <?php
                                    if ($user['status'] == ACTIVE) {
                                        echo '<h6><span class="label label-success">Un-Blocked</span></h6>';
                                    } else {
                                        echo '<h6><span class="label label-danger">Blocked</span></h6>';
                                    }
                                    ?> </td>

                                <td >  
                                    <a href="<?=base_url("manageUser/view_users_detail/"); ?><?=encode($user['id']); ?>"  data-repeater-create class="btn btn-sm btn-success mt-repeater-add" title="View Details">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    <a href="<?=base_url("manageUser/view_users_feedbacks/"); ?><?=encode($user['id']); ?>"  data-repeater-create class="btn btn-sm btn-success mt-repeater-add" title="View Feedbacks">
                                        <i class="fa fa-comment-o"></i>

                                    </a>
                                    <?php if ($user['status'] == ACTIVE) { ?>
                                        <a class="btn btn-sm btn-danger mt-repeater-add actionModalConfirm" data-repeater-create data-action-title="Deactive" data-action-desc="Are you sure want to block user?" data-id="<?=$user['id']; ?>" data-value="<?=NOTACTIVE; ?>" data-url="<?=base_url('manageUser/userStatus'); ?>" data-toggle="modal" href="javascript:void(0)" title="Set Block">
                                            <i class="fa fa-times-circle-o"></i>
                                        </a>
                                    <?php } else { ?>
                                        <a class="btn btn-sm btn-success mt-repeater-add actionModalConfirm" data-repeater-create data-action-title="Active" data-action-desc="Are you sure want to un-block user?" data-id="<?=$user['id']; ?>" data-value="<?=ACTIVE; ?>" data-url="<?=base_url('manageUser/userStatus'); ?>" data-toggle="modal" href="javascript:void(0)" title="Set Un-Block">
                                            <i class="fa fa-check-circle-o"></i>
                                        </a>
                                    <?php }
                                    ?> 
                                    <a class="btn btn-sm btn-danger mt-repeater-add actionModalConfirm" data-repeater-create data-action-title="Remove" data-action-desc="Are you sure want to remove this user?" data-id="<?=$user['id']; ?>" data-value="<?=DELETED; ?>" data-url="<?=base_url('manageUser/removeRecord'); ?>" data-toggle="modal" href="javascript:void(0)" title="Remove">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>

                    </tbody>
                </table>
            </div>
            <!-- END SAMPLE TABLE PORTLET-->   
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
                <button type="button" type="button" class="btn btn-success confirmAction">Yes <span class="actionEvent"></span></button>
                <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>

            </div>
        </div>
    </div>
</div>