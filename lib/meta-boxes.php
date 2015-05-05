<?php

/* Get post objects for select field options */
function get_post_objects( $query_args ) {
  $args = wp_parse_args( $query_args, array(
      'post_type' => 'project',
    ) );
  $posts = get_posts( $args );
  $post_options = array();
  if ( $posts ) {
    foreach ( $posts as $post ) {
      $post_options [ $post->ID ] = $post->post_title;
    }
  }
  return $post_options;
}


/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

/**
 * Hook in and add metaboxes. Can only happen on the 'cmb2_init' hook.
 */
add_action( 'cmb2_init', 'igv_cmb_metaboxes' );
function igv_cmb_metaboxes() {

  // Start with an underscore to hide fields from custom fields list
  $prefix = '_igv_';

  if (isset($_GET['post'])) {
    $post_ID = $_GET['post'];
  } else {
    $post_ID = null;
  }

  $parent_args = array(
    'post_type' => 'project'
  );

  $gallery_args = array(
    'post_type'   => 'photograph',
    'meta_query' => array(
      array(
        'key' => '_igv_parent',
        'value' => $post_ID,
        'type' => 'NUMERIC',
        'compare' => '='
      )
    )
  );

  /**
   * Metaboxes declarations here
   * Reference: https://github.com/WebDevStudios/CMB2/blob/master/example-functions.php
   */

  $gallery = new_cmb2_box( array(
      'id'            => $prefix . 'gallery_metabox',
      'title'         => __( 'Gallery', 'cmb2' ),
      'object_types'  => array( 'project', ), // Post type
      'context'       => 'normal',
      'priority'      => 'high',
      'show_names'    => true, // Show field names on the left
    ) );

  $gallery->add_field( array(
    'name'    => __( 'Gallery', 'cmb2' ),
    'desc'    => __( 'Create the gallery in the right hand column', 'cmb2' ),
    'id'      => $prefix . 'gallery',
    'type'    => 'custom_attached_posts',
    'options' => array(
        'query_args' => $gallery_args,
        'show_thumbnails' => true
      ),
    ) );

  $project_meta = new_cmb2_box( array(
      'id'            => $prefix . 'project_metabox',
      'title'         => __( 'Project meta', 'cmb2' ),
      'object_types'  => array( 'project', ), // Post type
      'context'       => 'normal',
      'priority'      => 'high',
      'show_names'    => true, // Show field names on the left
    ) );

  $project_meta->add_field( array(
      'name'    => __( 'Year', 'cmb2' ),
      'desc'    => __( '...', 'cmb2' ),
      'id'      => $prefix . 'year',
      'type'    => 'text'
    ) );

    // PHOTOGRAPH

  $photo_meta = new_cmb2_box( array(
      'id'            => $prefix . 'photograph_metabox',
      'title'         => __( 'Photograph meta', 'cmb2' ),
      'object_types'  => array( 'photograph' ),
      'context'       => 'normal',
      'priority'      => 'high',
      'show_names'    => true,
    ) );

  $photo_meta->add_field( array(
    'name' => 'Photograph details',
    'desc' => 'Set the caption for the photograph as the title of this post. Set the image as the featured image.',
    'type' => 'title',
    'id'   => $prefix . 'instructions'
  ) );

  $photo_meta->add_field( array(
      'name'    => __( 'Parent project', 'cmb2' ),
      'desc'    => __( 'Choose the project which this photograph belongs to', 'cmb2' ),
      'id'      => $prefix . 'parent',
      'type'    => 'select',
      'show_option_none' => true,
      'options' => get_post_objects($parent_args),
    ) );


    // SPREAD

  $spread_meta = new_cmb2_box( array(
      'id'            => $prefix . 'spread_metabox',
      'title'         => __( 'Spread images', 'cmb2' ),
      'object_types'  => array( 'spread', ), // Post type
      'context'       => 'normal',
      'priority'      => 'high',
      'show_names'    => true, // Show field names on the left
    ) );

  $spread_colorpicker = $spread_meta->add_field( array(
    'name'    => 'Spread color',
    'id'      => $prefix . 'spread_color',
    'type'    => 'colorpicker',
    'default' => '#FDFDFD',
  ) );

  $spread_meta_group = $spread_meta->add_field( array(
      'id'          => $prefix . 'spread_images',
      'type'        => 'group',
      'description' => __( 'Generates reusable form entries', 'cmb' ),
      'options'     => array(
        'group_title'   => __( 'Entry {#}', 'cmb' ), // since version 1.1.4, {#} gets replaced by row number
        'add_button'    => __( 'Add Another Entry', 'cmb' ),
        'remove_button' => __( 'Remove Entry', 'cmb' ),
        'sortable'      => true, // beta
      ),
    ) );

  $spread_meta->add_group_field( $spread_meta_group, array(
      'name' => 'Image',
      'description' => 'DO NOT upload a file here! Choose an existing upload that you have already added to a project',
      'id'   => 'image',
      'type' => 'file',
    ) );

  $spread_meta->add_group_field( $spread_meta_group, array(
      'name' => 'Top',
      'description' => '% value for css',
      'id'   => 'top',
      'type' => 'text',
    ) );

  $spread_meta->add_group_field( $spread_meta_group, array(
      'name' => 'Left',
      'description' => '% value for css',
      'id'   => 'left',
      'type' => 'text',
    ) );

  $spread_meta->add_group_field( $spread_meta_group, array(
      'name' => 'Right',
      'description' => '% value for css',
      'id'   => 'right',
      'type' => 'text',
    ) );

  $spread_meta->add_group_field( $spread_meta_group, array(
      'name' => 'Max-width',
      'description' => '% value for css',
      'id'   => 'maxwidth',
      'type' => 'text',
    ) );

/*
  $spread_meta->add_group_field( $spread_meta_group, array(
      'name' => 'Scale',
      'description' => '% value for css',
      'id'   => 'scale',
      'type' => 'text',
    ) );
*/

}

?>