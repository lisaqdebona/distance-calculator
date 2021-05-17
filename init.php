<?php
/**
* Plugin Name: Distance Calculator
* Plugin URI: 
* Description: This is a simple distance calculator.
* Version: 1.0
* Author: Lisa DeBona
* Author URI: http://lisadebona.com/
**/
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

define( 'LISAQ_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'LISAQ_PLUGIN_URL', str_replace( array("\r", "\n") , '', untrailingslashit( plugin_dir_url( __FILE__ ) ) ) );

function googlemapAPI() {
  return 'AIzaSyDPkkDNCBIgNVNZyTCqZS72qjyDfYfFPQI';
}

function getToLocationDefault() {
   $address = 'The White House, 1600 Pennsylvania Avenue NW, Washington, DC 20500';
   $lat = 38.894600;
   $long = -77.035500;
   $default['address'] = $address;
   $default['latitude'] = $lat;
   $default['longitude'] = $long;
  return $default;
}


add_shortcode( 'distance_calculator', 'dc_distance_calculator_func' );
function dc_distance_calculator_func( $atts ) {
    $d = getToLocationDefault();
    $atts = shortcode_atts( array(
        'address'=> $d['address'],
        'latitude' => $d['latitude'],
        'longitude' => $d['longitude']
    ), $atts, 'bartag' );

    $address = $atts['address'];
    $lat = $atts['latitude'];
    $long = $atts['longitude'];
    $form = distance_calc_form($lat,$long,$address);
    return $form;
}



function distance_calc_form($lat2,$long2,$destinationAddress) {
	$content = '';
	ob_start();
	include( LISAQ_PLUGIN_DIR . '/template.php' );
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

function myscript() { 
$googleAPI = googlemapAPI();
echo '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key='.$googleAPI.'&libraries=places"></script>';
}
add_action( 'wp_footer', 'myscript' );

function wpse_load_myplugin_css() {
  $googleAPI = googlemapAPI();
  $plugin_url = LISAQ_PLUGIN_URL;
  if ( !wp_script_is( 'jquery', 'enqueued' )) {
    wp_enqueue_script( 'jquery' );
  }
  wp_enqueue_style( 'myplugincss', LISAQ_PLUGIN_URL . '/css/myplugin.css' );
  wp_enqueue_script( 'mypluginjs', LISAQ_PLUGIN_URL . '/js/scripts.js', array(), false, true );
  wp_localize_script( 
    'mypluginjs', 
    'mypluginAjax', 
    array('jsonUrl' => rest_url('myplugin/distance'),'mapAPI'=>googlemapAPI() )
  );
}
add_action( 'wp_enqueue_scripts', 'wpse_load_myplugin_css' );


add_action( 'rest_api_init', 'my_register_route' );
function my_register_route() {
    register_rest_route( 'myplugin', 'distance', array(
        'methods' => 'GET',
        'callback' => 'rest_callback_func'
      )
    );
}

function rest_callback_func() {
  if( (isset($_REQUEST['userLat']) && $_REQUEST['userLat']) && isset($_REQUEST['userLong']) && $_REQUEST['userLong'] ) {
    $lat1 = $_REQUEST['userLat'];
    $lon1 = $_REQUEST['userLong'];
    $add2 = $_REQUEST['destination2'];

    $lat2 = $_REQUEST['lat2'];
    $lon2 = $_REQUEST['long2'];

    $result = getDistance($lat1, $lon1, $lat2, $lon2, "M");
    $distance = ($result) ? $result . ' miles':'';
    $message = ($result) ? '<div class="response">Your distance from <strong class="t1">'.$add2.'</strong> is:<BR> <strong class="t2">'.$distance.'</strong></div>' : '';  
  }  else {
    $message = '<div class="response error">Geolocation is not supported by this browser.</div>';
  }
  return rest_ensure_response($message);
}


function getDistance($lat1, $lon1, $lat2, $lon2, $unit) {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $miles = round($miles,2);
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}


