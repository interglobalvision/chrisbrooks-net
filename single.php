<?php
get_header();
?>

<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();
    $images = get_post_meta( $post->ID, '_igv_gallery', true );
    $length = get_post_meta( $post->ID, '_igv_gallery_length', true );
    $fig = get_post_meta( $post->ID, '_igv_fig', true );
    $title = get_the_title( $post );
?>

    <article <?php post_class('viewer'); ?> id="post-<?php the_ID(); ?>">

      <div id="single-slider">

        <nav id="single-close" class="single-nav">
          <a href="<?php echo home_url('project/'); ?>">
            <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/close.svg'); ?>
          </a>
        </nav>
<?php
  $nextPost = get_adjacent_post(null, null, false);
  if ($nextPost) {
    $nextLink = get_permalink($nextPost->ID);
?>
        <nav id="single-next" class="single-nav">
          <a href="<?php echo $nextLink; ?>">
            <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/next.svg'); ?>
          </a>
        </nav>
<?php
  }
  $previousPost = get_adjacent_post();
  if ($previousPost) {
    $prevLink = get_permalink($previousPost->ID);
?>
        <nav id="single-prev" class="single-nav">
          <a href="<?php echo $prevLink; ?>">
            <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/prev.svg'); ?>
          </a>
        </nav>
<?php } ?>
        <div class="js-slick-container<?php if ($length != 1) {echo ' u-pointer';} ?>">
<?php
        if ($images) {
          foreach($images as $image) {
            $post_id = $image;
            $index = get_post_meta( $post_id, '_igv_gallery_index', true);
            $img_id = get_post_thumbnail_id( $post_id );
            $img_obj = get_post( $img_id );
            $img = wp_get_attachment_image_src($img_id, 'gallery-basic');
            $imgLarge = wp_get_attachment_image_src($img_id, 'gallery-large');
            $imgLargest = wp_get_attachment_image_src($img_id, 'gallery-largest');
            $photo_title = get_the_title($post_id);
            $photo_caption = $img_obj->post_excerpt;
?>
            <div class="js-slick-item slider-item"
            data-index="<?php echo $index; ?>">
              <div class="u-holder">
                <div class="u-held">
                  <img class="slider-img"
                  data-basic="<?php echo $img[0]; ?>"
                  data-large="<?php echo $imgLarge[0]; ?>"
                  data-largest="<?php echo $imgLargest[0]; ?>" />
                  <div id="single-slider-text" class="font-caption">
                    <span><?php
                      if (! empty($fig)) {
                        echo 'fig. ' . $fig;
                      }

                      if ($length[0] > 1 && ! empty($title)) {
                        echo ', ' . $title;
                      }

                      if (! empty($photo_title)) {
                        echo ', <em>' . $photo_title . '</em>';
                      }

                      if (! empty($photo_caption)) {
                        echo ', <em>' . $photo_caption . '</em>';
                      }

                      if ($length[0] > 1) {
                        echo ', ' . $index . ' of ' . $length[0];
                        echo ', </span><span id="slick-prev" class="u-pointer">Prev</span><span> / </span><span id="slick-next" class="u-pointer">Next';
                      } ?></span>
                  </div>
                </div>
              </div>
            </div><!-- end js-slick-item -->
<?php
          }
        }
?>
        </div><!-- end js-slick-container -->

      </div>

      <section id="single-copy" class="container">
        <div class="row">
          <div class="col col6">
	          <?php the_content(); ?>
          </div>
        </div>
      </section>

    </article>

<?php
  }
} else {
?>
    <article class="u-alert"><?php _e('Sorry, no posts matched your criteria :{'); ?></article>
<?php
} ?>

<?php
get_footer();
?>