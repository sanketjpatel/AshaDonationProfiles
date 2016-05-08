<?php

/*
  Plugin Name: Asha Donations Profiles plugin
  Plugin URI:
  Description: Donation profiles are custom post types that accpet event, project, channel, chapeter and display donations received for a particular channel by querying Asha treasury db through a REST API.
  Author: Tathagata Dasgupta
  Version: 1.0
  Author URI: https://twitter.com/tathagata
*/

add_action( 'init', 'donation_profile_post_type' );

function donation_profile_post_type() {
  register_post_type( 'donation_profiles',
		      array(
			    'labels' => array(
					      'name' => 'Donation Profiles',
					      'singular_name' => 'Donation Profile',
					      'add_new' => 'Add New',
					      'add_new_item' => 'Add New Donation Profile',
					      'edit' => 'Edit',
					      'edit_item' => 'Edit Donation Profile',
					      'new_item' => 'New Donation Profile',
					      'view' => 'View',
					      'view_item' => 'View Donation Profile',
					      'search_items' => 'Search Donation Profiles',
					      'not_found' => 'No Donation Profile found',
					      'not_found_in_trash' => 'No Donation Profile found in Trash',
					      'parent' => 'Parent Donation Profile'
					      ),
			    'public' => true,
			    'menu_position' => 20,
			    'supports' => array( 'title', 'editor', 'comments', 'thumbnail' ),
			    'taxonomies' => array( '' ),
			    'menu_icon' => 'dashicons-id',
			    'has_archive' => true
			    )
		      );

}





add_action('init', 'get_donation_api_endpoint_with_credentials');
function get_donation_api_endpoint_with_credentials(){
  $parsed_data = parse_ini_file(".keys.ini");

  global $donation_api_endpoint;
  $donation_api_endpoint =  array(
				  'AUTHORIZATION_STRING'  => $parsed_data["AUTHORIZATION_STRING"],
				  'ACCESSTOKEN'           => $parsed_data["ACCESSTOKEN"],
				  'IP_PORT'               => $parsed_data["IP_PORT"],
				  );
}


add_action( 'admin_init', 'donation_profile_admin_init' );
function donation_profile_admin_init() {
  add_meta_box( 'donation_profile_details_meta_box', 'Donation Profile Details', 'display_donation_profile_details_meta_box', 'donation_profiles', 'normal', 'high' );
}


function display_donation_profile_details_meta_box( $donation_profile ) {
  $channel = esc_html( get_post_meta( $donation_profile->ID, 'channel', true ) );
  $chapter = esc_html( get_post_meta( $donation_profile->ID, 'chapter', true ) );
  $event   = esc_html( get_post_meta( $donation_profile->ID, 'event',   true ) );
  $project = esc_html( get_post_meta( $donation_profile->ID, 'project', true ) );
  $target  = esc_html( get_post_meta( $donation_profile->ID, 'target', true ) );
  ?>
  <table>
     <tr>
     <td style="width: 100%">Channel<sup>*</sup></td>
     <td><input type='text' size='80' name='channel' value='<?php echo $channel; ?>' /></td>
     </tr>
     <tr>
     <td style="width: 100%">Chapter<sup>*</sup></td>
     <td><input type='text' size='80' name='chapter' value='<?php echo $chapter; ?>' /></td>
     </tr>
     <tr>
     <td style="width: 100%">Event<sup>*</sup></td>
     <td><input type='text' size='80' name='event'   value='<?php echo $event;   ?>' /></td>
     </tr>
     <tr>
     <td style="width: 100%">Project<sup>*</sup></td>
     <td><input type='text' size='80' name='project' value='<?php echo $project; ?>' /></td>
     </tr>
     <tr>
     <td style="width: 100%">Target<sup>*</sup></td>
     <td><input type='text' size='80' name='target' value='<?php echo $target; ?>' /></td>
     </tr>
  </table>

 <?php }

add_action( 'save_post', 'add_donation_profile_fields', 10, 2 );
function add_donation_profile_fields( $post_id = false, $post = false ) {
  if ( $post->post_type == 'donation_profiles' ) {
    if ( isset( $_POST['channel'] ) && $_POST['channel'] != '' ) {
      update_post_meta( $post_id, 'channel', $_POST['channel'] );
    }
    if ( isset( $_POST['chapter'] ) && $_POST['chapter'] != '' ) {
      update_post_meta( $post_id, 'chapter', $_POST['chapter'] );
    }
    if ( isset( $_POST['event'] ) && $_POST['event'] != '' ) {
      update_post_meta( $post_id, 'event', $_POST['event'] );
    }
    if ( isset( $_POST['project'] ) && $_POST['project'] != '' ) {
      update_post_meta( $post_id, 'chapter', $_POST['project'] );
    }
    if ( isset( $_POST['target'] ) && $_POST['target'] != '') {
      update_post_meta( $post_id, 'target', $_POST['target'] );
    }
  }
}

add_filter( 'template_include', 'donation_profile_template_include', 1 );
function donation_profile_template_include( $template_path ){
  if ( get_post_type() == 'donation_profiles' ) {
    if ( is_single() ) {
      if ( $theme_file = locate_template( array( 'single-donation_profiles.php' ) ) ) {
	$template_path = $theme_file;
      } else {
	$template_path = plugin_dir_path( __FILE__ ) . '/single-donation_profiles.php';
      }
    } elseif ( is_archive() ) {
        if ( $theme_file = locate_template( array( 'archive-donation_profiles.php' ) ) ) {
	         $template_path = $theme_file;
         } elseif ( is_home() ) {
            if ( $theme_file = locate_template( array( 'archive-donation_profiles.php' ) ) ) {
              //$template_path = $theme_file;
              $template_path = plugin_dir_path( __FILE__ ) . '/archive-donation_profiles.php';
            }
         } else {
	             $template_path = plugin_dir_path( __FILE__ ) . '/archive-donation_profiles.php';
             }
         }
  }

  return $template_path;
}

add_action('wp_enqueue_scripts', 'donation_profiles_add_style');
function donation_profiles_add_style(){

  wp_register_style('google-fonts', 'https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en');
  wp_enqueue_style('google-fonts');

  wp_register_style('material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons');
  wp_enqueue_style('google-fonts');

  wp_register_style('material', 'https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.2/material.min.css');
  wp_enqueue_style('material');

  wp_register_style('donation_profile_style', plugins_url( 'donation_profile_style.css', __FILE__ ) );
  wp_enqueue_style('donation_profile_style');

}

include 'donations_api.php';
add_shortcode( 'donation-profiles-list', 'generate_donation_profiles_list' );
function generate_donation_profiles_list() {
  $query_params = array( 'post_type' => 'donation_profiles',
    'post_status' => 'publish',
    'posts_per_page' => 5 );

  $page_num = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;


  if ( $page_num != 1 )
    $query_params['paged'] = $page_num;

  $donation_profile_query = new WP_Query;
  $donation_profile_query->query( $query_params );

  if ( $donation_profile_query->have_posts() ) {

    $output = '<table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp">';
    $output .= '<tr><th class="mdl-data-table__cell--non-numeric" style="width: 350px"><strong>Runner</strong></th>';
    $output .= '<th><strong>Funds Raised</strong></th>';
    $output .= '<th><strong>Number of Kids Funded</strong></th></tr>';


    while ( $donation_profile_query->have_posts() ) {
      $donation_profile_query->the_post();

      $channel = get_post_meta( get_the_ID(), 'channel', true );

      $image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' )[0];
      
      $response = get_donations($channel);
      $total = $response['total'];
      $kids_helped_count = floor( intval( $response['total'] ) / 250 ) ;
      
      $output .= '<tr><td><img src="' . $image_url . '"/></td>';
      $output .= '<tr><td class="mdl-data-table__cell--non-numeric"><a href="' . post_permalink() . '">';
      $output .= get_the_title( get_the_ID() ) . '</a></td>';
      
      $output .= '<td class="mdl-data-table__cell--numeric">';
      $output .= esc_html($total );
      $output .= '</td>';
      $output .= '<td class="mdl-data-table__cell--numeric">';
      $output .= esc_html($kids_helped_count);
      $output .= '</td>';
      
      $output .= '</tr>';
    }

   $output .= '</table>';


    if ( $donation_profile_query->max_num_pages > 1 ) {
      $output .= '<nav id="nav-below">';
      $output .= '<div class="nav-previous">';
      $output .= get_next_posts_link( '<span class="meta-nav">&larr;</span> Older profiles', $donation_profile_query->max_num_pages );
      $output .= '</div>';
      $output .= '<div class="nav-next">';
      $output .= get_previous_posts_link( 'Newer profiles <span class="meta-nav">&rarr;</span>', $donation_profile_query->max_num_pages );
      $output .= '</div>';
      $output .= '</nav>';

    }
    wp_reset_query();
  }

  return $output;
}

?>
