<?php
  get_header();
?>

<?php if ( have_posts() ) : ?>
      <div class="demo-layout mdl-layout mdl-js-layout">
        <main class="mdl-layout__content">
            <div class="demo-cards mdl-grid">
              <?php while ( have_posts() ) : the_post(); ?>
              <?php
                $channel = get_post_meta( get_the_ID(), 'channel', true );

                $image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' )[0];
                
                $response = get_donations($channel);
                $total = $response['total'];
                $kids_helped_count = floor( intval( $response['total'] ) / 250 ) ;
                $target=get_post_meta( get_the_ID(), 'target', true);
                $target=isset($target)?$target:1000;
                
              ?>
              <div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col">
                <div class="mdl-card__title mdl-card--expand mdl-color--teal-300" style="background:url('<?php echo ( $image_url ) ?>') center / cover;">
                </div>
                
                <div class="mdl-card__actions">
		  <?php if ( isset($target) && !empty($target) ): ?>
		  <div class="mdl-cell mdl-cell--10-col"><span style="color: red; font-weight:bold;"><?php echo $target; ?></span>  USD Target</div>
		<?php endif; ?>
                    <div class="mdl-cell mdl-cell--10-col"><span style="color: red; font-weight:bold;"><?php echo intval( $total ); ?></span> USD raised</div>
                    <div class="mdl-cell mdl-cell--10-col"><span style="color: red; font-weight:bold;"><?php echo( $response['count']); ?></span> Supporters </div>
                </div>
              
                <div class="mdl-card__actions mdl-card--border">
                  <a href="<?php echo post_permalink(); ?>" class="mdl-button mdl-js-button mdl-js-ripple-effect"><?php explode(' ', the_title(), 2); ?></a>
                </div>
              </div>
              <?php endwhile; ?>
           </div>
      </main>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.2/material.min.js"></script>

<?php endif; ?>
<?php get_footer(); ?>
