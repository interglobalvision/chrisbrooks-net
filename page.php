<?php
get_header();
?>

<!-- main content -->

<main id="main-content">


<?php
if (!is_page('Search')) {
  if( have_posts() ) {
    while( have_posts() ) {
      the_post();
        $meta = get_post_meta($post->ID);
?>

  <article id="page" <?php post_class('container'); ?>>
    <div class="row">
      <div id="page-main-copy" class="col col6 page-copy">
        <?php the_content(); ?>
      </div>
      <div class="col col1">&nbsp;</div>
      <div id="page-extra-copy" class="col col5">
        <?php
          if (!empty($meta['_igv_extra_copy'][0])) {
            echo wpautop($meta['_igv_extra_copy'][0]);
          }
        ?>
      </div>
    </div>
  </article>

<?php
    }
  } else {
?>
    <div class="container">
      <article class="col col12 u-alert"><?php _e('Sorry, no posts matched your criteria'); ?></article>
    </div>
<?php
  }
}
?>

  <?php get_template_part('partials/pagination'); ?>

<!-- end main-content -->

</main>

<?php
get_footer();
?>