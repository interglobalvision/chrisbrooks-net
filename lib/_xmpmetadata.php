<?php
// FUNCTIONS

function setArrayValue(&$array, $stack, $value) {

		if ($stack) {
			$key = array_shift($stack);
			//print $key;
			//TODO:Review this, reports sometimes a error "Fatal error: Only variables can be passed by reference" (PHP 5.2.6)

	    	setArrayValue($array[$key], $stack, $value);

	    	return $array;
	  	} else {
	    	$array = $value;


	  	}
}

function extractXmp($file) {

  $xml_array = array();
  //TODO:Require a lot of memory, could be better
  ob_start();
  @readfile($file);
  $source = ob_get_contents();
  ob_end_clean();
  $source;
  $start = strpos( $source, "<x:xmpmeta"   );
  $end   = strpos( $source, "</x:xmpmeta>" );
  if ((!$start === false) && (!$end === false)) {
    $lenght = $end - $start;
    $xmp_data = substr($source, $start, $lenght+12 );
    unset($source);
    //print_r($xmp_data);
    $xml_array = XMP2Array($xmp_data);
  }

  unset($source);
  return $xml_array;
}

function XMP2array($data) {

  $parser = xml_parser_create();
  xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0); // Dont mess with my cAsE sEtTings
  xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); // Dont bother with empty info
  xml_parse_into_struct($parser, $data, $values);
  xml_parser_free($parser);
  //print_r($values);

  $xmlarray   = array(); // The XML array
  $xmp_array    = array(); // The returned array
  $stack          = array(); // tmp array used for stacking
  $list_array     = array(); // tmp array for list elements
  $list_element   = false; // rdf:li indicator
  $temp_attr    = array();
  $last_open_tag   = '';

  foreach($values as $val) {

    if($val['type'] === "open") {


      if ( array_key_exists('attributes', $val) &&  $val['attributes'] ) {
        $temp_attr[$val['tag']] = $val['attributes'];
      } else {
        array_push($stack, $val['tag']);
      }
      $last_open_tag = $val['tag'];



    } elseif($val['type'] === "close") {
      // reset the compared stack
      if ($list_element == false) {
        if ( ! array_key_exists('value', $stack) || !$stack['value']) {
          if (array_key_exists($val['tag'], $temp_attr)) {
            $xmlarray[$val['tag']] = $temp_attr[$val['tag']];
          }

        }
      }
      $last_open_tag = '';
      array_pop($stack);
      // reset the rdf:li indicator & array
      $list_element = false;
      $list_array   = array();

    } elseif($val['type'] === "complete") {
      if ($val['tag'] === "rdf:li") {
        // first go one element back
        if ($list_element == false)
          array_pop($stack);

        $list_element = true;
        // save it in our temp array
        if (array_key_exists('value', $val)) {
          $list_array[] = $val['value'];
        }
        //print_r( $val['value']);
        // in the case it's a list element we seralize it
        //$value = implode(",", $list_array);
        setArrayValue($xmlarray, $stack, $list_array);


      } else {
        array_push($stack, $val['tag']);
        if (array_key_exists('value', $val)) {
          setArrayValue($xmlarray, $stack, $val['value']);
        } elseif (array_key_exists('attributes', $val)){
          $xmlarray[$val['tag']] = $val['attributes'];
        }
        array_pop($stack);
      }
    }

  } // foreach

  // cut off the useless tags
  $strip_keys = array('x:xmpmeta','rdf:RDF');

  foreach ($strip_keys as $k) {
    unset($xmlarray[$k]);
  }


  //print_r($xmlarray);
  return $xmlarray;
}



// WORDPRESS

function get_upload_metatags($id){

  $xmpMeta = extractXmp(get_attached_file($id));
  var_dump($extractXmp);

}
add_action( 'add_attachment', 'get_upload_metatags' );