<?php
get_header();
?>

<!-- main content -->

<main id="main-content">
  <section id="slide-container">
<?php
$slide = get_posts(array(
  'post_type' => 'slide',
  'posts_per_page' => -1
));
if ($slide) {
  $post = $slide[0];
  $slideImages = get_post_meta($post->ID, '_igv_slide_images');
  foreach ($slideImages[0] as $image) {
    $imgDefault = wp_get_attachment_image_src($image['image_id'], 'slide-normal');
?>
    <img class="slide-image" src="<?php echo $imgDefault[0]; ?>" style="
<?php
    if (!empty($image['top'])) {
      echo 'top: ' . $image['top'] . '%;';
    }
    if (!empty($image['left'])) {
      echo 'left: ' . $image['left'] . '%;';
    }
    if (!empty($image['bottom'])) {
      echo 'bottom: ' . $image['bottom'] . '%;';
    }
    if (!empty($image['right'])) {
      echo 'right: ' . $image['right'] . '%;';
    }
?>
"/>
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