<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption  font-green">
                    <i class="icon-eye"></i>
                    <span class="caption-subject bold uppercase">Live Tracking</span>
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
                            <?php //print_r($userDetails['track_location']); ?>
                            <table class="table table table-striped table-condensed flip-content">

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
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
                <div class="col-md-8 col-sm-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light portlet-fit portlet-datatable bordered">
                        <input type="text" id="lonDeg" value=<?php  echo json_encode($userDetails['track_location']); ?>>
                        <!--<div id="dvMap" style="width: 100%; height: 500px">-->
                        <div id="map" style="width: 100%; height: 500px">
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        // This example creates a 2-pixel-wide red polyline showing the path of
        // the first trans-Pacific flight between Oakland, CA, and Brisbane,
        // Australia which was made by Charles Kingsford Smith.

//        $(document).ready(function () {
//            var callAjax = function () {
// initMap();
//    setInterval(function () {
//        initMap()
//    }, 2000);
    //setInterval( function(){ callInterval() } , 2000); 
               
//                    $.ajax({
//                        type: "POST",
//                        url: "<?= base_url("ManageMap/getTrckLocation") . '/' . $userDetails['reserveId']; ?>",
//                        dataType: "json",
//                        success: function (data)
//                        {
//                            var flightPlanCoordinates = data;
//                            },
//                                timeout: 3000
//                    });
//                    
//                   
               
                
//                    if(!flightPlanCoordinates){
//                    var flightPlanCoordinates = <?php //echo json_encode($userDetails['track_location']); ?>;
//                    }
                     function initMap() {
//                          AjaxGet = function () {
//
//                    var response = $.ajax({
//                        type: "POST",
//                        url: "<?= base_url("ManageMap/getTrckLocation") . '/' . $userDetails['reserveId']; ?>",
//                        dataType: "json",
//                        async: false,
//                        success: function (response) {
//
//                        }
//                    }).responseText;
//                    return response;
//                }
                //var flightPlanCoordinates2 = new Array();
               // setInterval( function(){ var flightPlanCoordinates2= AjaxGet() } , 2000); 
                          //var flightPlanCoordinates2= AjaxGet();
                           //flightPlanCoordinates2.push(flightPlanCoordinates3);
               // console.log(flightPlanCoordinates2);
                var flightPlanCoordinates3 = $("#lonDeg").val();
             var flightPlanCoordinates4 = JSON.parse(JSON.stringify(flightPlanCoordinates3));
                //var flightPlanCoordinates = JSON.stringify(flightPlanCoordinates2);
               
                            var flightPlanCoordinates2 = <?php echo json_encode($userDetails['track_location']); ?>;
    //console.log(flightPlanCoordinates);    
    console.log(flightPlanCoordinates4);    
    //console.log(flightPlanCoordinates[0].lat); 
    var map = new google.maps.Map(document.getElementById('map'), {
                                zoom: 15,
                                center: {lat: parseFloat(flightPlanCoordinates[0].lat), lng: parseFloat(flightPlanCoordinates[0].lng)},
                                mapTypeId: 'terrain'
                            });
                            for (var i = 0; i < flightPlanCoordinates.length; i++) {
                                flightPlanCoordinates[i].lat = parseFloat(flightPlanCoordinates[i].lat);
                                flightPlanCoordinates[i].lng = parseFloat(flightPlanCoordinates[i].lng);
                            }
                            var flightPath = new google.maps.Polyline({
                                path: flightPlanCoordinates,
                                geodesic: true,
                                strokeColor: '#FF0000',
                                strokeOpacity: 1.0,
                                strokeWeight: 2
                            });

                            flightPath.setMap(map);

                            /*
                             * 
                             * @type google.maps.InfoWindow add start and end marker
                             */
                            var infowindow = new google.maps.InfoWindow();

                            var marker, i, contentString;
                            var count = flightPlanCoordinates.length - 1
                            marker = new google.maps.Marker({
                                position: new google.maps.LatLng(parseFloat(flightPlanCoordinates[0].lat), parseFloat(flightPlanCoordinates[0].lng)),
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
                                position: new google.maps.LatLng(parseFloat(flightPlanCoordinates[count].lat), parseFloat(flightPlanCoordinates[count].lng)),
                                map: map
                            });
                            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                                return function () {
                                    contentString = '<div>End Ride</div>';
                                    infowindow.setContent(contentString);
                                    infowindow.open(map, marker);
                                }

                            })(marker, i));

                        
                }
//            }
//            setInterval(callAjax, 500);
//        });
    </script>
    <script type="text/javascript">

        /*
         var markers =<?php //echo json_encode($userDetails['track_location'] );    ?>;
         
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
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrrhfZecpWI6v4_IaRqoQFUIOzw5WGSCs&callback=initMap">
    </script>

<!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrrhfZecpWI6v4_IaRqoQFUIOzw5WGSCs&libraries=places&callback=fullMap" async defer></script>-->
