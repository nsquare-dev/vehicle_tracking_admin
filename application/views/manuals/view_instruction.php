<style>
    .pac-container {
        z-index: 20000 !important;
        display: inline-block;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?= base_url("admin"); ?>">Home</a>
                    <i class="fa fa-circle"></i>
                </li>  
                <li>
                    <span>Manage Instruction</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption  font-green">
                    <i class="icon-list"></i>
                    <span class="bold uppercase">Instruction Listing</span>
                </div>
                <div class="actions">
                    <div class="btn-group">
                        <a href="#addKnowledge" data-toggle="modal" class="btn btn-success sbold" >
                            <i class="fa fa-plus-circle"></i> Update Instruction                            
                        </a>
                    </div>
                </div>
            </div>
            <!-- BEGIN SAMPLE TABLE PORTLET-->

            <div class="portlet-body flip-scroll">
               <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered table-hover table-checkable">
                    <thead>
                        <tr>
                            <th> Sr.No </th>
                            <th> Name </th>
                            <th> Date  </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($instructions) { ?>
                            <tr>

                                <td> <?php echo $instructions['id']; ?> </td>
                                <td> <?php echo ucwords($instructions['instruction']); ?> </td>
                                <td> <?php echo date('d-m-Y', strtotime($instructions['createdDate'])); ?> </td>
                                <td> 
    <!--                                    <a class="btn btn-danger mt-repeater-add actionModalConfirm" data-repeater-create data-action-title="Delete" data-action-desc="Are you sure want to delete?" data-id="<?php echo $instructions['id']; ?>" data-value="<?php echo DELETED; ?>" data-url="<?php echo base_url("ManageInstruction/deleteInstruction"); ?>" data-toggle="modal" href="javascript:void(0)" title="Delete">
                                        <i class="fa fa-remove"></i>
                                    </a>-->
                                    <a class="btn btn-success" href="<?= $instructions['filePath'] ?>" target="_blank">
                                        <i class="fa fa-download"></i>
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


<!--add Knowledge model-->
<div id="addKnowledge" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="formsubmit"  enctype="multipart/form-data">
                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title"><i class="fa fa-plus-square"></i> Update Instruction</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" id="errormsg"></div>
                    <div class="alert alert-success" id="successmsg"></div>
                    <input type="hidden" name="url" id="url" value="<?php echo base_url("ManageInstruction/add_instruction"); ?>">
                    <div class="" style="//height:150px" data-always-visible="1" data-rail-visible="1">
                        <div class="form-group">
                            <label class="control-label">Name<span class="required">*</span></label>
                            <input type="text" name="instructionName" placeholder="Enter Name" class="form-control modalField" value="<?php echo ucwords($instructions['instruction']); ?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Select File (.doc or .pdf file)<span class="required">*</span></label>
                            <input id="searchInput" name="image_file" id="image_file" class="form-control" type="file" >
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="upload" id="upload" class="btn btn-success">Update</button>                     
                    <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>                     
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
                <h4 class="modal-title"><i class="fa fa-warning"></i> Warning</h4>
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

