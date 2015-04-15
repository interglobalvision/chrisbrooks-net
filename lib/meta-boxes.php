<?php

/* Get post objects for select field options */
function get_post_objects( $query_args ) {
  $args = wp_parse_args( $query_args, array(
      'post_type' => 'post',
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
      'show_names'    => false, // Show field names on the left
      // 'cmb_styles' => false, // false to disable the CMB stylesheet
      // 'closed'     => true, // true to keep the metabox closed by default
    ) );

  $gallery->add_field( array(
      'name'    => __( 'Gallery', 'cmb2' ),
      'desc'    => __( 'Slider gallery', 'cmb2' ),
      'id'      => $prefix . 'gallery',
      'type'    => 'wysiwyg',
      'options' => array(
        'textarea_rows' => 5,
        'media_buttons' => true,
        'tinymce' => true,
        'quicktags' => false,
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

    // SLIDE

  $slide_meta = new_cmb2_box( array(
      'id'            => $prefix . 'slide_metabox',
      'title'         => __( 'Slide images', 'cmb2' ),
      'object_types'  => array( 'slide', ), // Post type
      'context'       => 'normal',
      'priority'      => 'high',
      'show_names'    => true, // Show field names on the left
    ) );

  $slide_meta_group = $slide_meta->add_field( array(
      'id'          => $prefix . 'slide_images',
      'type'        => 'group',
      'description' => __( 'Generates reusable form entries', 'cmb' ),
      'options'     => array(
        'group_title'   => __( 'Entry {#}', 'cmb' ), // since version 1.1.4, {#} gets replaced by row number
        'add_button'    => __( 'Add Another Entry', 'cmb' ),
        'remove_button' => __( 'Remove Entry', 'cmb' ),
        'sortable'      => true, // beta
      ),
    ) );

  // Id's for group's fields only need to be unique for the group. Prefix is not needed.
  $slide_meta->add_group_field( $slide_meta_group, array(
      'name' => 'Image',
      'description' => 'DO NOT upload a file here! Choose an existing upload that you have already added to a project',
      'id'   => 'image',
      'type' => 'file',
    ) );

  $slide_meta->add_group_field( $slide_meta_group, array(
      'name' => 'Top',
      'description' => '% value for css',
      'id'   => 'top',
      'type' => 'text',
    ) );

  $slide_meta->add_group_field( $slide_meta_group, array(
      'name' => 'Left',
      'description' => '% value for css',
      'id'   => 'left',
      'type' => 'text',
    ) );

  $slide_meta->add_group_field( $slide_meta_group, array(
      'name' => 'Bottom',
      'description' => '% value for css',
      'id'   => 'bottom',
      'type' => 'text',
    ) );

  $slide_meta->add_group_field( $slide_meta_group, array(
      'name' => 'Right',
      'description' => '% value for css',
      'id'   => 'right',
      'type' => 'text',
    ) );

}

?>