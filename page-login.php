	
<?php /* Template Name: Login Page */ ?>

<?php get_header();

	/* Checkbox Default Values */
	$baxel_show_sidebar_static = get_theme_mod( 'baxel_show_sidebar_static', 1 );
	$baxel_show_sidebar_page = get_theme_mod( 'baxel_show_sidebar_page', 1 );
	$baxel_show_page_comments = get_theme_mod( 'baxel_show_page_comments', 1 );
	/* */

	?>

	<div class="main-container<?php echo baxel_apply_layout(); ?>">

	<article <?php post_class( 'clearfix' ); ?>>

			<?php
            if ( have_posts() ) :
                while ( have_posts() ) : the_post();

				$meta_sidebar = get_post_meta( get_the_ID(), 'baxel-sidebar-meta-box-checkbox', true ); ?>

				<?php if ( get_theme_mod( 'baxel_map_page_id' ) == get_the_ID() ) { ?>
                    <div id="googleMap"></div>
                <?php } else {
                    ?><div class="article-featured-image"><?php the_post_thumbnail(); ?></div><?php
                } ?>
								<div class="article-content-outer<?php echo baxel_apply_layout(); ?>">
									
								<?php  if (!is_user_logged_in()):  ?>

								<div class="article-content-inner">
										<h1 class="article-title"><?php the_title(); ?></h1>
										<?php echo do_shortcode('[woocommerce_notices]'); ?>
										<div class="article-pure-content clearfix">
											<!-- <php the_content(); ?> -->
											<div class="woocommerce">
											<?php 
												echo do_shortcode('[woo_login_form]');
											?>
											</div>
										</div>
										<?php
										if ( function_exists( 'WC' ) ) { if ( !is_cart() && !is_checkout() && !is_account_page() ) { get_template_part( 'social-bar' ); } }
										get_template_part( 'pager-bar' );
										?>
									</div>

									<?php else: ?>
										<h1 style="font-size: 18px;">You are in loged in. Please go to <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id')); ?>">My account</a></h1>
										<?php endif; ?>

								</div>

            <?php

            endwhile;

            else :

                echo baxel_nothing_found();

            endif; ?>

        </article>
	</div><!-- /main-container -->

	<!-- sidebar -->

	<?php

	if ( function_exists( 'WC' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {

		// Hide Sidebar on WooCommerce Pages
		
	} else {

		$baxel_s_type = 'home';

		if ( is_front_page() ) {

			if ( $baxel_show_sidebar_static && !$meta_sidebar ) {

				if ( get_theme_mod( 'baxel_enable_sidebar_static', 0 ) ) { $baxel_s_type = 'static'; }
				baxel_insert_sidebar( $baxel_s_type );

			}

		} else {

			if ( $baxel_show_sidebar_page && !$meta_sidebar ) {

				if ( get_theme_mod( 'baxel_enable_sidebar_page', 0 ) ) { $baxel_s_type = 'page'; }
				baxel_insert_sidebar( $baxel_s_type );

			}

		}

	}

	?>

	<!-- /sidebar -->

</div><!-- /site-mid -->

<?php get_footer(); ?>
