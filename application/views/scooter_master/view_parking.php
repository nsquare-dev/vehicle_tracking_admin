<style>
    .pac-container {
        z-index: 20000 !important;
        display: inline-block;
        position: relative;
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
                    <span>Manage Parking</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-green">
                    <i class="icon-list"></i>
                    <span class="bold uppercase">Parking Listing</span>
                </div>
                <div class="actions">
                    <div class="btn-group">
                        <a href="#addParking" data-toggle="modal" class="btn btn-success btn-md">
                            <i class="fa fa-plus-circle"></i> Create Parking                            
                        </a>
                    </div>
                </div>
            </div>
            <!-- BEGIN SAMPLE TABLE PORTLET-->

            <div class="body">
               <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered table-hover table-checkable">
                    <thead>
                        <tr>
                            <th> Sr.No </th>
                            <th> Name </th>
                            <th> Location </th>
                            <th> Status </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($parking as $key => $parking) { ?>
                            <tr>
                                <td> <?php echo $key + 1; ?> </td>
                                <td> <?php echo $parking['parkingName'] ?> </td>
                                <td> <?php echo $parking['parkingLocation'] ?> </td>
                                <td> <?php
                                    if ($parking['status'] == ACTIVE) {
                                        echo '<h6><span class="label label-success">Active</span></h6>';
                                    } else {
                                        echo '<h6><span class="label label-danger">De-Active</span></h6>';
                                    }
                                    ?> 
                                </td>
                                <td> 
                                    <button data-repeater-create class="btn btn-success  btn-sm mt-repeater-add edit-parking" data-value='<?= json_encode($parking) ?>' title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <?php if ($parking['status'] == ACTIVE) { ?>
                                        <a class="btn btn-sm btn-danger mt-repeater-add actionModalConfirm" data-repeater-create data-action-title="Deactive" data-action-desc="Are you sure want to deactivate this parking?" data-id="<?php echo $parking['id']; ?>" data-value="<?php echo NOTACTIVE; ?>" data-url="<?php echo base_url('manageScooter/parkingStatus'); ?>" data-toggle="modal" href="javascript:void(0)" title="Set De-Activate">
                                            <i class="fa fa-times-circle-o"></i>
                                        </a>
                                    <?php } else { ?>
                                        <a class="btn btn-sm btn-success mt-repeater-add actionModalConfirm" data-repeater-create data-action-title="Active" data-action-desc="Are you sure want to activate this parking?" data-id="<?php echo $parking['id']; ?>" data-value="<?php echo ACTIVE; ?>" data-url="<?php echo base_url('manageScooter/parkingStatus'); ?>" data-toggle="modal" href="javascript:void(0)" title="Set Activate">
                                            <i class="fa fa-check-circle-o"></i>
                                        </a>
                                    <?php }
                                    ?> 
                                    <a class="btn btn-sm btn-danger mt-repeater-add actionModalConfirm" data-repeater-create data-action-title="Remove" data-action-desc="Are you sure want to remove this parking?" data-id="<?php echo $parking['id']; ?>" data-value="<?php echo DELETED; ?>" data-url="<?php echo base_url('manageScooter/removeParking'); ?>" data-toggle="modal" href="javascript:void(0)" title="Remove Parking">
                                        <i class="fa fa-remove"></i>
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
<div id="addParking" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="#" class="addservicesForm" id="addparking" enctype="multipart/form-data">
                <div class="modal-header bg-green">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title"> <i class="fa fa-plus-square"></i>  Create Parking</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" id="errormsg"></div>
                    <div class="alert alert-success" id="successmsg"></div>
                    <div class=""  data-always-visible="1" data-rail-visible="1">
                        <div class="form-group">
                            <label class="control-label">Name<span class="required">*</span></label>
                            <input type="text" name="parkingName" class="form-control modalField"  placeholder="Enter Name" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Location<span class="required">*</span></label><br>
                            <input id="parkingLocation" name="parkingLocation" class="form-control searchInput" type="text" placeholder="Enter Location" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    
                    <button type="button" class="btn btn-success addparking" data-url="<?php echo base_url("manageScooter/addParking"); ?>" >Save</button>                  
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

                <button type="button"  class="btn btn-success confirmAction">Yes <span class="actionEvent"></span></button>
                <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>

            </div>
        </div>
    </div>
</div>



<div id="editModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="<?php echo base_url("manageScooter/updateParking"); ?>" class="form form-horizontal"  id="editParking" enctype="multipart/form-data">

                <div class="modal-header bg-green">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title"><i class="fa fa-pencil-square"></i> Edit Parking</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" id="errormsg_edit"></div>
                    <div class="alert alert-success" id="successmsg_edit"></div>
                    <div data-always-visible="1" data-rail-visible="1"> 
                        <div class="form-group">
                            <label class="control-label col-sm-2">Name<span class="required">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" name="edit_parkingName" id="edit_parkingName" class="form-control modalField"  placeholder="Enter Name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label  col-sm-2">Location<span class="required">*</span></label>
                            <div class="col-sm-10">
                                <input  type="text"  name="edit_parkingLocation" id="edit_parkingLocation" class="form-control searchInput"placeholder="Enter Location" required>
                            </div>                            
                        </div>

                        <input type="hidden" name="edit_id" id="edit_id">
                    </div>
                </div>
                <div class="modal-footer">                                        
                    <button type="submit" class="btn btn-success" >Update</button>
                    <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: -33.8688, lng: 151.2195},
            zoom: 10
        });
        var value = document.getElementsByClassName('searchInput');

        $.each(input, function (index, value) {
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(value);
            var autocomplete = new google.maps.places.Autocomplete(value);
            autocomplete.bindTo('bounds', map);
        });
 
        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
            map: map,
            anchorPoint: new google.maps.Point(0, -29)
        });

        autocomplete.addListener('place_changed', function () {
            infowindow.close();
            marker.setVisible(false);
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                window.alert("Autocomplete's returned place contains no geometry");
                return;
            }

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
            marker.setIcon(({
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(35, 35)
            }));
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }

            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);
        });
    }

    function initAutocomplete() {
        var input = document.getElementsByClassName('searchInput');
        
        $.each(input, function (index, value) {
            var searchBox = new google.maps.places.SearchBox(value);
        });
        
    }

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrrhfZecpWI6v4_IaRqoQFUIOzw5WGSCs&libraries=places&callback=initAutocomplete"></script>