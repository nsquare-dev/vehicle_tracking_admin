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
                    <span>Manage Knowledge Base</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption  font-green">
                    <i class="icon-list"></i>
                    <span class="bold uppercase">Knowledge Base Listing</span>
                </div>
                <div class="actions">
                    <div class="btn-group">
                        <a href="#addKnowledge" data-toggle="modal" class="btn btn-success sbold" >
                            <i class="fa fa-plus-circle"></i> Create Knowledge Base                            
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
                        <?php foreach ($knowledge as $key => $knowledge) { ?>
                            <tr>
                                <td> <?php echo $key + 1; ?> </td>
                                <td> <?php echo ucwords($knowledge['name']); ?> </td>
                                <td> <?php echo date('d-m-Y', strtotime($knowledge['createdDate'])); ?> </td>
                                <td> 
                                    <a class="btn btn-danger mt-repeater-add actionModalConfirm" data-repeater-create data-action-title="Delete" data-action-desc="Are you sure want to delete?" data-id="<?php echo $knowledge['id']; ?>" data-value="<?php echo DELETED; ?>" data-url="<?php echo base_url("ManageKnowledge/deleteKnowledge"); ?>" data-toggle="modal" href="javascript:void(0)" title="Delete">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                    <a class="btn btn-success" href="<?=$knowledge['filePath']?>" target="_blank">
                                        <i class="fa fa-download"></i>
                                    </a>
                                    <!--<a class="btn btn-info mt-repeater-add actionModal" data-repeater-create data-action-title="Delete" data-action-desc="Delete Manuals" data-id="<?php echo $knowledge['id']; ?>" data-value="<?php echo DELETED; ?>" data-url="<?php echo base_url(); ?>ManageManauals/deleteManuals" data-toggle="modal" href="javascript:void(0)" >Edit</a>-->
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
                    <h4 class="modal-title"><i class="fa fa-plus-square"></i> Create Knowledge Document</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" id="errormsg"></div>
                    <div class="alert alert-success" id="successmsg"></div>
                    <input type="hidden" name="url" id="url" value="<?php echo base_url("ManageKnowledge/add_knowledge"); ?>">
                    <div class="" style="//height:150px" data-always-visible="1" data-rail-visible="1">
                        <div class="form-group">
                            <label class="control-label">Name<span class="required">*</span></label>
                            <input type="text" name="knowledgeName" placeholder="Enter Name" class="form-control modalField">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Select File (.doc or .pdf file)<span class="required">*</span></label>
                            <input id="searchInput" name="image_file" id="image_file" class="form-control" type="file" >
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="upload" id="upload" class="btn btn-success">Save</button>                     
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

