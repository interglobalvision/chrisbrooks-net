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
  <div class="spread-wrapper" <?php if ($spread_color) { echo 'style="background-color: ' . $spread_color[0] . '"'; } ?>>
    <div class="home-spread">
<?php
    $spreadImages = get_post_meta($post->ID, '_igv_spread_images');
    foreach ($spreadImages[0] as $image) {
      $img_id = $image['image_id'];
      $imgDefault = wp_get_attachment_image_src($img_id, 'slide-normal');
      $photograph_id = get_post_field( 'post_parent', $img_id);
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

?>
">
        <img class="spread-image u-pointer" src="<?php echo $imgDefault[0]; ?>"/>
        <span class="spread-image-caption"><a href="<?php echo $project_url . '#' . $index; ?>">fig. <?php echo $fig; ?></a>&emsp;<a href="#">&bull;</a></span>
      </div>
<?php
    }
?>
    </div>
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