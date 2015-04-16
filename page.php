<?php
get_header();
?>

<!-- main content -->

<main id="main-content">


<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();
?>

  <article id="page" <?php post_class('container'); ?>>
    <div class="row">
      <div class="col col2">
        <?php the_post_thumbnail(); ?>
      </div>

      <div class="col col8">
        <h4><?php the_title(); ?></h4>
        <?php the_content(); ?>
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
} ?>

  <?php get_template_part('partials/pagination'); ?>

<!-- end main-content -->

</main>

<?php
get_footer();
?>