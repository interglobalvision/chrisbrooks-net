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
    $nextPost = get_adjacent_post(null, null, false);
    $prevPost = get_adjacent_post(null, null, true);
    if ($nextPost) {
      $nextLink = get_permalink($nextPost->ID);
    } else {
      $nextLink = home_url('list-of-works/');
    }
    if ($prevPost) {
      $prevLength = get_post_meta( $prevPost->ID, '_igv_gallery_length', true );
      if ($prevLength === '1') {
        $prevLink = get_permalink($prevPost->ID);
      } else {
        $prevLink = get_permalink($prevPost->ID) . '#' . $prevLength;
      }
    } else {
      $prevLink = home_url('list-of-works/');
    }
?>

    <article <?php post_class('viewer'); ?> id="post-<?php the_ID(); ?>">

      <div id="single-slider">

        <nav id="single-close" class="single-nav">
          <a href="<?php echo home_url('list-of-works/'); ?>">
            <span class="font-italic">list of works</span>
          </a>
        </nav>

        <nav id="single-next" class="single-nav" data-gallery-length="<?php echo $length; ?>">
          <a href="<?php echo $nextLink; ?>">
          <div class="u-holder">
            <div class="u-align-right u-held">
              <span>&#8594;</span>
            </div>
          </div>
          </a>
        </nav>

        <nav id="single-prev" class="single-nav">
          <a href="<?php echo $prevLink; ?>">
            <div class="u-holder">
              <div class="u-held">
                  <span>&#8592;</span>
              </div>
            </div>
          </a>
        </nav>

        <div class="js-slick-container">
<?php
        if ($images) {

          foreach($images as $image) {
            $post_id = $image;
            $post = get_post($post_id);
            $index = get_post_meta( $post_id, '_igv_gallery_index', true);

            $img_id = get_post_thumbnail_id( $post_id );

            $img = wp_get_attachment_image_src($img_id, 'gallery-basic');
            $imgLarge = wp_get_attachment_image_src($img_id, 'gallery-large');
            $imgLarger = wp_get_attachment_image_src($img_id, 'gallery-larger');
            $imgLargest = wp_get_attachment_image_src($img_id, 'gallery-largest');

            $photo_title = $post->post_title;
            $photo_caption = $post->post_content;
?>
            <div class="js-slick-item slider-item"
            data-index="<?php echo $index; ?>">
              <div class="u-holder">
                <div class="u-held">
                  <a <?php
              if ($length[0] === 1 || $index === $length) {
                echo 'href="' . $nextLink . '"';
              } else {
                echo 'class="js-next-slide u-pointer"';
              }
?>>
                    <img class="slider-img"
                      data-basic="<?php echo $img[0]; ?>"
                      data-large="<?php echo $imgLarge[0]; ?>"
                      data-larger="<?php echo $imgLarger[0]; ?>"
                      data-largest="<?php echo $imgLargest[0]; ?>" />
                  </a>
                  <div class="single-slider-text font-caption">
                    <?php
                      if (!empty($fig)) {
                        echo 'fig. ' . $fig;
                      }

                      echo '<span class="caption-main">';

                      if ($length[0] > 1 && ! empty($title)) {
                        echo $title . ', ';
                      }

                      if (!empty($photo_title)) {
                        echo '<em>' . $photo_title . '</em>';
                      }

                      if (!empty($photo_caption)) {
                        echo ', ' . $photo_caption;
                      }

                      echo '</span>';

                      if ($length > 1) {
                        echo '(' . $index . ' of ' . $length . ')';
                      } ?>
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