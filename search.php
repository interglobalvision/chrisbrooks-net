<?php
get_header();

$search_types = array('post', 'project', 'page', 'attachment');

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
  'tag' => $search_term,
  'post_status' => array('published', 'inherit')
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
    'order'     => 'DESC',
    'post_status' => array('published', 'inherit')
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
  <section id="posts" class="row">

<?php
if( $search->have_posts() ) {
  while( $search->have_posts() ) {
    $search->the_post();
?>


    <article <?php post_class('percent-col into-3'); ?> id="post-<?php the_ID(); ?>">
      <a href="<?php the_permalink() ?>">
    <?php 
    if( get_post_type() == 'attachment') {
      $thumb = wp_get_attachment_image_src( $post->ID, 'thumbnail' );
    ?>
        <img src="<?php echo $thumb[0];?>">
    <?php
    }
    ?>
        <?php the_post_thumbnail(); ?>
      </a>
    </article>

<?php
  }
} else {
?>
    <article class="u-alert"><?php _e('Sorry, no posts matched your criteria :{'); ?></article>
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
