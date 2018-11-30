
<div class="row">
    <div class="col-md-12">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?= base_url("admin"); ?>">Home</a>
                    <i class="fa fa-circle"></i>
                </li>  
                <li>
                    <span>Manage Command</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list font-black"></i>
                    <span class="bold uppercase">Scooto Command Listing</span>
                </div>
                <div class="actions">
                    <div class="btn-group">
                        <a href="#addCommand" data-toggle="modal" class="btn sbold btn-success" id="btn_cmd">
                            <i class="fa fa-plus-circle"></i> Add Command

                        </a>
                    </div>
                </div>
            </div>

            <!-- BEGIN SAMPLE TABLE PORTLET-->
            <div class="portlet-body flip-scroll">
                <table id="example" class="display table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th> Sr.No </th>
                            <th> Title </th>
                            <th> Command </th>
                            <th> Description </th>
                            <th> Code </th> 
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($command_list as $key => $command) {
                            ?>
                            <tr>
                                <td> <?php echo $i++; ?> </td>
                                <td> <?php echo ucwords($command['title']) ?> </td>
                                <td> <?php echo $command['command'] ?> </td>
                                <td> <?php echo $command['description'] ?> </td>
                                <td> <?php echo $command['code']; ?> </td>
                                <td>  
                                    <button data-repeater-create class="btn btn-success mt-repeater-add btn_cmd" data-value="<?php echo $command['id']; ?>" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </button> 


                                    <?php //echo anchor('/command/delete_record/'.$command['id'], 'Delete', 'onclick="if (!confirm(\'Are you sure want to delete this record?\')) return false;" data-toggle="tooltip" title="Delete" class="btn btn-info mt-repeater-add"'); ?>

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

<!--add parking model-->
<div id="addCommand" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" id="formsubmit"  enctype="multipart/form-data">
                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title"><i class="fa fa-plus-square"></i> Add Command</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="url" id="url" value="<?php echo base_url("command/add"); ?>">
                    <div class="" style="//height:150px" data-always-visible="1" data-rail-visible="1">
                        <div class="form-group"><span id="errormsg" style="color:red"></span></div>
                        <div class="form-group">
                            <label class="control-label">Title<span class="required">*</span></label>
                            <input type="text" name="field_title" id="txt_title" placeholder="Enter a Title" class="form-control modalField" required>
                            <span id="field_title" style="color:red"></span>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Description<span class="required"></span></label><br>
                            <textarea name="field_description" id="txt_description" class="form-control modalField"></textarea>
                            <span id="field_description" style="color:red"></span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Command<span class="required">*</span></label><br>
                            <input type="text" name="field_command" id="txt_command" placeholder="Enter a Command" class="form-control modalField" required>
                            <span id="field_command" style="color:red"></span> 
                        </div>
                        <div class="form-group">
                            <label class="control-label">Syntax<span class="required">*</span></label><br>
                            <input type="text" name="field_syntax" id="txt_syntax" placeholder="Enter a Syntax" class="form-control modalField" required>
                            <span id="field_syntax" style="color:red"></span> 
                        </div>
                        <div class="form-group">
                            <label class="control-label">Example<span class="required">*</span></label><br>
                            <input type="text" name="field_example" id="txt_example" placeholder="Enter a Example" class="form-control modalField" required>
                            <span id="field_example" style="color:red"></span> 
                        </div>
                        <div class="form-group" id="form_group_key">
                            <label class="control-label">Command Code<span class="required">*</span></label><br>
                            <input type="text" name="field_command_key" style="text-transform:uppercase" id="txt_key" placeholder="Enter a Key" class="form-control modalField" required>
                            <span id="field_command_key" style="color:red"></span>
                        </div>
                        <input type="hidden" name="field_id" id="id">

                    </div>
                </div>
                <div class="modal-footer">
                    
                    <button type="submit" name="submit" id="submit" class="btn btn-success">Save</button>
                    <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>

                </div>
            </form>

        </div>
    </div>
</div>
