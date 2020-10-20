<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );


/**
 * Include all the needed files
 *
 * (!) Note for Clients: please, do not modify this or other theme's files. Use child theme instead!
 */


if ( ! defined( 'US_ACTIVATION_THEMENAME' ) ) {

	define( 'US_ACTIVATION_THEMENAME', 'Impreza' );

}


/*add data to custom table start*/


function update_custom_event_location() {

	global $wpdb;


	$Query = "SELECT e.ID,e.post_title,e.post_type,e.post_status,lc.meta_key,lc.meta_value FROM `" . $wpdb->prefix . "posts` as e LEFT JOIN `" . $wpdb->prefix . "postmeta` as lc on lc.post_id=e.ID WHERE e.`post_type` ='tribe_events' AND e.post_status ='publish' AND (lc.meta_key !='_latitude' and lc.meta_key !='_longitude') group by lc.post_id";

	$result = $wpdb->get_results( $Query );

	if ( ! empty( $result ) ):

		foreach ( $result as $key => $ev ):

			$locationId = get_post_meta( $ev->ID, '_EventVenueID', true );

			$latitude = get_post_meta( $ev->ID, '_latitude', true );

			$City = get_post_meta( $locationId, '_VenueCity', true );

			if ( isset( $locationId ) && ! empty( $locationId ) && empty( $latitude ) ) {


				$Address = get_post_meta( $locationId, '_VenueAddress', true );

				$City = get_post_meta( $locationId, '_VenueCity', true );


				$Country = get_post_meta( $locationId, '_VenueCountry', true );

				$Zip = get_post_meta( $locationId, '_VenueZip', true );

				$State = get_post_meta( $locationId, '_VenueState', true );

				$fulladdress = $Address . ' ' . $City . ' ' . $Country;

				$full = getLatLong( $fulladdress );

				//	echo '<pre>'; print_r($full); echo '</pre>';

				$latitude = $full['latitude'];

				$longitude = $full['longitude'];

				update_post_meta( $ev->ID, "_latitude", $latitude );

				update_post_meta( $ev->ID, "_longitude", $longitude );

			}


		endforeach;

	endif;

}


if ( isset( $_POST ) && ! empty( $_POST ) && ( $_POST['aggregator']['csv']['content_type'] == 'tribe_events' ) ) {

	update_custom_event_location();

}


$us_theme_supports = array(

	'plugins' => array(

		'js_composer' => '/framework/plugins-support/js_composer/js_composer.php',

		'Ultimate_VC_Addons' => '/framework/plugins-support/Ultimate_VC_Addons.php',

		'revslider' => '/framework/plugins-support/revslider.php',

		'contact-form-7' => null,

		'gravityforms' => '/framework/plugins-support/gravityforms.php',

		'woocommerce' => '/framework/plugins-support/woocommerce/woocommerce.php',

		'codelights' => '/framework/plugins-support/codelights.php',

		'wpml' => '/framework/plugins-support/wpml.php',

		'bbpress' => '/framework/plugins-support/bbpress.php',

		'tablepress' => '/framework/plugins-support/tablepress.php',

		'the-events-calendar' => '/framework/plugins-support/the_events_calendar.php',

	),

);


require dirname( __FILE__ ) . '/framework/framework.php';


unset( $us_theme_supports );

function event_map_custom() {

	echo '<div class="sidebar-event">';

	echo do_shortcode( '[locations_map country="IN" ]' );

	echo do_shortcode( '[events_list country="IN"]' );

	echo '</div>';
}

add_shortcode( 'event_map', 'event_map_custom' );


function get_location_code_event() {

	$siteurl = get_the_permalink();

	$return = '<input id="full_address" value="" type="hidden" ><input type="hidden" id="lat_event" value=""/><input type="hidden" id="long_event" value=""/><input type="hidden" id="event_datajson" value=""/>

			<div class="eventmap-list"><input id="searchTextField" value="" type="text" size="50"><div id="map-canvas" style="width: 334px; height: 200px;">

    </div><div class="event_search_box">' . event_sidebarlist() . '</div><div class="event-list-show"></div></div>

			<script type="text/javascript" charset="utf-8">function getLocation() { 

      if (navigator.geolocation) {

          navigator.geolocation.getCurrentPosition(savePosition, positionError, {timeout:10000});

      }

		}

  function positionError(error) {

  var errorCode = error.code;

		var message = error.message;

		if(errorCode==2 || errorCode==3 ){

		var latLong;

            $.getJSON("https://ipinfo.io", function(ipinfo){

                console.log("Found location ["+ipinfo.loc+"] by ipinfo.io");

                latLong = ipinfo.loc.split(",");

               var geocoder = new google.maps.Geocoder();

            		var latLng = new google.maps.LatLng(latLong);

            		if (geocoder) {

                    geocoder.geocode({ "latLng": latLng}, function (results, status) {

                    if (status == google.maps.GeocoderStatus.OK) {

                    var address= results[0].formatted_address;	

            		jQuery("#searchTextField").attr("value", address);

            		ViewCustInGoogleMap();

                   }

            	}); 

              }

            });

		}

  }

  function savePosition(position) {

		var latitude=position.coords.latitude;

		var longitude=position.coords.longitude;

		jQuery("#lat_event").val(latitude);

		jQuery("#long_event").val(longitude);

		var geocoder = new google.maps.Geocoder();

		var latLng = new google.maps.LatLng(latitude, longitude);

		if (geocoder) {

        geocoder.geocode({ "latLng": latLng}, function (results, status) {

        if (status == google.maps.GeocoderStatus.OK) {

        var address= results[0].formatted_address;	

		jQuery("#searchTextField").attr("value", address);

		ViewCustInGoogleMap();

       }

	}); 

  }

  }

	function GetParameterValues(param) {  

		var url = window.location.href.slice(window.location.href.indexOf("?") + 1).split("&");  

		for (var i = 0; i < url.length; i++) {  

				var urlparam = url[i].split("=");  

				if (urlparam[0] == param) {

						return urlparam[1];

				}

				}

	}getLocation();

	

	</script>';


	return $return;

}

add_shortcode( 'get_location_event', 'get_location_code_event' );

//add_shortcode( 'nearby_event', 'getnearbyevent' );


function getnearbylist() {


	$latitude = $_POST['lat'];

	$longitude = $_POST['lon'];

	$distance = $_POST['distance'];


	global $wpdb;

	$Query = "SELECT ID, post_title, post_type,( 6371 * acos ( cos ( radians( $latitude ) ) * cos( radians( latitude.meta_value ) ) * cos( radians( longitude.meta_value ) - radians( $longitude ) ) + sin ( radians( $latitude ) ) * sin( radians( latitude.meta_value ) ) ) ) AS distance FROM " . $wpdb->prefix . "posts INNER JOIN " . $wpdb->prefix . "postmeta latitude ON (ID = latitude.post_id AND latitude.meta_key = '_latitude') INNER JOIN " . $wpdb->prefix . "postmeta longitude ON (ID = longitude.post_id AND longitude.meta_key = '_longitude') 

WHERE post_type = 'tribe_events' AND post_status = 'publish' HAVING distance < $distance ORDER BY distance";

	$result = $wpdb->get_results( $Query );

	if ( ! empty( $result ) ):

		$i = 0;

		$locatId = array();

		foreach ( $result as $key => $ev ):

			$locationId = get_post_meta( $ev->ID, '_EventVenueID', true );


			if ( ! in_array( $locationId, $locatId ) ) {

				echo '<div class="eventlist"><div class="event-title"> <p class="linktag">	<span><i class="fa fa-map-marker" aria-hidden="true"></i></span><a href="' . site_url() . '/venue/?id=' . $locationId . '">' . get_the_title( $locationId ) . '</a> </p></div></div>';

				$locatId[] = $locationId;

			}

			$i ++;

		endforeach;

	else:

		echo '<div class="eventlist"><div class="event-title"> NO SCREENINGS FOUND NEAR YOU</div>';


	endif;


	exit;

}


function getnearbyevent() {


	$latitude = $_POST['lat'];

	$longitude = $_POST['lon'];

	$distance = $_POST['distance'];


	global $wpdb;

	$Query = "SELECT ID, post_title, post_type,( 6371 * acos ( cos ( radians( $latitude ) ) * cos( radians( latitude.meta_value ) ) * cos( radians( longitude.meta_value ) - radians( $longitude ) ) + sin ( radians( $latitude ) ) * sin( radians( latitude.meta_value ) ) ) ) AS distance FROM " . $wpdb->prefix . "posts INNER JOIN " . $wpdb->prefix . "postmeta latitude ON (ID = latitude.post_id AND latitude.meta_key = '_latitude') INNER JOIN " . $wpdb->prefix . "postmeta longitude ON (ID = longitude.post_id AND longitude.meta_key = '_longitude') 

WHERE post_type = 'tribe_events' AND post_status = 'publish' HAVING distance < $distance ORDER BY distance";

	$result = $wpdb->get_results( $Query );

	if ( ! empty( $result ) ):

		$i = 0;

		foreach ( $result as $key => $ev ):


			$locationId = get_post_meta( $ev->ID, '_EventVenueID', true );

			$latitude = get_post_meta( $ev->ID, '_latitude', true );

			$longitude = get_post_meta( $ev->ID, '_longitude', true );

			$Address = get_post_meta( $locationId, '_VenueAddress', true );

			$City = get_post_meta( $locationId, '_VenueCity', true );

			$Country = get_post_meta( $locationId, '_VenueCountry', true );

			$Zip = get_post_meta( $locationId, '_VenueZip', true );

			$State = get_post_meta( $locationId, '_VenueState', true );

			$fulladdress = $Address . ' ' . $City . ' ' . $Country . '-' . $Zip;


			$data[ $i ] = new stdClass();

			$data[ $i ]->DisplayText = $ev->post_title;

			$data[ $i ]->ADDRESS = $fulladdress;

			$data[ $i ]->LatitudeLongitude = $latitude . ',' . $longitude;

			$data[ $i ]->MarkerId = 'event';


			$i ++;

		endforeach;


	endif;


	echo json_encode( $data );


	exit;

}


function my_enqueue() {

	wp_enqueue_script( 'ajax-slick', get_template_directory_uri() . '/js/slick.js', array( 'jquery' ) );

	//wp_enqueue_script( 'ajax-sclikcustome', get_template_directory_uri() . '/js/slick-custom.js', array( 'jquery' ) );
    if ( defined( 'US_DEV' ) AND US_DEV ) {
        wp_register_script( 'ajax-sclikcustome', get_template_directory_uri() . '/js/slick-custom.js', array( 'jquery' ) );
    } else {
        wp_register_script( 'ajax-sclikcustome', get_template_directory_uri () . '/js/slick-custom.min.js', array( 'jquery' ) );
    }
    wp_enqueue_script( 'ajax-sclikcustome' );

	wp_enqueue_script ( 'md-public-script', get_template_directory_uri () . '/js/md-public-script.js', array (
		'jquery',
		'tribe-events-pro-geoloc'
	) );

	//wp_enqueue_script( 'ajax-script', get_template_directory_uri() . '/js/my-ajax-script.js', array( 'jquery' ) );

	wp_localize_script( 'ajax-script', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

    /**
     * Added custom style file.
     */
    //wp_enqueue_style('md-custom-style', get_template_directory_uri(). '/css/md-custom-style.css', array(), '0.1.0', 'all');

}

add_action( 'wp_enqueue_scripts', 'my_enqueue' );


add_action( 'wp_ajax_countrylistsearch', 'countrylistsearch' );

add_action( 'wp_ajax_nopriv_countrylistsearch', 'countrylistsearch' );


add_action( 'wp_ajax_getnearbyevent', 'getnearbyevent' );

add_action( 'wp_ajax_nopriv_getnearbyevent', 'getnearbyevent' );


add_action( 'wp_ajax_getnearbylist', 'getnearbylist' );

add_action( 'wp_ajax_nopriv_getnearbylist', 'getnearbylist' );


/*

function countrylistsearch()

{

	$country_data = do_shortcode('[event_search_form]');

	$country_data .= '<div class="eventlist_custom">';

		if(isset($_POST['countrycode'])) {

			$country = $_POST['countrycode'];

			$country_data .= do_shortcode('[events_list country="'.$country.'"]');

		}else{			

			$country_data .= do_shortcode('[events_list]');

		}

		$country_data .= '</div>';

		

		echo $country_data;

		

}

*/


function country_dropdown() {


	$countries = array(

		'' => esc_html__( 'Select a Country' ),

		'AF' => esc_html__( 'Afghanistan' ),

		'AL' => esc_html__( 'Albania' ),

		'DZ' => esc_html__( 'Algeria' ),

		'AS' => esc_html__( 'American Samoa' ),

		'AD' => esc_html__( 'Andorra' ),

		'AO' => esc_html__( 'Angola' ),

		'AI' => esc_html__( 'Anguilla' ),

		'AQ' => esc_html__( 'Antarctica' ),

		'AG' => esc_html__( 'Antigua And Barbuda' ),

		'AR' => esc_html__( 'Argentina' ),

		'AM' => esc_html__( 'Armenia' ),

		'AW' => esc_html__( 'Aruba' ),

		'AU' => esc_html__( 'Australia' ),

		'AT' => esc_html__( 'Austria' ),

		'AZ' => esc_html__( 'Azerbaijan' ),

		'BS' => esc_html__( 'Bahamas' ),

		'BH' => esc_html__( 'Bahrain' ),

		'BD' => esc_html__( 'Bangladesh' ),

		'BB' => esc_html__( 'Barbados' ),

		'BY' => esc_html__( 'Belarus' ),

		'BE' => esc_html__( 'Belgium' ),

		'BZ' => esc_html__( 'Belize' ),

		'BJ' => esc_html__( 'Benin' ),

		'BM' => esc_html__( 'Bermuda' ),

		'BT' => esc_html__( 'Bhutan' ),

		'BO' => esc_html__( 'Bolivia' ),

		'BA' => esc_html__( 'Bosnia And Herzegowina' ),

		'BW' => esc_html__( 'Botswana' ),

		'BV' => esc_html__( 'Bouvet Island' ),

		'BR' => esc_html__( 'Brazil' ),

		'IO' => esc_html__( 'British Indian Ocean Territory' ),

		'BN' => esc_html__( 'Brunei Darussalam' ),

		'BG' => esc_html__( 'Bulgaria' ),

		'BF' => esc_html__( 'Burkina Faso' ),

		'BI' => esc_html__( 'Burundi' ),

		'KH' => esc_html__( 'Cambodia' ),

		'CM' => esc_html__( 'Cameroon' ),

		'CA' => esc_html__( 'Canada' ),

		'CV' => esc_html__( 'Cape Verde' ),

		'KY' => esc_html__( 'Cayman Islands' ),

		'CF' => esc_html__( 'Central African Republic' ),

		'TD' => esc_html__( 'Chad' ),

		'CL' => esc_html__( 'Chile' ),

		'CN' => esc_html__( 'China' ),

		'CX' => esc_html__( 'Christmas Island' ),

		'CC' => esc_html__( 'Cocos (Keeling) Islands' ),

		'CO' => esc_html__( 'Colombia' ),

		'KM' => esc_html__( 'Comoros' ),

		'CG' => esc_html__( 'Congo' ),

		'CD' => esc_html__( 'Congo, The Democratic Republic Of The' ),

		'CK' => esc_html__( 'Cook Islands' ),

		'CR' => esc_html__( 'Costa Rica' ),

		'CI' => esc_html__( "C&ocirc;te d'Ivoire" ),

		'HR' => esc_html__( 'Croatia (Local Name: Hrvatska)' ),

		'CU' => esc_html__( 'Cuba' ),

		'CY' => esc_html__( 'Cyprus' ),

		'CZ' => esc_html__( 'Czech Republic' ),

		'DK' => esc_html__( 'Denmark' ),

		'DJ' => esc_html__( 'Djibouti' ),

		'DM' => esc_html__( 'Dominica' ),

		'DO' => esc_html__( 'Dominican Republic' ),

		'TP' => esc_html__( 'East Timor' ),

		'EC' => esc_html__( 'Ecuador' ),

		'EG' => esc_html__( 'Egypt' ),

		'SV' => esc_html__( 'El Salvador' ),

		'GQ' => esc_html__( 'Equatorial Guinea' ),

		'ER' => esc_html__( 'Eritrea' ),

		'EE' => esc_html__( 'Estonia' ),

		'ET' => esc_html__( 'Ethiopia' ),

		'FK' => esc_html__( 'Falkland Islands (Malvinas)' ),

		'FO' => esc_html__( 'Faroe Islands' ),

		'FJ' => esc_html__( 'Fiji' ),

		'FI' => esc_html__( 'Finland' ),

		'FR' => esc_html__( 'France' ),

		'FX' => esc_html__( 'France, Metropolitan' ),

		'GF' => esc_html__( 'French Guiana' ),

		'PF' => esc_html__( 'French Polynesia' ),

		'TF' => esc_html__( 'French Southern Territories' ),

		'GA' => esc_html__( 'Gabon' ),

		'GM' => esc_html__( 'Gambia' ),

		'GE' => esc_html__( 'Georgia' ),

		'DE' => esc_html__( 'Germany' ),

		'GH' => esc_html__( 'Ghana' ),

		'GI' => esc_html__( 'Gibraltar' ),

		'GR' => esc_html__( 'Greece' ),

		'GL' => esc_html__( 'Greenland' ),

		'GD' => esc_html__( 'Grenada' ),

		'GP' => esc_html__( 'Guadeloupe' ),

		'GU' => esc_html__( 'Guam' ),

		'GT' => esc_html__( 'Guatemala' ),

		'GN' => esc_html__( 'Guinea' ),

		'GW' => esc_html__( 'Guinea-Bissau' ),

		'GY' => esc_html__( 'Guyana' ),

		'HT' => esc_html__( 'Haiti' ),

		'HM' => esc_html__( 'Heard And Mc Donald Islands' ),

		'VA' => esc_html__( 'Holy See (Vatican City State)' ),

		'HN' => esc_html__( 'Honduras' ),

		'HK' => esc_html__( 'Hong Kong' ),

		'HU' => esc_html__( 'Hungary' ),

		'IS' => esc_html__( 'Iceland' ),

		'IN' => esc_html__( 'India' ),

		'ID' => esc_html__( 'Indonesia' ),

		'IR' => esc_html__( 'Iran (Islamic Republic Of)' ),

		'IQ' => esc_html__( 'Iraq' ),

		'IE' => esc_html__( 'Ireland' ),

		'IL' => esc_html__( 'Israel' ),

		'IT' => esc_html__( 'Italy' ),

		'JM' => esc_html__( 'Jamaica' ),

		'JP' => esc_html__( 'Japan' ),

		'JO' => esc_html__( 'Jordan' ),

		'KZ' => esc_html__( 'Kazakhstan' ),

		'KE' => esc_html__( 'Kenya' ),

		'KI' => esc_html__( 'Kiribati' ),

		'KP' => esc_html__( "Korea, Democratic People's Republic Of" ),

		'KR' => esc_html__( 'Korea, Republic Of' ),

		'KW' => esc_html__( 'Kuwait' ),

		'KG' => esc_html__( 'Kyrgyzstan' ),

		'LA' => esc_html__( "Lao People's Democratic Republic" ),

		'LV' => esc_html__( 'Latvia' ),

		'LB' => esc_html__( 'Lebanon' ),

		'LS' => esc_html__( 'Lesotho' ),

		'LR' => esc_html__( 'Liberia' ),

		'LY' => esc_html__( 'Libya' ),

		'LI' => esc_html__( 'Liechtenstein' ),

		'LT' => esc_html__( 'Lithuania' ),

		'LU' => esc_html__( 'Luxembourg' ),

		'MO' => esc_html__( 'Macau' ),

		'MK' => esc_html__( 'Macedonia' ),

		'MG' => esc_html__( 'Madagascar' ),

		'MW' => esc_html__( 'Malawi' ),

		'MY' => esc_html__( 'Malaysia' ),

		'MV' => esc_html__( 'Maldives' ),

		'ML' => esc_html__( 'Mali' ),

		'MT' => esc_html__( 'Malta' ),

		'MH' => esc_html__( 'Marshall Islands' ),

		'MQ' => esc_html__( 'Martinique' ),

		'MR' => esc_html__( 'Mauritania' ),

		'MU' => esc_html__( 'Mauritius' ),

		'YT' => esc_html__( 'Mayotte' ),

		'MX' => esc_html__( 'Mexico' ),

		'FM' => esc_html__( 'Micronesia, Federated States Of' ),

		'MD' => esc_html__( 'Moldova, Republic Of' ),

		'MC' => esc_html__( 'Monaco' ),

		'MN' => esc_html__( 'Mongolia' ),

		'ME' => esc_html__( 'Montenegro' ),

		'MS' => esc_html__( 'Montserrat' ),

		'MA' => esc_html__( 'Morocco' ),

		'MZ' => esc_html__( 'Mozambique' ),

		'MM' => esc_html__( 'Myanmar' ),

		'NA' => esc_html__( 'Namibia' ),

		'NR' => esc_html__( 'Nauru' ),

		'NP' => esc_html__( 'Nepal' ),

		'NL' => esc_html__( 'Netherlands' ),

		'AN' => esc_html__( 'Netherlands Antilles' ),

		'NC' => esc_html__( 'New Caledonia' ),

		'NZ' => esc_html__( 'New Zealand' ),

		'NI' => esc_html__( 'Nicaragua' ),

		'NE' => esc_html__( 'Niger' ),

		'NG' => esc_html__( 'Nigeria' ),

		'NU' => esc_html__( 'Niue' ),

		'NF' => esc_html__( 'Norfolk Island' ),

		'MP' => esc_html__( 'Northern Mariana Islands' ),

		'NO' => esc_html__( 'Norway' ),

		'OM' => esc_html__( 'Oman' ),

		'PK' => esc_html__( 'Pakistan' ),

		'PW' => esc_html__( 'Palau' ),

		'PA' => esc_html__( 'Panama' ),

		'PG' => esc_html__( 'Papua New Guinea' ),

		'PY' => esc_html__( 'Paraguay' ),

		'PE' => esc_html__( 'Peru' ),

		'PH' => esc_html__( 'Philippines' ),

		'PN' => esc_html__( 'Pitcairn' ),

		'PL' => esc_html__( 'Poland' ),

		'PT' => esc_html__( 'Portugal' ),

		'PR' => esc_html__( 'Puerto Rico' ),

		'QA' => esc_html__( 'Qatar' ),

		'RE' => esc_html__( 'Reunion' ),

		'RO' => esc_html__( 'Romania' ),

		'RU' => esc_html__( 'Russian Federation' ),

		'RW' => esc_html__( 'Rwanda' ),

		'KN' => esc_html__( 'Saint Kitts And Nevis' ),

		'LC' => esc_html__( 'Saint Lucia' ),

		'VC' => esc_html__( 'Saint Vincent And The Grenadines' ),

		'WS' => esc_html__( 'Samoa' ),

		'SM' => esc_html__( 'San Marino' ),

		'ST' => esc_html__( 'Sao Tome And Principe' ),

		'SA' => esc_html__( 'Saudi Arabia' ),

		'SN' => esc_html__( 'Senegal' ),

		'RS' => esc_html__( 'Serbia' ),

		'SC' => esc_html__( 'Seychelles' ),

		'SL' => esc_html__( 'Sierra Leone' ),

		'SG' => esc_html__( 'Singapore' ),

		'SK' => esc_html__( 'Slovakia (Slovak Republic)' ),

		'SI' => esc_html__( 'Slovenia' ),

		'SB' => esc_html__( 'Solomon Islands' ),

		'SO' => esc_html__( 'Somalia' ),

		'ZA' => esc_html__( 'South Africa' ),

		'GS' => esc_html__( 'South Georgia, South Sandwich Islands' ),

		'ES' => esc_html__( 'Spain' ),

		'LK' => esc_html__( 'Sri Lanka' ),

		'SH' => esc_html__( 'St. Helena' ),

		'PM' => esc_html__( 'St. Pierre And Miquelon' ),

		'SD' => esc_html__( 'Sudan' ),

		'SR' => esc_html__( 'Suriname' ),

		'SJ' => esc_html__( 'Svalbard And Jan Mayen Islands' ),

		'SZ' => esc_html__( 'Swaziland' ),

		'SE' => esc_html__( 'Sweden' ),

		'CH' => esc_html__( 'Switzerland' ),

		'SY' => esc_html__( 'Syrian Arab Republic' ),

		'TW' => esc_html__( 'Taiwan' ),

		'TJ' => esc_html__( 'Tajikistan' ),

		'TZ' => esc_html__( 'Tanzania, United Republic Of' ),

		'TH' => esc_html__( 'Thailand' ),

		'TG' => esc_html__( 'Togo' ),

		'TK' => esc_html__( 'Tokelau' ),

		'TO' => esc_html__( 'Tonga' ),

		'TT' => esc_html__( 'Trinidad And Tobago' ),

		'TN' => esc_html__( 'Tunisia' ),

		'TR' => esc_html__( 'Turkey' ),

		'TM' => esc_html__( 'Turkmenistan' ),

		'TC' => esc_html__( 'Turks And Caicos Islands' ),

		'TV' => esc_html__( 'Tuvalu' ),

		'UG' => esc_html__( 'Uganda' ),

		'UA' => esc_html__( 'Ukraine' ),

		'AE' => esc_html__( 'United Arab Emirates' ),

		'GB' => esc_html__( 'United Kingdom' ),

		'US' => esc_html__( 'United States' ),

		'UM' => esc_html__( 'United States Minor Outlying Islands' ),

		'UY' => esc_html__( 'Uruguay' ),

		'UZ' => esc_html__( 'Uzbekistan' ),

		'VU' => esc_html__( 'Vanuatu' ),

		'VE' => esc_html__( 'Venezuela' ),

		'VN' => esc_html__( 'Viet Nam' ),

		'VG' => esc_html__( 'Virgin Islands (British)' ),

		'VI' => esc_html__( 'Virgin Islands (U.S.)' ),

		'WF' => esc_html__( 'Wallis And Futuna Islands' ),

		'EH' => esc_html__( 'Western Sahara' ),

		'YE' => esc_html__( 'Yemen' ),

		'ZM' => esc_html__( 'Zambia' ),

		'ZW' => esc_html__( 'Zimbabwe' ),

	);

	$country = '<select name="country_name" class="em-search-country">';

	foreach ( $countries as $cnt => $vl ) {

		$country .= '<option value="' . $cnt . '">' . $vl . '</option>';

	}

	$country .= '</select>';

	return $country;

}

function countrylistsearch() {


	$country_data = country_dropdown();

	$country_data .= '<div class="eventlist_custom">';

	if ( isset( $_GET['country'] ) ) {

		$country = $_GET['country'];

		$country_data .= event_calender_customform( $country );

	} else {

		$country_data .= event_calender_venuelist();

	}

	$country_data .= '</div>';


	echo $country_data;

}

add_shortcode( 'countrylist_search', 'countrylistsearch' );


function event_calender_customform( $country = '' ) {

	$whereq = array();

	if ( isset( $country ) && ! empty( $country ) ) {

		$whereq = array(

			'key' => '_VenueCountry',

			'value' => array( $country ),

			'compare' => 'IN',

		);

	}

	$args = array(

		'nopaging' => true,

		'post_type' => 'tribe_venue',

		//Only query venues in specific locations

		'meta_query' => array(

			'relation' => 'AND',

			//Specific Country

			$whereq

		)

	);

	$country_venue = get_posts( $args );


	$venue_ids = array();

	//Loops through venues and add ID to array

	foreach ( $country_venue as $post ) {

		$locationId = $post->ID;

		$Address = get_post_meta( $locationId, '_VenueAddress', true );

		$City = get_post_meta( $locationId, '_VenueCity', true );

		$Country = get_post_meta( $locationId, '_VenueCountry', true );

		$Zip = get_post_meta( $locationId, '_VenueZip', true );

		$State = get_post_meta( $locationId, '_VenueState', true );

		$fulladdress = $Address . ' ' . $City . ' ' . $Country . '-' . $Zip;


		$eventllist .= '<div class="css-events-list" id="css-events-list" ><ul id="demoFour">

			<a href="' . site_url() . '/venue/?id=' . $locationId . '">

			<li class="">

			<h2 class="our-film">' . get_the_title( $locationId ) . '</h2> 

			<h2 class="film-subtitle"><p>' . $fulladdress . '</p></h2>

			</li></a>



			</ul></div>';

	}


	wp_reset_postdata();


	return $eventllist;

}


add_shortcode( 'event_calender_customform_search', 'event_calender_customform' );

/******all venue list start**********/

function event_calender_venuelist() {


	$eventllist = '';

	$args = array(

		'post_type' => 'tribe_venue',

		'posts_per_page' => 10,

		'paged' => ( get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1 ),

		//Only query venues in specific locations


	);


	//$country_venue = get_posts( $args );

	$postslist = new WP_Query( $args );


	//Loops through venues and add ID to array

	if ( $postslist->have_posts() ) :

		while ( $postslist->have_posts() ) : $postslist->the_post();


			$locationId = get_the_ID();

			$Address = get_post_meta( $locationId, '_VenueAddress', true );

			$City = get_post_meta( $locationId, '_VenueCity', true );

			$Country = get_post_meta( $locationId, '_VenueCountry', true );

			$Zip = get_post_meta( $locationId, '_VenueZip', true );

			$State = get_post_meta( $locationId, '_VenueState', true );

			$fulladdress = $Address . ' ' . $City . ' ' . $Country . '-' . $Zip;


			$eventllist .= '<div class="css-events-list" id="css-events-list" ><ul id="demoFour">

        <a href="' . site_url() . '/venue/?id=' . $locationId . '">

        <li class="">

        			<h2 class="our-film">' . get_the_title() . '</h2> 

        			<h2 class="film-subtitle"><p>' . $fulladdress . '</p></h2>

        </li></a>

        

        </ul></div>';

		endwhile;


		$eventllist .= '<div class="pagination-list">' . get_previous_posts_link( 'Previous' );

		$eventllist .= get_next_posts_link( 'Next', $postslist->max_num_pages ) . '</div>';

		$eventllist .= wp_reset_postdata();

	endif;

	return $eventllist;

}

/*********all event list start for sidebar*********/

function event_sidebarlist() {

	$taxonomy = 'tribe_events_cat';

	$terms = get_terms( $taxonomy ); // Get all terms of a taxonomy


	$eventlsidelist .= '<select name="eventlist" class="event-get"><option value="">Filter By Film Title</option>';

	foreach ( $terms as $post ) {


		$eventlsidelist .= '<option value="' . $post->slug . '">' . $post->name . '</option>';

	}

	$eventlsidelist .= '</select>';

	return $eventlsidelist;

}


/* function event_sidebarlist()

{	

		$eventlsidelist='';

		$event_args = array(

		    'nopaging' => true,

			'post_type'=>'tribe_events',

			'posts_per_page' => -1,

			'start_date' => date( 'Y-m-d H:i:s' )

		);

		$country_events = tribe_get_events( $event_args );

		$eventlsidelist .='<select name="eventlist" class="event-get"><option value="">Filter By Film Title</option>';

		foreach( $country_events as $post ) {

		    	$locationId=get_post_meta($post->ID,'_EventVenueID',true);

				if(!empty($locationId)){

				$Address=get_post_meta($locationId,'_VenueAddress',true);

				$City=get_post_meta($locationId,'_VenueCity',true);

				$Country=get_post_meta($locationId,'_VenueCountry',true);

				$Zip=get_post_meta($locationId,'_VenueZip',true);

				$State=get_post_meta($locationId,'_VenueState',true);

				$fulladdress = $Address.' '.$City.' '.$Country;

		

			$eventlsidelist .='<option value="'.$fulladdress.'">' .  get_the_title($post->ID) . '</option>';

		}

		}

		$eventlsidelist .='</select>';	

		return $eventlsidelist;		

	} */


function customposttypetest() {

	$args = array( 'post_type' => 'event', 'posts_per_page' => 10 );

	$loop = new WP_Query( $args );

	while ( $loop->have_posts() ) : $loop->the_post();

		the_title();

		//echo get_the_ID();

		$key_1_value = get_post_meta( get_the_ID() );

		//echo '<pre>'; print_r($key_1_value); echo '</pre>';


	endwhile;

}


add_shortcode( 'posttypetest', 'customposttypetest' );


function add_events_metaboxes() {

	add_meta_box( '_latitude', 'latitude', '_latitude', 'tribe_venue', 'normal', 'default' );

	add_meta_box( '_longitude', 'longitude', '_longitude', 'tribe_venue', 'normal', 'default' );

}

add_action( 'add_meta_boxes', 'add_events_metaboxes' );


// function to get  the address


function getLatLong( $address ) {

	if ( ! empty( $address ) ) {

		//Formatted address

		$address = str_replace( ',', ' ', $address );

		$formattedAddr = str_replace( ' ', '+', $address );

		$formattedAddr = str_replace( '++', '+', $formattedAddr );

		$formattedAddr = str_replace( 'á', 'a', $formattedAddr );


		$url = "https://maps.google.com/maps/api/geocode/json?address=$formattedAddr&sensor=true&key=AIzaSyAIac7z0DwlKNPw1modF-KcocQAg3Q_yPw";

		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, $url );

		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

		curl_setopt( $ch, CURLOPT_PROXYPORT, 3128 );

		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );

		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );

		$response = curl_exec( $ch );


		curl_close( $ch );

		$response_a = json_decode( $response );


		$data['latitude'] = $response_a->results[0]->geometry->location->lat;

		$data['longitude'] = $response_a->results[0]->geometry->location->lng;

		if ( ! empty( $data ) ) {

			return $data;

		} else {

			return false;

		}

	} else {

		return false;

	}

}


function getLatLong111( $address ) {

	if ( ! empty( $address ) ) {

		//Formatted address

		$address = str_replace( ',', ' ', $address );

		$formattedAddr = str_replace( ' ', '+', $address );

		$formattedAddr = str_replace( '++', '+', $address );


		//Send request and receive json data by address

		$geocodeFromAddr = file_get_contents( 'http://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddr . '&sensor=false&key=AIzaSyBW6_v-U9om9MMozTyYYQjDngmbeZHLWcY' );

		$output = json_decode( $geocodeFromAddr );


		//Get latitude and longitute from json data

		$data['latitude'] = $output->results[0]->geometry->location->lat;

		$data['longitude'] = $output->results[0]->geometry->location->lng;

		//Return latitude and longitude of the given address

		if ( ! empty( $data ) ) {

			echo '<pre>';
			print_r( $data );
			echo '</pre>';

			return $data;

		} else {

			return false;

		}

	} else {

		return false;

	}

}

/*add data to custom table end*/


/*
 * Filter to remove unwanted filters from event's filterbar
 */
add_filter( 'tribe-events-bar-filters', 'md_remove_extra_filters' );
function md_remove_extra_filters( $filters ) {

	if ( ! empty( $filters['tribe-bar-date'] ) ) {
		unset( $filters['tribe-bar-date'] );
	}

	if ( ! empty( $filters['tribe-bar-search'] ) ) {
		unset( $filters['tribe-bar-search'] );
	}

	return $filters;
}

/*
 * Action to add extra link after the searched category
 */
add_action( 'tribe_events_after_the_meta', 'md_add_extra_link_to_events' );
function md_add_extra_link_to_events() {
	global $post;
	$event_website_url = get_post_meta( $post->ID, '_EventURL', true );
	if ( ! empty( $event_website_url )  && isset( $event_website_url )) {
		echo '<a class="md-site-link" href="' . $event_website_url . '" target="_blank">CLICK HERE to find &amp; buy tickets at this cinema&apos;s website</a>';
	} else {
		$venue_id = tribe_get_venue_id( $post->ID );
		$url      = esc_url_raw( tribe_get_venue_website_url( $venue_id ) );
		if ( ! empty( $url )  && isset( $url )) {
			echo '<a class="md-site-link" href="' . $url . '" target="_blank">CLICK HERE to find &amp; buy tickets at this cinema&apos;s website</a>';
		}
	}
}

/*
 * Filter to override button text for event filter
 */
add_filter( 'tribe_event_label_plural', 'md_find_button_text_replace' );
function md_find_button_text_replace( $text ) {
	if ( ! is_admin() ) {
		$text = esc_html__( 'for Screenings', 'the-events-calendar' );
	}

	return $text;
}


/*
 * Filter to change the searched title
 */
add_filter( 'tribe_get_events_title', 'md_change_screen_title', 10, 2 );
function md_change_screen_title( $title, $depth ) {
	$title = sprintf( esc_html__( '%s', 'the-events-calendar' ), 'Screenings' );

	return $title;
}

/*
 * Filter to change event filter's placeholder
 *
 */
add_filter( 'gettext', 'md_change_tribe_event_labels', 20, 3 );
function md_change_tribe_event_labels( $translated_text, $text, $domain ) {
	// Check the domain and only perform it for this case.
	// If another plugin was to translate the same word we
	// would not want to change it then
	if ( $domain == 'tribe-events-calendar' || $domain == 'tribe-events-calendar-pro' ) {
		switch ( $text ) {
			case 'Location' :
				$translated_text = 'eg. BN2 5JD';
				break;
		}
	}

	return $translated_text;
}

function request_is_frontend_ajax()
{
	$script_filename = isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '';

	//Try to figure out if frontend AJAX request... If we are DOING_AJAX; let's look closer
	if((defined('DOING_AJAX') && DOING_AJAX))
	{
		//From wp-includes/functions.php, wp_get_referer() function.
		//Required to fix: https://core.trac.wordpress.org/ticket/25294
		$ref = '';
		if ( ! empty( $_REQUEST['_wp_http_referer'] ) )
			$ref = wp_unslash( $_REQUEST['_wp_http_referer'] );
		elseif ( ! empty( $_SERVER['HTTP_REFERER'] ) )
			$ref = wp_unslash( $_SERVER['HTTP_REFERER'] );

		//If referer does not contain admin URL and we are using the admin-ajax.php endpoint, this is likely a frontend AJAX request
		if(((strpos($ref, admin_url()) === false) && (basename($script_filename) === 'admin-ajax.php')))
			return true;
	}

	//If no checks triggered, we end up here - not an AJAX request.
	return false;
}

add_filter( 'tribe_get_venue_link', 'change_venue_link', 10, 4 );

function change_venue_link( $link, $venue_id, $full_link, $url ) {
	global $post;
	if ( ! is_admin()|| request_is_frontend_ajax() ) {
		$name              = tribe_get_venue( $venue_id );
		$attr_title        = the_title_attribute( array( 'post' => $venue_id, 'echo' => false ) );
		$event_website_url = get_post_meta( $post->ID, '_EventURL', true );
		if ( ! empty( $event_website_url ) ) {
			return '<a id="'.$post->ID.'" class="md-site-link" href="' . $event_website_url . '" target="_blank" title="' . $attr_title . '">' . $name . '</a>';
		} else {
			$url      = esc_url_raw( tribe_get_venue_website_url( $venue_id ) );
			return '<a id="'.$post->ID.'" class="md-site-link" href="' . $url . '" target="_blank" title="' . $attr_title . '">' . $name . '</a>';
		}
	}
	return $link;
}

add_filter('tribe-events-bar-filters', 'ecp_theme_filter_text', 10, 3);
function ecp_theme_filter_text( $tribebar ) {

	$tribebar['tribe-bar-geoloc']['caption'] = "ENTER POSTCODE/ZIP or CITY";

	return $tribebar;
}

function my_cdn_upload_url() {
	return 'https://cdn.exhibitiononscreen.com/wp-content/uploads';
}
//add_filter( 'pre_option_upload_url_path', 'my_cdn_upload_url' );

//add_filter('upload_dir', 'cdn_upload_url');
function cdn_upload_url($args)
{
	if (!is_admin()) {
		$args['baseurl'] = 'https://cdn.exhibitiononscreen.com/wp-content/uploads';
	}
	return $args;
}
//add_filter( 'tribe_events_the_notices', 'customize_notice', 10, 2 );

function customize_notice( $html, $notices ) {

	if (strpos($html, 'No results were found for events in or near <strong>"') !== false) {
		echo 'true';
	}
	//wp_mail('chetan.satasiya@multidots.in','NOtice', print_r($notices,true));
	//wp_mail('chetan.satasiya@multidots.in','$html', $html);
	// If text is found in notice, then replace it
	if( stristr( $html, 'There were no results found.' ) ) {
		// Customize the message as needed
		$html = str_replace( 'There were no results found.', 'Your custom notice goes here.', $html );
	}

	return $html;

}

add_filter ( 'tribe_the_notices', 'ecp_theme_filter_notices12', 10, 2 );
/**
 * Replace a text from the not event result found.
 *
 * @param $html
 * @param $notices
 *
 * @return string
 */
function ecp_theme_filter_notices12 ( $html, $notices ) {

	if ( ! empty( $notices ) ) {
		if ( isset( $notices['event-search-no-results'] ) ) {
			return '<div class="tribe-events-notices"><ul><li>There are no current screenings showing for your search, please try again using your post code/Zip code or City/Town name. If you have missed a recent screening then DVDs and downloads are in available in our shop <a href="http://www.seventh-art.com/shop/" target="_blank">http://www.seventh-art.com/shop/</a></li></ul></div>';
		} else {
			return $html;
		}
	} else {
		return '';
	}

}

/*
Ensures all events appear on map view.
*/

function show_all_markers ( $data ) {
	if ( ! isset( $data['markers'] ) ) {
		return $data;
	}

	$cached_markers = get_transient ( 'all_geo_markers_store' );
	if ( $cached_markers ) {
		$data['markers'] = $cached_markers;

		return $data;
	}

	$geo_loc = Tribe__Events__Pro__Geo_Loc ::instance ();
	$events  = tribe_get_events ( array (
		'posts_per_page' => - 1,
		'eventDisplay'   => 'map'
	) );

	$markers = $geo_loc -> generate_markers ( $events );
	set_transient ( 'all_geo_markers_store', $markers, DAY_IN_SECONDS );

	$data['markers'] = $markers;

	return $data;
}

add_filter ( 'tribe_events_ajax_response', 'show_all_markers' );

add_filter ( 'tribe_get_address', 'md_change_tooltip_for_map_view', 10, 2 );
function md_change_tooltip_for_map_view ( $output, $venue_id ) {
	global $post;

	if ( tribe_is_map () ) {
		$name              = tribe_get_venue ( $venue_id );
		$attr_title        = the_title_attribute ( array ( 'post' => $venue_id, 'echo' => false ) );
		$event_website_url = get_post_meta ( $post -> ID, '_EventURL', true );
		if ( ! empty( $event_website_url ) ) {
			$output = '<a class="md-none" id="' . $post -> ID . '" href="' . $event_website_url . '" target="_blank" title="' . $attr_title . '">' . $name . ',</a> ' . $output;
		} else {
			$url = esc_url_raw ( tribe_get_venue_website_url ( $venue_id ) );

			$output = '<a class="md-none" id="' . $post -> ID . '" href="' . $url . '" target="_blank" title="' . $attr_title . '">' . $name . ',</a> ' . $output;
		}
	}

	return $output;
}

/**
 * Tribe event post status draft to publish mode delete transient.
 *
 * @param $new_status
 * @param $old_status
 * @param $post
 */
function md_change_post_status( $new_status, $old_status, $post ) {
    if( $post->post_type === 'tribe_events' ) {
        if ( $old_status == 'draft' && $new_status == 'publish' ) {
            delete_transient( 'all_geo_markers_store' );
        }
    }
}
add_action( 'transition_post_status', 'md_change_post_status', 10, 3 );

// Defer Javascripts
// Defer jQuery Parsing using the HTML5 defer property
if (!(is_admin() )) {
    function defer_parsing_of_js ( $url ) {
        if ( FALSE === strpos( $url, '.js' ) ) return $url;
        if ( strpos( $url, 'jquery.js' ) ) return $url;
        if ( strpos( $url, 'us.core.min.js' ) ) {
            return $url;
        }
        // return "$url' defer ";
        return "$url' defer onload='";
    }
    add_filter( 'clean_url', 'defer_parsing_of_js', 11, 1 );
}