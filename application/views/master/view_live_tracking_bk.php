<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-eye font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">Live Tracking</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light portlet-fit portlet-datatable bordered">
                        <div class="portlet-body ">
                            <div class="profile-sidebar-portlet">
                                <!-- SIDEBAR USERPIC -->
                                <div class="profile-userpic">
                                    <img src="<?php echo base_url(); ?>/assets/pages/media/profile/profile_user.jpg" class="img-responsive" style="height:100px; width:100px"  alt=""> </div>
                                <div class="profile-usertitle">
                                    <div class="profile-usertitle-name"><?php // echo $scooterDetails['scooterNumber']  ?> </div>
                                </div>
                            </div>
                            <!-- STAT -->
                            <table class="table table table-striped table-condensed flip-content">
                                <tbody>
                                    <tr>
                                        <td class="numeric"> User </td>
                                        <td class="numeric">50km </td>
                                    </tr>
                                    <tr>
                                        <td class="numeric"> Battery </td>
                                        <td class="numeric">20%</td>
                                    </tr>
                                    <tr>
                                        <td class="numeric"> Status </td>
                                        <td class="numeric">Active </td>
                                    </tr>
                                    <tr>
                                        <td class="numeric"> Speed </td>
                                        <td class="numeric">Active </td>
                                    </tr>
                                    <tr>
                                        <td class="numeric"> GEO Location </td>
                                        <td class="numeric">Active </td>
                                    </tr>
                                    <tr>
                                        <td class="numeric"> Current Location </td>
                                        <td class="numeric">Active </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row list-separated profile-stat">
                                <div class="form-group" style="text-align: center;">
                                    <input type="submit" class="btn green submit" value="Speed Limit Alert">
                                    <input type="submit" class="btn green submit" value="Geo Location Alert">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
                <div class="col-md-8 col-sm-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light portlet-fit portlet-datatable bordered">
                            <?php
//                foreach ($map as $key) {
//                    $locations[] = array('scooterNumber' => $key['scooterNumber'], 'status' => $staus, 'location' => $key['location'], 'lat' => $key['lat'], 'lng' => $key['lng']);
//                }
                            $locations = '';
                            $markers = json_encode($locations);
                            //print_r($markers);
                            ?>
                            
               
                        <!-- BEGIN SAMPLE TABLE PORTLET-->
                        <div class="portlet-body flip-scroll">

                            <div id="fullmaps" style="width:100%;height:400px;border:10px;margin-bottom:10px; "></div>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function fullMap() {
        var locations = <?php echo $markers ?>;
        var map = new google.maps.Map(document.getElementById('fullmaps'), {
            zoom: 12,
            center: new google.maps.LatLng(1.4069761, 103.90154849999999),
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
                    contentString = '<div>Scooter Id:' + locations[i]['scooterNumber'] + '</div><br><div>Status:' + locations[i]['status'] + '</div><br><div><a href="" style="text-align:center" class="btn btn-info scooterDetails" data-id="">View</a></div>';
                    infowindow.setContent(contentString);
                    infowindow.open(map, marker);
                }

            })(marker, i));

        }
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrrhfZecpWI6v4_IaRqoQFUIOzw5WGSCs&libraries=places&callback=fullMap" async defer></script>
