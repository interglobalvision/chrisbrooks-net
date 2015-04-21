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
  $i = 0;
  foreach ($spreads as $post) {
?>
    <div class="home-spread<?php
    if ($i === 0) {
      echo ' home-spread-active';
    }
?>">
<?php
    $spreadImages = get_post_meta($post->ID, '_igv_spread_images');
    foreach ($spreadImages[0] as $image) {
      $imgDefault = wp_get_attachment_image_src($image['image_id'], 'slide-normal');
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
?>
">
        <img class="spread-image u-pointer" src="<?php echo $imgDefault[0]; ?>"/>
      </div>
<?php
    }
?>    
    </div>
<?php
  $i++;
  }
}
?>

  </section>
<!-- end main-content -->
</main>

<?php
get_footer();
?>