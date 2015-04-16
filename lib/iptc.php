<?php
// built from wp_read_image_metadata /wp-admin/includes/image.php. why not just parse keywords eh WP?!
function igv_read_image_keywords( $file ) {
	if ( ! file_exists( $file ) ) {
		return false;
  }

	$keywords;

	if ( is_callable( 'iptcparse' ) ) {
		getimagesize( $file, $info );

		if ( ! empty( $info['APP13'] ) ) {
			$iptc = iptcparse( $info['APP13'] );

			if ( ! empty( $iptc["2#025"] ) ) {

        $keywords = [];

  			foreach ($iptc["2#025"] as $keyword) {
    		  $keywords[] = $keyword;
  			}

		  }

		 }
	}

  if (!empty($keywords)) {
  	return $keywords;
  } else {
    return false;
  }

}