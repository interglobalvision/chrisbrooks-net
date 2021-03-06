<?php
get_header();
?>

<!-- main content -->

<main id="main-content">
  <section id="spread-container">
<?php
$spreads = get_posts(array(
  'post_type' => 'spread',
  'posts_per_page' => -1
));
if ($spreads) {
  foreach ($spreads as $post) {
    $spread_color = get_post_meta( $post->ID, '_igv_spread_color' );
?>
    <div class="home-spread u-pointer"<?php if ($spread_color) { echo ' data-color="' . $spread_color[0] . '"'; } ?>>
<?php
    $spreadImages = get_post_meta($post->ID, '_igv_spread_images');
    foreach ($spreadImages[0] as $image) {
      $img_id = $image['image_id'];

      $img = wp_get_attachment_image_src($img_id, 'gallery-basic');
      $imgLarge = wp_get_attachment_image_src($img_id, 'gallery-large');
      $imgLarger = wp_get_attachment_image_src($img_id, 'gallery-larger');
      $imgLargest = wp_get_attachment_image_src($img_id, 'gallery-largest');

      $img_attachment = get_post($img_id);

      $photograph_id = $img_attachment->post_parent;
      $project_id = get_post_meta($photograph_id, '_igv_parent', true );

      $index = get_post_meta($photograph_id, '_igv_gallery_index', true );

      $project_url = get_permalink( $project_id );
      $fig = get_post_meta($project_id, '_igv_fig', true );

?>
      <div class="spread-image-wrapper" style="
<?php
      if (!empty($image['top'])) {
        echo 'top: ' . $image['top'] . '%;';
      }
      if (!empty($image['left'])) {
        echo 'left: ' . $image['left'] . '%;';
      }
      if (!empty($image['right'])) {
        echo 'right: ' . $image['right'] . '%;';
      }
      if (!empty($image['maxwidth'])) {
        echo 'max-width: ' . $image['maxwidth'] . '%;';
      }
?>">
        <img class="spread-image"
          data-basic="<?php echo $img[0]; ?>"
          data-large="<?php echo $imgLarge[0]; ?>"
          data-larger="<?php echo $imgLarger[0]; ?>"
          data-largest="<?php echo $imgLargest[0]; ?>" />
<?php
  if (!empty($project_id)) {
?>
        <span class="spread-image-caption font-caption"><a href="<?php echo $project_url . '#' . $index; ?>">fig. <?php echo $fig; ?></a></span>
<?php
  }
?>
      </div>
<?php
    }
?>
    </div>
<?php
  }
}
?>

  </section>
<!-- end main-content -->
</main>

<?php
get_footer();
?>