<?php
  get_header();
?>
<?php while ( have_posts() ) : the_post(); ?>
      <div class="demo-layout mdl-layout mdl-js-layout">
        <main class="mdl-layout__content mdl-color--grey-40">
          <div class="mdl-grid demo-content">
            <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--8-col">
              <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                  <strong><?php the_title(); ?></strong>
                </header>
              <?php the_content(); ?>
              </article>
            </div>
            <?php
              $channel = get_post_meta( get_the_ID(), 'channel', true );
              $image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' )[0];
              
              $response = get_donations($channel);
              $total = isset($response['total'])?$response['total']:0;
              $kids_helped_count = floor( intval( $response['total'] ) / 250 ) ;
	      
              $event= get_post_meta( get_the_ID(), 'event', true );
              $chapter=get_post_meta( get_the_ID(), 'chapter', true );
              $project=get_post_meta( get_the_ID(), 'project', true );
              $target=get_post_meta( get_the_ID(), 'target', true); 
              $target=isset($target)?$target:1000; 		      
              $donation_url = "https://donate.ashanet.org/donate-new/?e=" . $event . "&l=" . $channel . "&c=" . $chapter . "&p=" . $project;
              
            ?>
            <div class="demo-cards mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">
              <div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
                <div class="mdl-card__title mdl-card--expand mdl-color--teal-300" style="background:url('<?php echo ( $image_url ) ?>') center / cover;">
                  <!-- <h2 class="mdl-card--expand mdl-card__title-text"><?php the_title(); ?></h2> -->
                </div>
		  
                <div class="mdl-card__supporting-text mdl-color-text--grey-600">
		<div class="mdl-card__actions">
		<div class="mdl-cell mdl-cell--10-col"><span style="color: red; font-weight:bold;"><?php echo ($target); ?></span> USD Target</div>                    
		<div class="mdl-cell mdl-cell--10-col"><span style="color: red; font-weight:bold;"><?php echo sprintf("%' 4d\n", intval( $total )); ?></span> USD Raised </div>
                    <div class="mdl-cell mdl-cell--10-col"><span style="color: red; font-weight:bold;"><?php echo( $response['count']); ?></span> Supporters </div>
                </div>
                <div class="mdl-card__actions mdl-card--border">
                  <a href="<?php echo $donation_url; ?>" class="mdl-button mdl-js-button mdl-js-ripple-effect">Donate Now</a>
                </div>
              </div>
              <div class="demo-separator mdl-cell--1-col"></div>
           </div>
        </div>
      </main>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.2/material.min.js"></script>
<?php endwhile; ?>
<?php get_footer(); ?>
