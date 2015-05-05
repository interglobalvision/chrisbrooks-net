<?php
function scripts_and_styles_method() {

  $templateuri = get_template_directory_uri() . '/js/';

  // library.js is to bundle plugins. my.js is your scripts. enqueue more files as needed
  $jslib = $templateuri."library.js";
  wp_enqueue_script( 'jslib', $jslib,'','',true);
  $myscripts = $templateuri."main.js";
  wp_enqueue_script( 'myscripts', $myscripts,'','',true);

  // enqueue stylesheet here. file does not exist until stylus file is processed
  wp_enqueue_style( 'site', get_stylesheet_directory_uri() . '/css/site.css' );

  // dashicons for admin
  if(is_admin()){
    wp_enqueue_style( 'dashicons' );
  }

}
add_action('wp_enqueue_scripts', 'scripts_and_styles_method');

if( function_exists( 'add_theme_support' ) ) {
  add_theme_support( 'post-thumbnails' );
}

if( function_exists( 'add_image_size' ) ) {
  add_image_size( 'admin-thumb', 150, 150, false );
  add_image_size( 'admin-gallery-thumb', 250, 250, false );

  add_image_size( 'opengraph', 1200, 630, true );

  add_image_size( 'gallery-basic', 800, 533, false );
  add_image_size( 'gallery-large', 1400, 933, false );
  add_image_size( 'gallery-largest', 2000, 1333, false );
}

// Register Nav Menus
/*
register_nav_menus( array(
	'menu_location' => 'Location Name',
) );
*/

get_template_part( 'lib/gallery' );
get_template_part( 'lib/post-types' );
get_template_part( 'lib/meta-boxes' );
get_template_part( 'lib/theme-options' );

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
function cmb_initialize_cmb_meta_boxes() {
  // Add CMB2 plugin
  if( ! class_exists( 'cmb2_bootstrap_202' ) ) {
    require_once 'lib/CMB2/init.php';
/*     require_once 'lib/CMB2-plugins/cmb-field-gallery/cmb-field-gallery.php'; */
  }

  // Add CMB2 Attached Posts Field plugin
  if ( ! function_exists( 'cmb2_attached_posts_fields_render' ) ) {
    require_once 'lib/CMB2-plugins/cmb2-attached-posts/cmb2-attached-posts-field.php';
  }
}

// Disable that freaking admin bar
add_filter('show_admin_bar', '__return_false');

// Turn off version in meta
function no_generator() { return ''; }
add_filter( 'the_generator', 'no_generator' );

// Show thumbnails in admin lists
add_filter('manage_posts_columns', 'new_add_post_thumbnail_column');
function new_add_post_thumbnail_column($cols){
  $cols['new_post_thumb'] = __('Thumbnail');
  return $cols;
}
add_action('manage_posts_custom_column', 'new_display_post_thumbnail_column', 5, 2);
function new_display_post_thumbnail_column($col, $id){
  switch($col){
    case 'new_post_thumb':
    if( function_exists('the_post_thumbnail') ) {
      echo the_post_thumbnail( 'admin-thumb' );
      }
    else
    echo 'Not supported in theme';
    break;
  }
}

// Show fig number in admin lists
add_filter('manage_project_posts_columns', 'new_add_post_fig_column');
function new_add_post_fig_column($cols){
  $cols['new_post_fig'] = __('Fig');
  return $cols;
}
add_action('manage_project_posts_custom_column', 'new_display_post_fig_column', 6, 3);
function new_display_post_fig_column($col, $id){
  switch($col){
    case 'new_post_fig':
    global $post;
    $admin_fig = get_post_meta($post->ID, '_igv_fig', true );
    if( !(empty($admin_fig)) ) {
      echo $admin_fig;
      }
    else
    echo 'Not supported in theme';
    break;
  }
}

// remove automatic <a> links from images in blog
function wpb_imagelink_setup() {
	$image_set = get_option( 'image_default_link_type' );
	if($image_set !== 'none') {
		update_option('image_default_link_type', 'none');
	}
}
add_action('admin_init', 'wpb_imagelink_setup', 10);

// custom login logo
/*
function custom_login_logo() {
  echo '<style type="text/css">h1 a { background-image:url(' . get_bloginfo( 'template_directory' ) . '/images/login-logo.png) !important; background-size:300px auto !important; width:300px !important; }</style>';
}
add_action( 'login_head', 'custom_login_logo' );
*/

// UTILITY FUNCTIONS

// to replace file_get_contents
function url_get_contents($Url) {
  if (!function_exists('curl_init')){
      die('CURL is not installed!');
  }
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $Url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $output = curl_exec($ch);
  curl_close($ch);
  return $output;
}

// get ID of page by slug
function get_id_by_slug($page_slug) {
	$page = get_page_by_path($page_slug);
	if($page) {
		return $page->ID;
	} else {
		return null;
	}
}
// is_single for custom post type
function is_single_type($type, $post) {
  if (get_post_type($post->ID) === $type) {
    return true;
  } else {
    return false;
  }
}

function pr($data){
  echo "<pre>";
  print_r($data);
  echo "</pre>";
}

// PRE GET POSTS

function tag_archive_filter($query) {
  if ( !is_admin() && $query->is_main_query() ) {
    if ($query->is_tag) {
      $query->set('post_type', array( 'post', 'project', 'photograph' ));
    }
  }
}
add_action('pre_get_posts','tag_archive_filter');

// ADD PHOTOGRAPHS TO EMPTY GALLERY ON PROJECT SAVE

function add_photos_empty_gallery( $post_id ) {

  $gallery_key = '_igv_gallery';
  $parent_key = '_igv_parent';

  if ( wp_is_post_revision( $post_id ) ) {
    return;
  } else if (get_post_type($post_id) === 'project') {
    if (empty($_POST[$gallery_key])) {
      $photos = get_posts('fields=ids&post_type=photograph&posts_per_page=-1&meta_key=' . $parent_key . '&meta_value=' . $post_id);
      if ($photos) {
        if( !update_post_meta($post_id, $gallery_key, $photos) ) {
          add_post_meta($post_id, $gallery_key, $photos);
        }
      }
    }
    return;

  } else {
    return;
  }

}
add_action( 'save_post', 'add_photos_empty_gallery', 11 );

// SAVE FIG ON POST SAVE

function set_fig_values( $post_id ) {

  $meta_key = '_igv_fig';

	if ( wp_is_post_revision( $post_id ) ) {
		return;
  } else if (get_post_type($post_id) === 'project') {

    $projects = get_posts('post_type=project&posts_per_page=-1&order=ASC');
    $i = 1;
    foreach ($projects as $post) {
      update_post_meta($post->ID, $meta_key, $i);
      $i++;
    }
    return;

  } else {
    return;
  }

}
add_action( 'save_post', 'set_fig_values' );

// SAVE GALLERY LENGTH ON POST SAVE

function set_gallery_length( $post_id ) {

  $meta_key = '_igv_gallery';

	if ( wp_is_post_revision( $post_id ) ) {
		return;
  } else if (get_post_type($post_id) === 'project') {

    if (isset($_POST[$meta_key])) {
      $gallery = explode(',', $_POST[$meta_key]);
      update_post_meta($post_id, '_igv_gallery_length', count($gallery));
    }
    return;

  } else {
    return;
  }

}
add_action( 'save_post', 'set_gallery_length' );

// SAVE FEATURED IMAGE FROM GALLERY ON POST SAVE

function set_project_featured_image( $post_id ) {

  $meta_key = '_igv_gallery';

  if ( wp_is_post_revision( $post_id ) ) {
    return;
  } else if (get_post_type($post_id) === 'project') {

    if (isset($_POST[$meta_key])) {
      $gallery = explode(',', $_POST[$meta_key]);
      $photo_id = $gallery[0];
      $thumb_id = get_post_thumbnail_id( $photo_id );
      set_post_thumbnail( $post_id, $thumb_id );
    }
    return;

  } else {
    return;
  }

}
add_action( 'save_post', 'set_project_featured_image' );

// SAVE PHOTOGRAPH POSITION IN  GALLERY ON POST SAVE

function set_gallery_index( $post_id ) {

  $meta_key = '_igv_gallery';

  if ( wp_is_post_revision( $post_id ) ) {
    return;
  } else if (get_post_type($post_id) === 'project') {

    if (isset($_POST[$meta_key])) {
      $gallery = explode(',', $_POST[$meta_key]);
      $pos = 1;
      foreach ($gallery as $photo) {
        update_post_meta($photo, '_igv_gallery_index', $pos);
        $pos++;
      }
    }
    return;

  } else {
    return;
  }

}
add_action( 'save_post', 'set_gallery_index' );

// SAVE TAGS FOR PHOTOGRAPHS ON POST SAVE

function save_photograph_tags( $post_id ) {

	if ( wp_is_post_revision( $post_id ) ) {
		return;
  } else if (get_post_type($post_id) === 'photograph') {

    $image = get_attached_file(get_post_thumbnail_id( $post_id ));
    $tags = igv_read_image_keywords($image);

    wp_set_object_terms( $post_id, $tags, 'post_tag' );
    return;

  } else {
    return;
  }

}
add_action( 'save_post', 'save_photograph_tags' );

// SAVE TITLE FOR PHOTOGRAPHS ON POST SAVE

function save_photograph_title( $post_id ) {

  if ( wp_is_post_revision( $post_id ) ) {
    return;
  } else if (get_post_type($post_id) === 'photograph') {

    $attachment_id = get_post_thumbnail_id( $post_id );
    $attachment = get_post( $attachment_id );
    if ($attachment) {
      $title = $attachment->post_title;
      global $wpdb;
      $wpdb->update( $wpdb->posts, array( 'post_title' =>  $title, 'post_name' => $title ), array( 'ID' => $post_id ) );
    }

    return;

  } else {
    return;
  }

}
add_action( 'save_post', 'save_photograph_title' );


// METADATA FOR UPLOADS

get_template_part( 'lib/iptc' );


?>
