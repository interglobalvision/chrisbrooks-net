<?php
/*
Template Name: List archive
*/
get_header();
$args = array(
  'posts_per_page' => -1,
  'post_type' => 'project',
  'order' => 'ASC'
);
?>

<!-- main content -->

<main id="main-content" class="container">

  <!-- main posts loop -->
  <section id="posts" class="row js-packery-container">

<?php
$posts = get_posts($args);
if ($posts) {
  foreach ($posts as $post) {
    $meta = get_post_meta($post->ID);
?>

    <article <?php post_class('col col6 list-project js-packery-item'); ?> id="post-<?php the_ID(); ?>">
      <a href="<?php the_permalink() ?>">
<?php
if (!empty($meta['_igv_fig'][0])) {
  echo '<span class="list-fig">fig.' . $meta['_igv_fig'][0] . '</span>';
}
echo '<h4 class="list-title">';
echo '<span class="font-italic">';
the_title();
echo '</span>';
if (!empty($meta['_igv_year'][0])) {
  echo ', ' . $meta['_igv_year'][0];
}
echo '</h4>';
if (!empty($meta['_igv_gallery_length'][0]) && $meta['_igv_gallery_length'][0] > 1) {
  echo '<div class="list-series-count">series of ' . $meta['_igv_gallery_length'][0] . ' images</div>';
}
?>
      </a>
    </article>

<?php
  }
}
?>

  <!-- end posts -->
  </section>

<!-- end main-content -->

</main>

<?php
get_footer();
?>