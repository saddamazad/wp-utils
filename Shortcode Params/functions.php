<?php
/**
 * astra-child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package astra-child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {
	wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );
	wp_enqueue_style( 'slick-style', get_stylesheet_directory_uri() . '/slick.css' );
	wp_enqueue_style( 'slick-theme-style', get_stylesheet_directory_uri() . '/slick-theme.css' );
	wp_enqueue_script( 'slick-script', get_stylesheet_directory_uri() .  '/slick.min.js', array(), '1.8.1', true );
}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );


add_image_size( 'blog_thumb', 768, 400, true );

add_shortcode( 'display_blogs', 'get_blog_post_shortcode_init' );
function get_blog_post_shortcode_init() {	
	
	$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	
	$args_post = array(
		'post_type' => 'post',
		'posts_per_page' => '9', 
		'paged' => $paged,
		'ignore_sticky_posts' => 1		
	);
				
	$query_post = new WP_Query( $args_post );	
	
	$output = '';
	$big = 999999999; 
	
    if ( $query_post->have_posts() ):  
		$output .='<div class="section_blog_post clearfix">';  
			global $post;       
			 while ( $query_post->have_posts() ) : $query_post->the_post(); 
			 	 $output .='<article>';
				 if(has_post_thumbnail()){	
                 $feature_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'blog_thumb');
				 $output .='<a class="post_thumb" href="'.get_permalink().'"><img src="'.$feature_thumb[0].'" alt="'.get_the_title().'" /></a>';
                  }
				  $output .='<div class="post_description">';
				  $categories = get_the_category();
					if ( ! empty( $categories ) ) {
						$output .='<div class="cat_name">';
						foreach( $categories as $category ) {
							$output .= '<span>' . esc_html( $category->name ) . '</span>';
						}
						$output .='</div>';
					}
				 $output .='<p class="post_date">'.get_the_date('d F, Y', $post->ID).'</p>';
				
				 $output .='<h4>'.get_the_title().'</h4>';
				 $output .='<p class="author_name">'.get_the_author().'</p>';				 
				 $output .='<p class="post_excerpt">'.wp_trim_words( get_the_excerpt(), 20 ).'</p>';
				 $output .='<a class="readmore" href="'.get_permalink().'">READ MORE <i  class="fas fa-long-arrow-alt-right"></i></a>';
				 $output .='</div>';
				 $output .='</article>';
			 endwhile;
		$output .='</div><!--section_blog_post-->';
		$output .='<div class="pagination_nav">';
		$output .= paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'prev_text'          => __('<i class="fa fa-angle-left" aria-hidden="true"></i>'),
			'next_text'          => __('<i class="fa fa-angle-right" aria-hidden="true"></i>'),
			'total' => $query_post->max_num_pages
		) );
		$output .='</div>';
       endif;
	wp_reset_query();
	return $output;				 

}// End


function prefix_nav_description( $item_output, $item, $depth, $args ) {
    if ( !empty( $item->description ) ) {
        $item_output = str_replace( $args->link_after . '</a>', '<p class="menu-item-description">' . $item->description . '</p>' . $args->link_after . '</a>', $item_output );
    }
 
    return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'prefix_nav_description', 10, 4 );

function load_custom_scripts() {
	?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('.jet-view-more').on('click', function() {
				jQuery(this).parents('.elementor-element').prev('.elementor-element').show();
			});
			
			jQuery('.testimonials-slider').slick({
				dots: false,
				slidesToShow: 1,
				slidesToScroll: 1,
				//adaptiveHeight: true
			});
			jQuery('#paypal-payment').on('click', function() {
				jQuery(".paymentform-row.stripe").hide();
				jQuery(".paymentform-row.paypal").show();
			});
			jQuery('#cc-payment').on('click', function() {
				jQuery(".paymentform-row.paypal").hide();
				jQuery(".paymentform-row.stripe").show();
			});
			if( jQuery(".give-donation-amount").length ) {
				jQuery(".give-donation-amount label").text("Amount:");
			}
			if( jQuery("#give-final-total-wrap").length ) {
				jQuery(".give-donation-total-label").text("Total:");
			}
		});
	</script>
	<?php
}
add_action('wp_head', 'load_custom_scripts');

add_shortcode( 'get_testimonials', 'get_testimonials_slider' );
function get_testimonials_slider($atts) {
	extract(shortcode_atts(array(
		'ids' => '',
	), $atts));
	
	$args = array(
		'post_type' => 'testimonials',
		'posts_per_page' => 5, 
        'order' => 'ASC',
        'orderby' => 'menu_order'
	);
	
	if( $ids ) {
		$tmnl_array = explode(",", $ids);
		$args['post__in'] = $tmnl_array;
	}
	
	$query_post = new WP_Query( $args );
	
	if( $query_post->have_posts() ) {
		ob_start();
	?>
	<div class="testimonials-wrap testimonials-slider">
	<?php
		while( $query_post->have_posts() ) {
			$query_post->the_post();
			?>
			<div class="testimonial-slide">
				<div class="testimonial-item">
					<div class="tmnl-left-photo">
						<div class="tmnl-left-content">
							<img src="/wp-content/uploads/2021/03/quotes.png" alt="Quotes" />
							<h3>
								<?php echo get_post_meta(get_the_ID(), 'text_on_photo', true); ?>
							</h3>
						</div>
						<?php
							if( has_post_thumbnail() ) {	
								$feature_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
							}
						?>
						<img class="tmnl_feature_img" src="<?php echo $feature_thumb[0]; ?>" alt="<?php echo get_the_title(); ?>" />
						<!-- <div class="arrows-sync">
							<a class="tmnl-prev" href="#"><img src="/wp-content/uploads/2021/03/test-left-arrow.png" alt="Arrow" /></a>
							<a class="tmnl-next" href="#"><img src="/wp-content/uploads/2021/03/test-right-arrow.png" alt="Arrow" /></a>
						</div> -->
					</div>
					<div class="tmnl-content">
						<img src="/wp-content/uploads/2021/03/5stars.png" alt="Star" />
						<div class="testimonial-text">
							<?php echo get_the_content(); ?>
						</div>
						<div class="client-info">
							<img src="<?php echo wp_get_attachment_url(get_post_meta(get_the_ID(), 'client_photo', true)); ?>" alt="<?php echo get_the_title(); ?>" />
							<div class="author-dtls">
								<h4 class="client-name"><?php echo get_the_title(); ?></h4>
								<?php echo get_post_meta(get_the_ID(), 'designation', true); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	?>
	</div>
	<?php
	}
	wp_reset_postdata();
	$content = ob_get_clean();
	return $content;
}

add_shortcode( 'get_arc_resources', 'get_blog_resources' );
function get_blog_resources($atts) {
	extract(shortcode_atts(array(
		'cat' => '',
	), $atts));
	
	$args = array(
		'post_type' => 'resource',
		'posts_per_page' => -1, 
        'order' => 'ASC',
        'orderby' => 'menu_order'
	);
	
	if( $cat ) {
		//$tmnl_array = explode(",", $ids);
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'resources_cat',
				'field'    => 'slug',
				'terms'    => $cat,
			),
		);
	}
	
	$query_post = new WP_Query( $args );
	
	if( $query_post->have_posts() ) {
		ob_start();
	?>
	<div class="resources-wrap">
	<?php
		while( $query_post->have_posts() ) {
			$query_post->the_post();
			?>
			<div class="resource-item">
				<div class="resource-photo">
				<?php
				if( has_post_thumbnail() ) {	
					$feature_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
					echo '<a href="'.get_post_meta(get_the_ID(), 'res_youtube_url', true).'" class="fancybox-youtube"><img class="resource_img" src="'.$feature_thumb[0].'" alt="'.get_the_title().'" /></a>';
				}
				?>
				</div>
				<div class="resource-content">
					<h3 class="resource-title"><?php echo get_the_title(); ?></h3>
					<div class="resource-text">
						<?php echo get_the_content(); ?>
					</div>
				</div>
			</div>
			<?php
		}
	?>
	</div>
	<?php
	}
	wp_reset_postdata();
	$content = ob_get_clean();
	return $content;
}

add_shortcode( 'get_arc_events', 'get_arclg_events' );
function get_arclg_events($atts) {
	extract(shortcode_atts(array(
		'type' => '',
	), $atts));
	
	$args = array(
		'post_type' => 'arclg-events',
		'posts_per_page' => -1
	);

	$date = date("Y-m-d");
	if( $type && ($type == "past") ) {
		$args['meta_query'] = array(
								array(
									'key'     => 'event_date',
									'value'   => $date,
									'compare' => '<',
									'type' => 'DATE'
								),
							);
	} elseif( $type && ($type == "upcoming") ) {
		$args['meta_query'] = array(
								array(
									'key'     => 'event_date',
									'value'   => $date,
									'compare' => '>=',
									'type' => 'DATE'
								),
							);
	}
	
	$query_post = new WP_Query( $args );
	
	if( $query_post->have_posts() ) {
		ob_start();
	?>
	<div class="events-wrap">
	<?php
		while( $query_post->have_posts() ) {
			$query_post->the_post();
			?>
			<div class="event-item elementor-widget-icon-box elementor-position-left elementor-vertical-align-top">
				<div class="elementor-icon-box-wrapper">
					<div class="elementor-icon-box-icon">
						<span class="elementor-icon elementor-animation-">
							<i aria-hidden="true" class="fas fa-square-full"></i>
						</span>
					</div>
					<div class="elementor-icon-box-content">
						<?php
						if( get_post_meta(get_the_ID(), 'event_url', true) ) {
							$e_title = '<a href="'.get_post_meta(get_the_ID(), 'event_url', true).'" target="_blank"><h3 class="event-title">'.get_the_title().'</h3></a>';
						} else {
							$e_title = '<h3 class="event-title">'.get_the_title().'</h3>';
						}
			
						echo $e_title;
						$e_date = get_post_meta(get_the_ID(), 'event_date', true);
						?>
						<div class="elementor-icon-box-description">
							<div class="event-description">
								<?php echo get_the_content(); ?>								
							</div>
							<strong><?php echo date('F d, Y', strtotime($e_date)); ?></strong>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	?>
	</div>
	<?php
	}
	wp_reset_postdata();
	$content = ob_get_clean();
	return $content;
}