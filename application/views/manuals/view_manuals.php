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
                    <span>Manage Manuals</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption  font-green">
                    <i class="icon-list"></i>
                    <span class="bold uppercase">Manuals Listing</span>
                </div>
                <div class="actions">
                    <div class="btn-group">
                        <a href="#addManuals" data-toggle="modal" class="btn sbold btn-success" >
                            <i class="fa fa-plus-circle"></i> Create Manuals
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
                            <th> Type of Manuals </th>
                            <th> Date  </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($manuals as $key => $manuals) { ?>
                            <tr>
                                <td> <?php echo $key + 1; ?> </td>
                                <td> <?php echo ucwords($manuals['name']) ?> </td>
                                <td> <?php echo $manuals['manualCategory'] ?> </td>
                                <td> <?php echo date('d-m-Y', strtotime($manuals['createdDate'])) ?> </td>
                                <td> 
                                    <a class="btn btn-danger mt-repeater-add actionModalConfirm" data-repeater-create data-action-title="Delete" data-action-desc="Are you sure want to delete?" data-id="<?php echo $manuals['id']; ?>" data-value="<?php echo DELETED; ?>" data-url="<?php echo base_url("ManageManauals/deleteManuals"); ?>" data-toggle="modal" href="javascript:void(0)" title="Delete">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                    <a class="btn btn-success" href="<?=$manuals['filePath']?>" target="_blank">
                                        <i class="fa fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            </div>
            <!-- END SAMPLE TABLE PORTLET-->   
        </div>
    </div>
</div>


<!--add parking model-->
<div id="addManuals" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" id="formsubmit"  enctype="multipart/form-data">
                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title"><i class="fa fa-plus-square"></i> Create Manuals</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" id="errormsg"></div>
                    <div class="alert alert-success" id="successmsg"></div>
                    <input type="hidden" name="url" id="url" value="<?php echo base_url("manageManauals/addManuals"); ?>">
                    <div class="" style="//height:150px" data-always-visible="1" data-rail-visible="1">

                        <div class="form-group">
                            <label class="control-label">Enter Name<span class="required">*</span></label>
                            <input type="text" name="manualsName" id="manualsName" placeholder="Enter a Name" class="form-control modalField" >
                        </div>
                        <div class="form-group">
                            <label class="control-label">Select Category<span class="required">*</span></label><br>
                            <select  class="form-control" name="manualsCat" id="manualsCat">
                                <option value="">--Select--</option>
                                <option  value="Engine" >Engine</option>
                                <option  value="Indicators" >Indicators</option>
                                <option  value="Brakes" >Brakes</option>
                                <option  value="Battery" >Battery</option>
                            </select> 
                        </div>


                        <div class="form-group">
                            <label class="control-label">Select File (.doc or .pdf file)<span class="required">*</span></label><br>
                            <input id="searchInput" name="image_file" id="image_file" class="form-control" type="file">

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


