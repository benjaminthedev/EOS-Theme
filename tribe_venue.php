<?php

/*
Template Name: venue template
*/

get_header();
?>
<div class="l-main">
	<div class="l-main-h i-cf">
	   
<?php
    // TO SHOW THE PAGE CONTENTS
    while ( have_posts() ) : the_post(); ?> <!--Because the_content() works only inside a WP Loop -->
        <div class="entry-content-page">
            <?php the_content(); ?> <!-- Page Content -->
        </div><!-- .entry-content-page -->

    <?php
    endwhile; //resetting the page loop
    wp_reset_query(); //resetting the page query
    ?>
    <div class="g-cols valign_top">
    <div class="vc_col-sm-8 wpb_column vc_column_container"> 
<?php 	

    
global $wpdb;
$locationId = $_GET['id'];
$eventdata ='';
$Query = "SELECT * FROM `".$wpdb->prefix."postmeta` WHERE `meta_key` = '_EventVenueID' AND meta_value = '$locationId'";
	$result=$wpdb->get_results($Query);
	if(!empty($result)):
	    $i=0;
		foreach($result as $key=>$ev):	
		    $startdate=get_post_meta($ev->post_id,'_EventStartDate',true);
		    $startdate = date("D d-M-Y", strtotime($startdate));
		    $ticketurl=get_post_meta($ev->post_id,'_EventURL',true);
		    
		    $eventdata .= '<tr><td>'.get_the_title($ev->post_id).'</td><td>'.$startdate.'</td><td><div class="btn-link"><a href="'.$ticketurl.'"  target="_blank">Book Ticket</a></div></td></tr>';
		  
		         $latitude=get_post_meta($ev->post_id,'_latitude',true);
		         $longitude=get_post_meta($ev->post_id,'_longitude',true);
		    
				$Address=get_post_meta($locationId,'_VenueAddress',true);
				$City=get_post_meta($locationId,'_VenueCity',true);
				$Country=get_post_meta($locationId,'_VenueCountry',true);
				$Zip=get_post_meta($locationId,'_VenueZip',true);
				$State=get_post_meta($locationId,'_VenueState',true);
				$fulladdress = $Address.' '.$City.' '.$Country.'-'.$Zip;
				
				$datamasp[$i] 				= 	new stdClass();
				$datamasp[$i]->DisplayText		= get_the_title($ev->post_id);
				$datamasp[$i]->ADDRESS		= $fulladdress;
				$datamasp[$i]->LatitudeLongitude		= $latitude.','.$longitude;
				$datamasp[$i]->MarkerId		= 'event';
				
					$i++;
		 endforeach;
	endif;
//	echo '<pre>'; print_r($datamasp); exit;
				$datajson = json_encode($datamasp); 
				$Address1=get_post_meta($locationId,'_VenueAddress',true);
				$City1=get_post_meta($locationId,'_VenueCity',true);
				$Country1=get_post_meta($locationId,'_VenueCountry',true);
				$Zip1=get_post_meta($locationId,'_VenueZip',true);
				$State1=get_post_meta($locationId,'_VenueState',true);
				$fulladdress1 = $Address1.' '.$City1.' '.$Country1.'-'.$Zip1;
?>
<div class="eventbox">
    <p class="eventlocation-name"><?php echo get_the_title($locationId); ?></p>
    <div class="w-separator type_default size_small thick_2 style_solid color_border cont_none"><span class="w-separator-h"></span></div>
     <div id="map-canvas1" style="width: 600px; height: 200px;"></div>
     
     <p class="venue-address"><?php echo $fulladdress1; ?></p>
     <div class="btn-link"><a href="<?php echo get_post_meta($locationId,'_VenueURL',true); ?>" target="_blank" />Go to website</a></div>
     
     <div class="venue-event">
         <h2>Performances</h2>
         <table class="venue_titles table">
            <thead>
            <tr>
                <th>Name.</th>
                <th>Performance Date</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
           <?php if(isset( $eventdata) && !empty( $eventdata)){
           echo $eventdata;
           }else{ echo '<tr><td colspan="3">Currently no screenings available.</td></tr>'; } ?>
            </tbody>
        </table>
         
     </div>
</div>
       
	   
	</div>
	<div class="vc_col-sm-4 wpb_column vc_column_container">
	<div class="vc_column-inner">
	<div class="wpb_wrapper">
	<div class="wpb_widgetised_column wpb_content_element">
		<div class="wpb_wrapper custom-venuesidebar"><br/>
			  <?php dynamic_sidebar( 'location-page-sidebar ' ); ?>
		
		</div>
	</div>
</div>
</div>
</div>
    
</div> 	    
	</diV>
</div>

 <?php 
 if(isset($latitude) && !empty($latitude)){ $centerlat = $latitude; }else{ $centerlat = '50.822530'; }
 if(isset($longitude) && !empty($longitude)){ $centerlong = $longitude; }else{ $centerlong = '-0.137163'; }
 ?>
<script type="text/javascript">
				var map;
				var geocoder;
				var marker;
				var people = new Array();
				var latlng;
				var infowindow;
				
        jQuery(document).ready(function() {
            
                var lat_event = jQuery("#lat_event").val();
                var long_event = jQuery("#long_event").val();
                
                if(long_event ==''){
                    long_event = '-0.137163';
                }
                
                if(lat_event ==''){
                    lat_event = '50.822530';
                }
                
                jQuery.ajax({
                    data: {action: "getnearbylist", lat:lat_event, lon:long_event,distance:"20"},
                    type: "post",
                    url: my_ajax_object.ajax_url,
                    success: function(data) {
                    jQuery(".event-list-show").html(data);
                    }
                });
        
        
            ViewCustInGoogleMap();
            
        });

        function ViewCustInGoogleMap() {

            var mapOptions = {
                center: new google.maps.LatLng(<?php echo $centerlat.','.$centerlong; ?>),  
                zoom: 7,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("map-canvas1"), mapOptions);
           var data = '<?php echo $datajson; ?>';
            people = JSON.parse(data); 

            for (var i = 0; i < people.length; i++) {
                setMarker(people[i]);
                }
        }

        function setMarker(people) {
            geocoder = new google.maps.Geocoder();
            infowindow = new google.maps.InfoWindow();
            if ((people["LatitudeLongitude"] == null) || (people["LatitudeLongitude"] == 'null') || (people["LatitudeLongitude"] == '')) {
                geocoder.geocode({ 'address': people["Address"] }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        latlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                        marker = new google.maps.Marker({
                            position: latlng,
                            map: map,
                            draggable: false,
                            html: people["DisplayText"],
                            icon: "images/marker/" + people["MarkerId"] + ".png"
                        });
                        
                        google.maps.event.addListener(marker, 'click', function(event) {
                            infowindow.setContent(this.html);
                            infowindow.setPosition(event.latLng);
                            infowindow.open(map, this);
                        });
                    }
                    else {
                        alert(people["DisplayText"] + " -- " + people["Address"] + ". This address couldn't be found");
                    }
                });
            }
            else {
                var latlngStr = people["LatitudeLongitude"].split(",");
                var lat = parseFloat(latlngStr[0]);
                var lng = parseFloat(latlngStr[1]);
                latlng = new google.maps.LatLng(lat, lng);
                marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    draggable: false,               // cant drag it
                    html: people["DisplayText"]    // Content display on marker click
                    //icon: "images/marker.png"       // Give ur own image
                });
                //marker.setPosition(latlng);
                //map.setCenter(latlng);
                google.maps.event.addListener(marker, 'click', function(event) {
                    infowindow.setContent(this.html);
                    infowindow.setPosition(event.latLng);
                    infowindow.open(map, this);
                });
            }
        }

	function get_latlong(address){
				var geocoder = new google.maps.Geocoder();
				geocoder.geocode( { 'address': address}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
					var latitude = results[0].geometry.location.lat();
					var longitude = results[0].geometry.location.lng();
					jQuery("#lat_event").val(latitude);
					jQuery("#long_event").val(longitude);
					var siteurl = '<?php echo site_url(); ?>';
					jQuery(".event-list-show").html("<img width='30px' height='30px' src='"+siteurl+"/wp-content/uploads/2017/05/Loading_gif.gif'/>");
					jQuery.ajax({
                            data: {action: "getnearbylist", lat:latitude, lon:longitude,distance:"100"},
                            	 type: "post",
                            	  url: my_ajax_object.ajax_url,
                            	 success: function(data) {
                            	 jQuery(".event-list-show").html(data);
                            		    	}
        		    	
				
				});
			}
				});
	}
		
			
	/*autocomplete google location*/
	
	/*********on select event list sidebar**********/
	
	jQuery('.event-get').on('change', function() {
	    var address = this.value;
	    	jQuery('#full_address').val(address);		
             	get_latlong(address);
            });
    </script>
    
<?php

get_footer();


?>