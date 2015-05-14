<?php
get_header();

$search_types = array('photograph');

$search_term =  $_GET['s'];

// Request video search by 's' (default)
$search_default = new WP_Query( array (
  'fields' => 'ids',
  'post_type' => $search_types,
  's' => $search_term,
) );

// Request video search by 'tag'
$search_tag = new WP_Query( array (
  'fields' => 'ids',
  'post_type' => $search_types,
  'tag' => $search_term
) );

// If any of the video searches have posts
if( $search_default->have_posts() || $search_tag->have_posts() ) {

/*
  var_dump($search_default->posts);
  var_dump($search_tag->posts);
 */

  // Merge IDs
  $search_ids = array_merge( $search_default->posts, $search_tag->posts );

  //  Request video search query by IDs
  $search =  new WP_Query(array(
    'post_type' => $search_types,
    'post__in'  => $search_ids,
    'orderby'   => 'date',
    'order'     => 'DESC'
  ) );

  /*   var_dump($search); */

} else {

  // Blank query
  $search =  new WP_Query();

}
?>

<!-- main content -->

<main id="main-content">

  <!-- main posts loop -->
  <section id="posts" class="row js-packery-container">

<?php
if( $search->have_posts() ) {
  while( $search->have_posts() ) {
    $search->the_post();
      $parent = get_post_meta($post->ID, '_igv_parent');
      $parent_meta = get_post_meta( $parent[0] );
?>

    <article <?php post_class('percent-col into-5 grid-hover js-packery-item'); ?> id="post-<?php the_ID(); ?>">
      <a href="<?php
echo get_the_permalink($parent[0]);
?>">
        <?php the_post_thumbnail(); ?>
        <div class="grid-hover-holder">
          <div class="u-holder">
            <div class="u-held">
              <span>fig. <?php echo $parent_meta['_igv_fig'][0]; ?></span>
              <h2 class="font-italic"><?php the_title(); ?></h2>
<?php
if (!empty($parent_meta['_igv_gallery_length'][0]) && $parent_meta['_igv_gallery_length'][0] > 1) {
  echo 'series of ' . $parent_meta['_igv_gallery_length'][0] . ' images';
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
    <article class="u-alert"><?php _e('Sorry, no posts matched your criteria'); ?></article>
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
