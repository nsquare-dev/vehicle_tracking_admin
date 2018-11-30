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
                    <span>Instant Support</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">

                <div class="caption  font-green">
                    <i class="icon-list"></i>
                    <span class="bold uppercase">Instant Support</span>
                </div>
            </div>
            <!-- BEGIN SAMPLE TABLE PORTLET-->
            <div class="portlet-body flip-scroll">
                <table id="example" class="display table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th> Sr.No </th>
                            <th> Date </th>
                            <th> Scooter Id </th>
                            <th> Email </th>
                            <th> Type </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($instantList as $key => $instant) { ?>
                            <tr>
                                <td > <?php echo $key + 1; ?> </td>
                                <td > <?php echo date('d-m-Y', strtotime($instant['createdDate'])); ?> </td>
                                <td > <?php echo $instant['scooterNumber'] ?> </td>
                                <td > <?php echo $instant['email'] ?> </td>
                                <td > <?php echo $instant['name'] ?> </td>
                                <td > 
                                    <a href="<?php echo base_url("ManageInstant/instant_details/"); ?><?php echo encode($instant['id']); ?>"  data-repeater-create class="btn btn-sm btn-success mt-repeater-add" title="View Details">
                                        <i class="fa fa-eye"></i>
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


<div id="addModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="<?php echo base_url(); ?>manageScooter/addScooter" class="addservicesForm" enctype="multipart/form-data">

                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title"><i class="fa fa-plus-square"></i> Add Scooter</h4>
                </div>
                <div class="modal-body">
                    <div class="" style="//height:150px" data-always-visible="1" data-rail-visible="1">

                        <div class="form-group">
                            <label class="control-label">Scooter Number<span class="required">*</span></label>
                            <input type="text" name="scooteNumber" class="form-control modalField" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Tracker Id<span class="required">*</span></label>
                            <input type="text" name="tarckId" class="form-control modalField" required>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Location<span class="required">*</span></label><br>

                            <input id="searchInput" name="location" class="control" type="text" placeholder="Enter a location" style="width: 100%;height: 34px;padding: 6px 12px;background-color: #fff;border: 1px solid #c2cad8;border-radius: 4px;-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);box-shadow: inset 0 1px 1px rgba(0,0,0,.075);-webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;">

                            <input name="lat" type="text" value="" hidden="" name="lat">
                            <input name="lng" type="text" value="" hidden="" name="lng">
                            <div id="map" style="width:100%;height:400px;border:10px;margin-bottom:10px; "></div>

                        </div>
 
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                    <input type="submit" class="btn btn-success" value="Save">
                </div>
            </form>
        </div>
    </div>
</div>  
