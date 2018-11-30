<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption  font-green">
                    <i class="icon-map-marker"></i>
                    <span class="caption-subject bold uppercase">Tracking History</span>
                </div>
                <div class="actions">
                    <div class="btn-group">
                        <a href="javascript: window.history.go(-1)"  title="Back" class="btn btn-success btn-md" >
                            <i class="fa fa-backward"></i> Back

                        </a>
                    </div>
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
                                    <img src="<?php echo $userDetails['profileImage']; ?>" onerror="this.src='<?php echo base_url(); ?>resource/default/default_profile.png'" class="img-responsive" style="height:100px; width:100px"  alt=""> </div>

                                <div class="profile-usertitle">
                                    <div class="profile-usertitle-name"> <?php echo $userDetails['userName']; ?>  </div>
                                </div>
                            </div>
                            <!-- END PORTLET MAIN -->
                            <!-- PORTLET MAIN -->

                            <!-- STAT -->
                           <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered table-hover table-checkable">

                                <tbody>
                                    <tr>

                                        <td class="numeric"> Email </td>
                                        <td class="numeric"><?php echo $userDetails['email']; ?> </td>

                                    </tr>
                                    <tr>

                                        <td class="numeric"> Mobile </td>
                                        <td class="numeric"><?php echo $userDetails['mobile']; ?> </td>

                                    </tr>
                                    <tr>
                                        <td class="numeric"> Battery </td>
                                        <td class="numeric">20%</td>
                                    </tr>
                                    <tr>
                                        <td class="numeric"> Speed </td>
                                        <td class="numeric">Active </td>
                                    </tr>
                                    <tr>
                                        <td class="numeric"> GEO Location </td>
                                        <td class="numeric">Active </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        </div>

                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
                <div class="col-md-8 col-sm-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light portlet-fit portlet-datatable bordered">
                        <!--<div id="dvMap" style="width: 100%; height: 500px">-->
                        <div id="map-canvas" style="width: 100%; height: 500px">
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            </div>
        </div>
    </div>
<script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrrhfZecpWI6v4_IaRqoQFUIOzw5WGSCs&callback=init2">
    </script>
    
    <script>
        var map;
           
        var markers = [];
        var timeout = 5000;
        var interval = null;

        function init2() {
            var flightPlanCoordinates = <?php echo json_encode($userDetails['track_location']); ?>;
             console.log(flightPlanCoordinates);
            var myLatlng = new google.maps.LatLng(parseFloat(flightPlanCoordinates[0].lat), parseFloat(flightPlanCoordinates[0].lng));
            var mapOptions = {
                zoom: 14,
                center: myLatlng,
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL
                },
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
            update_markers();
        }
        function update_markers() {
            console.log('update...');
                  
            $.getJSON('<?= base_url("ManageMap/getTrckLocation") . '/' . $userDetails['reserveId'].'/'.$userDetails['userId']; ?>',
                    function (d) {
                        console.log('update2...');
//                         for (var i = 0; i < d.length; i++) {
//                             console.log(parseFloat(d[i].lat));
//                            }
                        for (var i = 0; i < markers.length; ++i) {
                            markers[i].setMap(null);
                        }
                        markers = [];
                        var polylineCoordinates = [];
                        var l = d.length;
                        for (var x =0; x < l; x++) {
                            var f1 = parseFloat(d[x].lat);
                            var f2 = parseFloat(d[x].long);
                            if (f1 > 100) {
                                continue;
                                f1 = f1 / 100.0;
                                f2 = f2 / 100.0;
                            }

                            var position = new google.maps.LatLng(f1, f2);
                            var positionstart = new google.maps.LatLng(d[0].lat, d[0].long);
                            var positionend = new google.maps.LatLng(d[d.length-1].lat, d[d.length-1].long);
/*
                             * 
                             * @type google.maps.InfoWindow add start and end marker
                             */
                            var infowindow = new google.maps.InfoWindow();

                            var marker, i, contentString;
                            
                            marker = new google.maps.Marker({
                                position: positionstart,
                                map: map
                            });
                            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                                return function () {
                                    contentString = '<div>Start Ride</div>';
                                    infowindow.setContent(contentString);
                                    infowindow.open(map, marker);
                                }

                            })(marker, i));

                            marker = new google.maps.Marker({
                                position: positionend,
                                map: map
                            });
                            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                                return function () {
                                    contentString = '<div>End Ride</div>';
                                    infowindow.setContent(contentString);
                                    infowindow.open(map, marker);
                                }

                            })(marker, i));
                            
                            polylineCoordinates.push(position);
                        }
                        markers.push(new google.maps.Polyline({
                            map: map,
                            path: polylineCoordinates,
                            strokeColor: '#FF0000',
                            strokeOpacity: 1.0,
                            strokeWeight: 2
                        }));
                    }
            );
            interval = setTimeout(update_markers, timeout);
        }

        google.maps.event.addDomListener(window, 'load', init2);

        $(function () {
            $('#timeout_sel').change(function () {
                clearTimeout(interval);
                timeout = $(this).val();
                update_markers();
            });
        });
    </script>
    <script type="text/javascript">

        /*
         var markers =<?php //echo json_encode($userDetails['track_location'] );     ?>;
         
         //        var markers = [
         //            {
         //                "title": 'Alibaug',
         //                "lat": '18.641400',
         //                "lng": '72.872200',
         //                "description": 'Alibaug is a coastal town and a municipal council in Raigad District in the Konkan region of Maharashtra, India.'
         //            }
         //        ,
         //            {
         //                "title": 'Mumbai',
         //                "lat": '18.964700',
         //                "lng": '72.825800',
         //                "description": 'Mumbai formerly Bombay, is the capital city of the Indian state of Maharashtra.'
         //            }
         //        ,
         //            {
         //                "title": 'Pune',
         //                "lat": '18.523600',
         //                "lng": '73.847800',
         //                "description": 'Pune is the seventh largest metropolis in India, the second largest in the state of Maharashtra after Mumbai.'
         //            }
         //			   
         //
         //];
         window.onload = function () {
         var mapOptions = {
         center: new google.maps.LatLng(markers[0].lat, markers[0].lng),
         zoom: 100,
         mapTypeId: google.maps.MapTypeId.ROADMAP
         };
         var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
         var infoWindow = new google.maps.InfoWindow();
         var lat_lng = new Array();
         var latlngbounds = new google.maps.LatLngBounds();
         // markers.length
         for (i = 0; i < markers.length; i++) {
         console.log(markers[i]);
         var data = markers[i];
         var myLatlng = new google.maps.LatLng(data.lat, data.lng);
         lat_lng.push(myLatlng);
         var marker = new google.maps.Marker({
         position: myLatlng,
         map: map,
         title: data.title
         });
         latlngbounds.extend(marker.position);
         
         (function (marker, data) {
         google.maps.event.addListener(marker, "click", function (e) {
         infoWindow.setContent(data.description);
         infoWindow.open(map, marker);
         });
         })(marker, data);
         }
         map.setCenter(latlngbounds.getCenter());
         map.fitBounds(latlngbounds);*/

        //***********ROUTING****************//

        //Intialize the Path Array
        /*      var path = new google.maps.MVCArray();
         
         //Intialize the Direction Service
         var service = new google.maps.DirectionsService();
         
         //Set the Path Stroke Color
         var poly = new google.maps.Polyline({ map: map, strokeColor: '#4986E7' });
         
         //Loop and Draw Path Route between the Points on MAP
         for (var i = 0; i < lat_lng.length; i++) {
         if ((i + 1) < lat_lng.length) {
         var src = lat_lng[i];
         var des = lat_lng[i + 1];
         path.push(src);
         poly.setPath(path);
         service.route({
         origin: src,
         destination: des,
         travelMode: google.maps.DirectionsTravelMode.DRIVING
         }, function (result, status) {
         if (status == google.maps.DirectionsStatus.OK) {
         for (var i = 0, len = result.routes[0].overview_path.length; i < len; i++) {
         path.push(result.routes[0].overview_path[i]);
         }
         }
         });
         }
         }
         }*/
    </script>
    

<!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrrhfZecpWI6v4_IaRqoQFUIOzw5WGSCs&libraries=places&callback=fullMap" async defer></script>-->
