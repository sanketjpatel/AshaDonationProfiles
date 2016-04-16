<?php
function get_donations($channel){
    $url  = 'https://' . $GLOBALS['donation_api_endpoint']['IP_PORT'] . '/donations/counts/channel/' . $channel;

    $args = array( 'headers' => array( 'Authorization' => "Basic " . base64_encode($GLOBALS['donation_api_endpoint']['AUTHORIZATION_STRING']),
			             'ACCESSTOKEN'   => $GLOBALS['donation_api_endpoint']['ACCESSTOKEN']),
	         'sslverify' => false, // TODO Remove in production
		 'timeout' => 2,
		 );


    $result = json_decode( wp_remote_retrieve_body( wp_remote_get( $url, $args ) ), true ); 
    return array('total' => $result['entity']['total'], 'count' => $result['entity']['count']);

}
?>