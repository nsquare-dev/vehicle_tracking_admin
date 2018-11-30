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
                    <span>Live Tracking</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">

                <div class="caption  font-green">
                    <i class="icon-list"></i>
                    <span class="bold uppercase">Ride Scooto List</span>
                </div>
            </div>
            <!-- BEGIN SAMPLE TABLE PORTLET-->
            <div class="portlet-body flip-scroll">
                <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered table-hover table-checkable">
                    <thead>
                        <tr>
                            <th> Sr.No </th>
                            <th> Zeep ID </th>
                            <th> User Name </th>
                            <th> Location </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($scooter as $key => $scooter) { ?>
                            <tr>
                                <td > <?php echo $key + 1; ?> </td>
                                <td > <?php echo $scooter['scooterNumber'] ?> </td>
                                <td > <?php echo $scooter['userName'] ?> </td>
                                <td > <?php echo $scooter['startLocation'] ?> </td>
                               <td >  
                                    <a href="<?php echo base_url("ManageMap/view_live_tracking/"); ?><?php echo encode($scooter['id']) . '/' . encode($scooter['uId']); ?>"  data-repeater-create class="btn btn-info mt-repeater-add" title="View Tracking">
                                        <i class="fa fa-eye"></i>
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

<!--<input type="text" name="location" placeholder="" class="form-control modalField pac-container" style="width:95%" id="pac-input" required>-->


                            <input id="searchInput" name="location" class="control" type="text" placeholder="Enter a location" style="width: 100%;height: 34px;padding: 6px 12px;background-color: #fff;border: 1px solid #c2cad8;border-radius: 4px;-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);box-shadow: inset 0 1px 1px rgba(0,0,0,.075);-webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;">

                            <input name="lat" type="text" value="" hidden="" name="lat">
                            <input name="lng" type="text" value="" hidden="" name="lng">
                            <div id="map" style="width:100%;height:400px;border:10px;margin-bottom:10px; "></div>

                        </div>

                    </div>
                </div>
                <div class="modal-footer">

                    <input type="submit" class="btn btn-success" value="Save">
                    <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>