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
                    <span>Manage Promo Codes</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">

                <div class="caption  font-green">
                    <i class="icon-list"></i>
                    <span class="bold uppercase">Manage Promo Codes</span>
                </div>
                <div class="actions">
                    <div class="btn-group">
                        <a href="#addModel" data-toggle="modal" class="btn btn-success btn-md">
                            <i class="fa fa-plus-circle"></i> Create Promo Code                            
                        </a>
                    </div>
                </div>
            </div>
            <!-- BEGIN SAMPLE TABLE PORTLET-->
            <div class="portlet-body flip-scroll">
                <div class="table-responsive">
                    <table id="example" class="display table-bordered" cellspacing="0" width="99%">
                        <thead>
                            <tr>
                                <th> Sr.No </th>
                                <th> Title </th>
                                <th> Image </th>
                                <th> Banner </th>
                                <th> Percentage </th>
                                <th> Code </th>
                                <th> Start Date/Time </th>
                                <th> End Date/Time </th>
                                <th> Status </th>                                
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($results as $key => $result) {
                                ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= ucwords($result->offerTitle) ?></td>
                                    <td>
                                        <img src="<?= $result->offerImage ?>" width="250px" class="img-responsive img-thumbnail" alt="Image">
                                    </td>
                                    <td>
                                        <img src="<?= $result->offerBannerImage ?>" width="250px" class="img-responsive img-thumbnail" alt="Banner">
                                    </td>
                                    <td><?= ucwords($result->offerPrice) ?>%</td>
                                    <td><?= ucwords($result->promoCode) ?></td>
                                    <td><?= $result->startDate ?></td>
                                    <td><?= $result->endDate ?></td>
                                    <td>
                                        <?php
                                        if ($result->status == ACTIVE) {
                                            echo '<h6><span class="label label-success">Active</span></h6>';
                                        } else {
                                            echo '<h6><span class="label label-danger">De-Active</span></h6>';
                                        }
                                        ?> 
                                    </td> 
                                    <td>
                                        <button class="btn btn-success btn-sm btn-flat edit-promo" title="Edit" data-value='<?= json_encode($result) ?>'><i class="fa fa-edit"></i></button>
                                        <?php if ($result->status == ACTIVE) { ?>
                                            <button class="btn btn-success btn-sm btn-flat actionModalConfirm" data-repeater-create data-action-title="Deactive" data-action-desc="Are you sure want to de-active Promo code?" data-id="<?= $result->id; ?>" data-value="<?= NOTACTIVE; ?>" data-url="<?= base_url("ManagePromoCodes/changeStatus"); ?>" data-toggle="modal"  title="Set Activate"><i class="fa fa-check-circle"></i></button>
                                        <?php } else { ?>
                                            <button class="btn btn-danger btn-sm btn-flat actionModalConfirm" data-repeater-create data-action-title="Active" data-action-desc="Are you sure want to activate this Promo code?" data-id="<?= $result->id; ?>" data-value="<?= ACTIVE; ?>" data-url="<?= base_url("ManagePromoCodes/changeStatus"); ?>" data-toggle="modal" title="Set De-Activate"><i class="fa  fa-times-circle-o"></i></button>
                                        <?php }
                                        ?> 



                                        <button class="btn btn-danger btn-sm btn-flat actionModalConfirm" data-repeater-create data-action-title="Active" data-action-desc="Are you sure want to remove this Promo code?" data-id="<?= $result->id; ?>" data-value="<?= ACTIVE; ?>" data-url="<?= base_url("ManagePromoCodes/remove"); ?>" data-toggle="modal"  title="Remove"><i class="fa fa-remove"></i></button>

                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>

                        </tbody>
                    </table>
                </div>

            </div>
            <!-- END SAMPLE TABLE PORTLET-->   
        </div>
    </div>
</div>
<!-- Warning Modal-->
<div id="actionModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"> 
            <div class="modal-header bg-warning">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
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
<!--addModel -->
<div id="addModel" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"> 
            <form  action="<?= base_url("ManagePromoCodes/add") ?>" method="post" enctype="multipart/form-data" class="form-horizontal" id="addPromoCode">
                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-square"></i> Create Promo Code</h4>
                </div>
                <div class="modal-body"> 
                    <div class="alert alert-danger" id="errormsg"></div>
                    <div class="alert alert-success" id="successmsg"></div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="field_title">Title:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="field_title" name="field_title" placeholder="Enter Title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="field_desc">Description:</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="field_desc" name="field_desc"> </textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="field_price">Percentage:</label>
                        <div class="col-sm-6"> 
                            <input type="text" class="form-control" id="field_price" name="field_price" placeholder="Enter Percentage">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="field_code">Promo Code:</label>
                        <div class="col-sm-6"> 
                            <input type="text" class="form-control uppercase" id="field_code" name="field_code" placeholder="Enter Promo Code">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="field_image">Image:</label>
                        <div class="col-sm-6"> 
                            <input type="file" class="form-control" id="field_image" name="field_image">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="field_banner">Banner:</label>
                        <div class="col-sm-6"> 
                            <input type="file" class="form-control" id="field_banner" name="field_banner">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="field_startDate">Start Date:</label>
                        <div class="col-sm-6"> 
                            <div class="input-group date form_datetime">
                                <input type="datetime-local" readonly class="form-control" id="field_startDate" name="field_startDate">
                                <span class="input-group-btn">
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="field_endDate">End Date:</label>
                        <div class="col-sm-6"> 

                            <div class="input-group date form_datetime">
                                <input type="datetime-local" readonly class="form-control" id="field_endDate" name="field_endDate">
                                <span class="input-group-btn">
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Submit</button>
                    <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- edit model -->
<div id="editModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"> 
            <form  action="<?= base_url("ManagePromoCodes/edit") ?>" method="post" enctype="multipart/form-data" class="form-horizontal" id="editPromoCode">
                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus-square"></i> Edit Promo Code</h4>
                </div>
                <div class="modal-body"> 
                    <div class="alert alert-danger" id="errormsg_edit"></div>
                    <div class="alert alert-success" id="successmsg_edit"></div>
                    <input type="hidden" name="edit_id" id="edit_id">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="field_edit_title">Title:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="field_edit_title" name="field_edit_title" placeholder="Enter Title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="field_edit_desc">Description:</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="field_edit_desc" name="field_edit_desc"> </textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="field_edit_price">Percentage:</label>
                        <div class="col-sm-6"> 
                            <input type="text" class="form-control" id="field_edit_price" name="field_edit_price" placeholder="Enter Percentage">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="field_edit_code">Promo Code:</label>
                        <div class="col-sm-6"> 
                            <input type="text" class="form-control uppercase" id="field_edit_code" name="field_edit_code" placeholder="Enter Promo Code">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="field_edit_image">Image:</label>
                        <div class="col-sm-6"> 
                            <input type="file" class="form-control" id="field_edit_image" name="field_edit_image">
                        </div>
                        <br/>
                        <br/>
                        <div class="col-sm-4">
                        </div>
                        <div class="col-sm-8">
                            <img src="" id="field_edit_image_src" class="img-responsive img-thumbnail" width="200px">
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="field_banner">Banner:</label>
                        <div class="col-sm-6"> 
                            <input type="file" class="form-control" id="field_edit_banner" name="field_edit_banner">
                        </div>
                        <br/>
                        <br/>
                        <div class="col-sm-4">
                        </div>
                        <div class="col-sm-8">
                            <img src="" id="field_edit_banner_src" class="img-responsive img-thumbnail" width="400px">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="field_edit_startDate">Start Date:</label>
                        <div class="col-sm-6"> 
                            <div class="input-group date form_datetime">
                                <input type="datetime-local" readonly class="form-control" id="field_edit_startDate" name="field_edit_startDate">
                                <span class="input-group-btn">
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="field_edit_endDate">End Date:</label>
                        <div class="col-sm-6"> 

                            <div class="input-group date form_datetime">
                                <input type="datetime-local" readonly class="form-control" id="field_edit_endDate" name="field_edit_endDate">
                                <span class="input-group-btn">
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Submit</button>
                    <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>