
<div class="row">
    <div class="col-md-12">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?= base_url("admin"); ?>">Home</a>
                    <i class="fa fa-circle"></i>
                </li>

                <li>
                    <span>View Map</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <?php
                $locations = array();
                foreach ($map as $key) {
                    if ($key['status'] == ACTIVE) {
                        $staus = 'Active';
                    } else {
                        $staus = 'Deactive';
                    }
                    $locations[] = array(
                        'scooterNumber' => $key['scooterNumber'],
                        'status' => $staus,
                        'location' => $key['location'],
                        'lat' => $key['lat'],
                        'lng' => $key['lng'],
                        'url' => base_url('manageScooter/view_scooter_details/'). encode($key['scooterNumber']),     
                        );
                }
                $markers = json_encode($locations);
                ?>
                <div class="caption  font-green">
                    <i class="icon-list"></i>
                    <span class="bold uppercase">Map View</span>
                </div>
            </div>
            <!-- BEGIN SAMPLE TABLE PORTLET-->
            <div class="portlet-body flip-scroll">
                <div id="fullmaps" style="width:100%;height:400px;border:10px;margin-bottom:10px; "></div>
            </div>
            <!-- END SAMPLE TABLE PORTLET-->   
            <div class="row">
                <div class="col-md-6">
                    <!-- BEGIN BLOCK BUTTONS PORTLET-->
                    <div class="portlet-body ">
                        <div class="row">
                            <div class="col-md-4" >
                                <div class="text-center">
                                    <!-- SIDEBAR USERPIC -->
                                    <div class="profile-userpic text-center">
                                        <img src="<?php echo base_url("resource/default/scooter_default.png"); ?>" onerror="this.src='<?php echo base_url("resource/default/scooter_default.png"); ?>'" class="img-thumbnail img-responsive" height="100px"  alt="Zeep image"> 
                                    </div>
                                    <div class="profile-usertitle"> 
                                        <strong>Scooto Status</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table table-striped table-condensed flip-content">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <strong class="uppercase">Idle : </strong> 
                                                </td>
                                                <td>
                                                    <span class="badge"><?php echo $counter['idelScooter']; ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong class="uppercase">Active :</strong>
                                                </td>
                                                <td>
                                                    <span class="badge"><?php echo $counter['activeScooter']; ?></span> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong class="uppercase">Under Maintenance :</strong>  
                                                </td>
                                                <td>
                                                    <span class="badge"><?php echo $counter['maintScooter']; ?></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END BLOCK BUTTONS PORTLET-->
                </div> 
                <div class="col-md-6">
                    <!-- BEGIN BLOCK BUTTONS PORTLET-->
                    <div class="portlet-body ">
                        <div class="row">
                            <div class="col-md-4" >
                                <div class="text-center">
                                    <!-- SIDEBAR USERPIC -->
                                    <div class="profile-userpic">
                                        <img src="<?php echo base_url("resource/default/user_default.png"); ?>" onerror="this.src='<?php echo base_url("resource/default/user_default.png"); ?>'" class="img-responsive img-thumbnail"  height="100px"  alt="User Image"> 
                                    </div>
                                    <div class="profile-usertitle"> 
                                        <strong>User Status</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table table-striped table-condensed flip-content">
                                        <tbody>
                                            <tr>
                                                <td> 
                                                    <strong class="uppercase">Registered :</strong> 
                                                </td>
                                                <td>
                                                    <span class="badge"><?php echo $counter['totalUser']; ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td> 
                                                    <strong class="uppercase">Active :</strong>
                                                </td>
                                                <td>
                                                    <span class="badge"><?php echo $counter['activeUser']; ?> </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong class="uppercase">Block Users : </strong>
                                                </td>
                                                <td>
                                                    <span class="badge"><?php echo $counter['notActiveUser']; ?> </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END BLOCK BUTTONS PORTLET-->
                </div> 
            </div>
        </div>
    </div>
</div>
<script>
    function fullMap() {
        var locations = <?= $markers ?>;
        var map = new google.maps.Map(document.getElementById('fullmaps'), {
            zoom: 12,
            center: new google.maps.LatLng(locations[0]['lat'], locations[0]['lng']),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow();

        var marker, i, contentString;

        for (i = 0; i <= locations.length; i++) {

            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i]['lat'], locations[i]['lng']),
                map: map
            });


            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    contentString = '<div>Scooter Id:' + locations[i]['scooterNumber'] + '</div><br><div>Status:' + locations[i]['status'] + '</div><br><div><a href="'+locations[i]['url']+'" style="text-align:center" class="btn btn-success scooterDetails" data-id="" target="_blank">View</a></div>';
                    infowindow.setContent(contentString);
                    infowindow.open(map, marker);
                }

            })(marker, i));

        }
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrrhfZecpWI6v4_IaRqoQFUIOzw5WGSCs&libraries=places&callback=fullMap" async defer></script>
