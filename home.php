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
      $img_id = $image['image_id'];
      $imgDefault = wp_get_attachment_image_src($img_id, 'slide-normal');
      $photograph_id = get_post_field( 'post_parent', $img_id);
      $project_id = get_post_meta($photograph_id, '_igv_parent', true );
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
?>
"<?php
if (!empty($image['scale'])) {
  echo ' data-scale="' . $image['scale'] . '"';
} 
?>>
        <img class="spread-image u-pointer" src="<?php echo $imgDefault[0]; ?>"/>
        <span class="spread-image-caption"><a href="<?php echo $project_url . '#' . $img_id; ?>">fig. <?php echo $fig; ?></a>&emsp;<a href="#">&bull;</a></span>
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