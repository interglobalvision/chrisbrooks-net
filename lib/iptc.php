<?php
// built from wp_read_image_metadata /wp-admin/includes/image.php. why not just parse keywords eh WP?!
function igv_read_image_keywords( $file ) {
	if ( ! file_exists( $file ) ) {
		return false;
  }

/* 	list( , , $sourceImageType ) = getimagesize( $file ); */

	$keywords;

	if ( is_callable( 'iptcparse' ) ) {
		getimagesize( $file, $info );

		if ( ! empty( $info['APP13'] ) ) {
			$iptc = iptcparse( $info['APP13'] );

			if ( ! empty( $iptc["2#025"] ) ) {

/*
  		  $keywords = '';

  			foreach ($iptc["2#025"] as $keyword) {
    			if ($keywords === '') {
      			$keywords = $keyword;
    			} else {
      			$keywords = $keywords . ', ' . $keyword;
    			}
  			}
*/

        $keywords = [];

  			foreach ($iptc["2#025"] as $keyword) {
    		  $keywords[] = $keyword;
  			}

		  }

		 }
	}

/*
	foreach ( array( 'title', 'caption', 'credit', 'copyright', 'camera', 'iso', 'keywords' ) as $key ) {
		if ( $meta[ $key ] && ! seems_utf8( $meta[ $key ] ) ) {
			$meta[ $key ] = utf8_encode( $meta[ $key ] );
		}
	}
*/

/*
	foreach ( $meta as &$value ) {
		if ( is_string( $value ) ) {
			$value = wp_kses_post( $value );
		}
	}
*/
  if (!empty($keywords)) {
  	return $keywords;
  } else {
    return false;
  }

}

// WORDPRESS

function get_upload_metatags($id){

  $keywords = igv_read_image_keywords(get_attached_file($id));

  if ($keywords) {

    $term_taxonomy_ids = wp_set_object_terms( $id, $keywords, 'post_tag', false );

    if ( is_wp_error( $term_taxonomy_ids ) ) {
      error_log((string)$term_taxonomy_ids);
    }

  }

}
add_action( 'add_attachment', 'get_upload_metatags' );


// Enable tags for attachments
function igv_add_tags_to_attachments() {
    register_taxonomy_for_object_type( 'post_tag', 'attachment' );
}
add_action( 'init' , 'igv_add_tags_to_attachments' );