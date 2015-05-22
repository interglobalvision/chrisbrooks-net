<?php
get_header();
?>

<!-- main content -->

<main id="main-content">

  <!-- main posts loop -->
  <section id="posts" class="row js-packery-container">

<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();
    $meta = get_post_meta( $post->ID );
    $img_id = get_post_thumbnail_id( $post->ID );
    $img = wp_get_attachment_image_src($img_id, 'grid-basic');
    $imgLarge = wp_get_attachment_image_src($img_id, 'grid-large');
    $imgLarger = wp_get_attachment_image_src($img_id, 'grid-larger');
    $imgLargest = wp_get_attachment_image_src($img_id, 'grid-largest');
?>

    <article <?php post_class('percent-col into-5 grid-hover js-packery-item'); ?> id="post-<?php the_ID(); ?>">
      <a href="<?php the_permalink(); ?>" class="grid-link">
        <img class="js-grid-img"
          data-basic="<?php echo $img[0]; ?>"
          data-large="<?php echo $imgLarge[0]; ?>"
          data-larger="<?php echo $imgLarger[0]; ?>"
          data-largest="<?php echo $imgLargest[0]; ?>" />
        <div class="grid-hover-holder">
          <div class="u-holder">
            <div class="u-held">
              <span>fig. <?php echo $meta['_igv_fig'][0]; ?></span>
              <h2 class="font-italic"><?php the_title(); ?></h2>
<?php
if (!empty($meta['_igv_gallery_length'][0]) && $meta['_igv_gallery_length'][0] > 1) {
  echo 'series of ' . $meta['_igv_gallery_length'][0] . ' images';
}
?>
            </div>
          </div>
        </div>
      </a>
    </article>

<?php
  }
} else {
?>
    <div class="container">
      <article class="col col12 u-alert"><?php _e('Sorry, no posts matched your criteria'); ?></article>
    </div>
<?php
} ?>
  <!-- end posts -->
  </section>

  <?php get_template_part('partials/pagination'); ?>

<!-- end main-content -->

</main>

<?php
get_footer();
?>