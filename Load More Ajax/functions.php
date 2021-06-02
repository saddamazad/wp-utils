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

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );


if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}


add_action( 'cmb2_admin_init', 'cmb2_post_register_metabox' );
function cmb2_post_register_metabox() {
	$prefix = '_cmb2_';
	$prefix2 = '_jobs_';
	$prefix3 = '_media_';

  $cmb2 = new_cmb2_box( array(
    'id'            => $prefix . 'metabox',
    'title'         => esc_html__( 'Post Specification', 'cmb2' ),
    'object_types'  => array( 'post' ), // Post type
  ) );
  
  $cmb2->add_field( array(
	'name'       => esc_html__( 'Post Reading Time', 'cmb2' ),
	'id'         => $prefix . 'rading_time',
	'type'       => 'text'
  ) );
  
  $jobs = new_cmb2_box( array(
    'id'            => $prefix2 . 'metabox',
    'title'         => esc_html__( 'Jobs Specification', 'cmb2' ),
    'object_types'  => array( 'jobs' ), // Post type
  ) );
  
  $jobs->add_field( array(
	'name'       => esc_html__( 'Apply Now URL', 'cmb2' ),
	'id'         => $prefix2 . 'apply_now_link',
	'type'       => 'text'
  ) );
	
  $mediac = new_cmb2_box( array(
    'id'            => $prefix3 . 'metabox',
    'title'         => esc_html__( 'Specification', 'cmb2' ),
    'object_types'  => array( 'media_coverage' ), // Post type
  ) );
  
  $mediac->add_field( array(
	'name'       => esc_html__( 'External URL', 'cmb2' ),
	'id'         => $prefix3 . 'mediac_link',
	'type'       => 'text'
  ) );
  $mediac->add_field( array(
	'name'       => esc_html__( 'Date', 'cmb2' ),
	'id'         => $prefix3 . 'mediac_date',
	'type'       => 'text'
  ) );
  $mediac->add_field( array(
	'name'       => esc_html__( 'Source Name', 'cmb2' ),
	'id'         => $prefix3 . 'source_name',
	'type'       => 'text'
  ) );
}

//add_action( 'cmb2_init', 'pronav_register_user_profile_metabox' );
function pronav_register_user_profile_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_pronav_';

	/**
	 * Metabox for the user profile screen
	 */
	$cmb_user = new_cmb2_box( array(
		'id'               => $prefix . 'edit',
		'title'            => __( 'Extra Info', 'cmb2' ),
		'object_types'     => array( 'user' ), // Tells CMB2 to use user_meta vs post_meta
		'show_names'       => true,
		'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
	) );

	$cmb_user->add_field( array(
		'name'     => __( 'User Profile Photo', 'cmb2' ),
		'desc'     => __( 'Add user profile photo', 'cmb2' ),
		'id'       => $prefix . 'profile_photo',
		'type'     => 'file',
	) );
}


add_shortcode( 'latest_blog_post', 'latest_blog_post_shortcode_init' );
function latest_blog_post_shortcode_init($atts) {
	
	$args_post = array(
		'post_type' => 'post',
		'posts_per_page' => '1', 
		'ignore_sticky_posts' => 1		
	);
				
	$query_post = new WP_Query( $args_post );	
	
	$output = '';
    if ( $query_post->have_posts() ):  
		$output .='<div class="latest_post">';   
			global $post;       
			 while ( $query_post->have_posts() ) : $query_post->the_post(); 
			 	 $output .='<div class="col-sm-6 featued_image_area">';
				 if(has_post_thumbnail()){	
                 $feature_img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
				 $output .='<a href="'.get_permalink().'"><img src="'.$feature_img[0].'" alt="'.get_the_title().'" /></a>';
                 }
				 $output .='</div><div class="col-sm-6">';
				 $output .='<div class="post_meta">';
				 $categories = get_the_category();
					if ( ! empty( $categories ) ) {
						$cat_color = get_term_meta($categories[0]->term_id, '_pn_txn_cat_color', true);
                    	$style_color = "color: #C04E43";
                    	if($cat_color) {
                        	$style_color = "color: ".$cat_color;
                    	}
						$output .= '<span class="cat_name" style="'.$style_color.'">' . esc_html( $categories[0]->name ) . '</span>';
					}
				 if(get_post_meta($post->ID, '_cmb2_rading_time', true)):
				 	$output .='<span class="rading_time">'.get_post_meta($post->ID, '_cmb2_rading_time', true).' minute read</span>';
				 endif;
				 $output .='</div>';
				 $output .='<h2><a href="'.get_permalink().'">'.get_the_title().'</a></h2>';
				 $output .='<p class="entry_content">'.wp_trim_words( get_the_excerpt(), 20).'</p>';
				 $output .='<div class="author_meta">';
				 $avatar_url = '';
				 if (function_exists ( 'mt_profile_img' ) ) {
					//$author_id = $post->post_author;
					$author_id = get_the_author_meta( 'ID' );
					$avatar_url = mt_profile_img( $author_id, array(
						'size' => 'profile_48',
						'attr' => array( 'alt' => get_the_author() ),
						'echo' => false )
					);
				 }
				 if( !$avatar_url ) {
					 $avatar_url = '<img src="/wp-content/uploads/2021/05/author-avatar.png" alt="'.get_the_author().'" />';
				 }
				 $output .='<span>'.$avatar_url.'</span>';
				 //$output .='<span><img src="/wp-content/uploads/2021/05/author-avatar.png" alt="'.get_the_author().'" /></span>';
				 $output .='<h5>'.get_the_author().'</h5>';
				 $output .='</div>';
				 $output .='</div><!--col-sm-6-->';
			 endwhile;
		$output .='</div><!--latest_post-->';
       endif;
	wp_reset_query();
	return $output;					 

}// End



add_shortcode( 'display_blog_grid', 'grid_blog_post_shortcode_init' );
function grid_blog_post_shortcode_init($atts) {
	
	$args_post = array(
		'post_type' => 'post',
		'posts_per_page' => '10', 
		'offset' => 1,
		'ignore_sticky_posts' => 1		
	);
				
	$query_post = new WP_Query( $args_post );	
	
	$output = '';
    if ( $query_post->have_posts() ):  
		$output .='<ul class="post_grid">';   
			global $post;       
			 while ( $query_post->have_posts() ) : $query_post->the_post(); 
			 	 $output .='<li>';
				 $output .='<div class="post_meta">';
				 $categories = get_the_category();
					if ( ! empty( $categories ) ) {
						$output .= '<span class="cat_name">' . esc_html( $categories[0]->name ) . '</span>';
					}
				 if(get_post_meta($post->ID, '_cmb2_rading_time', true)):
				 	$output .='<span class="rading_time">'.get_post_meta($post->ID, '_cmb2_rading_time', true).' minute read</span>';
				 endif;
				 $output .='</div>';
				 $output .='<h3><a href="'.get_permalink().'">'.get_the_title().'</a></h3>';
				 $output .='<p class="entry_content">'.wp_trim_words( get_the_excerpt(), 20).'</p>';
				 $output .='<div class="author_meta">';
				 $output .='<span><img src="/wp-content/uploads/2021/05/author-avatar.png" alt="'.get_the_author().'" /></span>';
				 $output .='<h5>'.get_the_author().'</h5>';
				 $output .='</div>';
				 $output .='</li>';
			 endwhile;
		$output .='</ul><!--post_grid-->';
       endif;
	wp_reset_query();
	return $output;					 

}// End



add_shortcode( 'display_related_post', 'get_related_post_shortcode_init' );
function get_related_post_shortcode_init($atts) {
	
	global $post;
	
	$args_post = array(
		'post_type' => 'post',
		'posts_per_page' => '1', 
		'orderby' => 'rand',
		'post__not_in' => array($post->ID),  
		'ignore_sticky_posts' => 1		
	);
				
	$query_post = new WP_Query( $args_post );	
	
	$output = '';
    if ( $query_post->have_posts() ):  
		$output .='<div class="latest_post">';   
			       
			 while ( $query_post->have_posts() ) : $query_post->the_post(); 
			 	 $output .='<div class="col-sm-6 featued_image_area">';
				 if(has_post_thumbnail()){	
                 $feature_img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
				 $output .='<a href="'.get_permalink().'"><img src="'.$feature_img[0].'" alt="'.get_the_title().'" /></a>';
                 }
				 $output .='</div><div class="col-sm-6">';
				 $output .='<div class="post_meta">';
				 $categories = get_the_category();
					if ( ! empty( $categories ) ) {
						$cat_color = get_term_meta($categories[0]->term_id, '_pn_txn_cat_color', true);
                    	$style_color = "color: #C04E43";
                    	if($cat_color) {
                        	$style_color = "color: ".$cat_color;
                    	}
						$output .= '<span class="cat_name" style="'.$style_color.'">' . esc_html( $categories[0]->name ) . '</span>';
					}
				 if(get_post_meta($post->ID, '_cmb2_rading_time', true)):
				 	$output .='<span class="rading_time">'.get_post_meta($post->ID, '_cmb2_rading_time', true).' minute read</span>';
				 endif;
				 $output .='</div>';
				 $output .='<h3><a href="'.get_permalink().'">'.get_the_title().'</a></h3>';
				 $output .='<p class="entry_content">'.wp_trim_words( get_the_excerpt(), 20).'</p>';
				 $output .='<div class="author_meta">';
				 $avatar_url = '';
				 if (function_exists ( 'mt_profile_img' ) ) {
					//$author_id = $post->post_author;
					$author_id = get_the_author_meta( 'ID' );
					$avatar_url = mt_profile_img( $author_id, array(
						'size' => 'profile_48',
						'attr' => array( 'alt' => get_the_author() ),
						'echo' => false )
					);
				 }
				 if( !$avatar_url ) {
					 $avatar_url = '<img src="/wp-content/uploads/2021/05/author-avatar.png" alt="'.get_the_author().'" />';
				 }
				 $output .='<span>'.$avatar_url.'</span>';
				 $output .='<h5>'.get_the_author().'</h5>';
				 $output .='</div>';
				 $output .='</div><!--col-sm-6-->';
			 endwhile;
		$output .='</div><!--latest_post-->';
       endif;
	wp_reset_query();
	return $output;					 

}// End


add_action( 'init', 'register_custompost_type_init' );
function register_custompost_type_init() {

	/*$label_jobs = array(
		'name' => _x('Jobs', 'job name', 'astra'),
		'singular_name' => _x('Job', 'job type singular name', 'astra'),
		'add_new' => _x('Add New', 'job', 'astra'),
		'all_items' => __('All jobs', 'astra'),
		'add_new_item' => __('Add New job', 'astra'),
		'edit_item' => __('Edit job', 'astra'),
		'new_item' => __('New job', 'astra'),
		'view_item' => __('View job', 'astra'),
		'search_items' => __('Search jobs', 'astra'),
		'not_found' => __('No job Found', 'astra'),
		'not_found_in_trash' => __('No job Found in Trash', 'astra'),
		'parent_item_colon' => ''
	);

	register_post_type('jobs', array('labels' => $label_jobs,

			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'capability_type' => 'post',//or page
            'map_meta_cap' => true,			
			'hierarchical' => false,
			'publicly_queryable' => true,
			'query_var' => true,
			'exclude_from_search' => false,
			'rewrite' => array('slug' => 'jobs'),
			'show_in_nav_menus' => false,
			'supports' => array('title',  'editor', 'page-attributes', 'revisions')
		)
	);*/
	
	$label_press_releases = array(
		'name' => _x('Press Releases', 'Press name', 'astra'),
		'singular_name' => _x('Press Release', 'Press type singular name', 'astra'),
		'add_new' => _x('Add New', 'Press', 'astra'),
		'all_items' => __('All Press releases', 'astra'),
		'add_new_item' => __('Add New Press', 'astra'),
		'edit_item' => __('Edit Press', 'astra'),
		'new_item' => __('New Press', 'astra'),
		'view_item' => __('View Press', 'astra'),
		'search_items' => __('Search Press', 'astra'),
		'not_found' => __('No Press Found', 'astra'),
		'not_found_in_trash' => __('No job Found in Trash', 'astra'),
		'parent_item_colon' => ''
	);

	register_post_type('press_releases', array('labels' => $label_press_releases,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'capability_type' => 'post',//or page
            'map_meta_cap' => true,			
			'hierarchical' => false,
			'publicly_queryable' => true,
			'query_var' => true,
			'exclude_from_search' => false,
			'rewrite' => array('slug' => 'press-release'),
			'show_in_nav_menus' => false,
			'supports' => array('title',  'editor', 'page-attributes', 'revisions')
		)
	);
	
	$label_media_coverage = array(
		'name' => _x('Media Coverages', 'Media Coverage name', 'astra'),
		'singular_name' => _x('Media Coverage', 'Media Coverage type singular name', 'astra'),
		'add_new' => _x('Add New', 'Media Coverage', 'astra'),
		'all_items' => __('All Media Coverage', 'astra'),
		'add_new_item' => __('Add New Media Coverage', 'astra'),
		'edit_item' => __('Edit Media Coverage', 'astra'),
		'new_item' => __('New Media Coverage', 'astra'),
		'view_item' => __('View Media Coverage', 'astra'),
		'search_items' => __('Search Media Coverage', 'astra'),
		'not_found' => __('No Media Coverage Found', 'astra'),
		'not_found_in_trash' => __('No Media Coverage Found in Trash', 'astra'),
		'parent_item_colon' => ''
	);

	register_post_type('media_coverage', array('labels' => $label_media_coverage,

			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'capability_type' => 'post',//or page
            'map_meta_cap' => true,			
			'hierarchical' => false,
			'publicly_queryable' => true,
			'query_var' => true,
			'exclude_from_search' => false,
			'rewrite' => array('slug' => 'media-coverage'),
			'show_in_nav_menus' => false,
			'supports' => array('title', 'page-attributes', 'revisions')
		)
	);
	
	/*$labels_jobs_cat = array(
		'name'                       => _x( 'jobs Category', 'taxonomy general name' ),
		'singular_name'              => _x( 'jobs Category', 'taxonomy singular name' ),
		'search_items'               => __( 'Search jobs Category' ),
		'popular_items'              => __( 'Popular jobs Category' ),
		'all_items'                  => __( 'All jobs Category' ),
		'parent_item'                => __( 'Parent jobs Category' ),
		'parent_item_colon'          => __( 'Parent jobs Category:' ),
		'edit_item'                  => __( 'Edit jobs Category' ),
		'update_item'                => __( 'Update jobs Category' ),
		'add_new_item'               => __( 'Add New jobs Category' ),
		'new_item_name'              => __( 'New jobs Category' ),
		'separate_items_with_commas' => __( 'Separate jobs Category with commas' ),
		'add_or_remove_items'        => __( 'Add or remove jobs Category' ),
		'choose_from_most_used'      => __( 'Choose from the most used job Category' ),
		'not_found'                  => __( 'No job Category found.' ),
		'menu_name'                  => __( 'jobs Category' ),
	);

	$args_jobs_cat = array(
		'hierarchical'          => true,
		'labels'                => $labels_jobs_cat,
		'capabilities' => array (
                'manage_terms' => 'read',
                'edit_terms' => 'read',
                'delete_terms' => 'read',
                'assign_terms' => 'read'
                ),		
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'jobs-cat' ),
	);*/
	
	
	$labels_coverage_cat = array(
		'name'                       => _x( 'Media Coverage Category', 'taxonomy general name' ),
		'singular_name'              => _x( 'Media Coverage Category', 'taxonomy singular name' ),
		'search_items'               => __( 'Search Media Coverage Category' ),
		'popular_items'              => __( 'Popular Media Coverage Category' ),
		'all_items'                  => __( 'All Media Coverage Category' ),
		'parent_item'                => __( 'Parent Media Coverage Category' ),
		'parent_item_colon'          => __( 'Parent Media Coverage Category:' ),
		'edit_item'                  => __( 'Edit Media Coverage Category' ),
		'update_item'                => __( 'Update Media Coverage Category' ),
		'add_new_item'               => __( 'Add New Media Coverage Category' ),
		'new_item_name'              => __( 'New Media Coverage Category' ),
		'separate_items_with_commas' => __( 'Separate Media Coverage Category with commas' ),
		'add_or_remove_items'        => __( 'Add or remove Media Coverage Category' ),
		'choose_from_most_used'      => __( 'Choose from the most used Media Coverage Category' ),
		'not_found'                  => __( 'No Media Coverage Category found.' ),
		'menu_name'                  => __( 'Media Coverage Category' ),
	);

	$args_coverage_cat = array(
		'hierarchical'          => true,
		'labels'                => $labels_coverage_cat,
		'capabilities' => array (
                'manage_terms' => 'read',
                'edit_terms' => 'read',
                'delete_terms' => 'read',
                'assign_terms' => 'read'
                ),		
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'media-coverage-cat' ),
	);

	//register_taxonomy( 'jobs_cat', 'jobs', $args_jobs_cat );
	register_taxonomy( 'mcoverage_cat', 'media_coverage', $args_coverage_cat );	
}


add_action('wp_head', 'get_header_child_theme_script');
function get_header_child_theme_script(){
	?>
		<!--[if lte IE 8]>
		<script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2-legacy.js"></script>
		<![endif]-->
		<script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2.js"></script>
    	<script>
		  jQuery(function($) {
				var Accordion = function(el, multiple) {
						this.el = el || {};
						this.multiple = multiple || false;
		
						var links = this.el.find('.accordion-title');
						links.on('click', {
								el: this.el,
								multiple: this.multiple
						}, this.dropdown)
				}
		
				Accordion.prototype.dropdown = function(e) {
						var $el = e.data.el;
						$this = jQuery(this),
								$next = $this.next();
		
						$next.slideToggle();
					
						$this.parent().toggleClass('open');
		
						if (!e.data.multiple) {
								$el.find('.accordion-content').not($next).slideUp().parent().removeClass('open');
						};
				}
				var accordion = new Accordion(jQuery('.accordion-pro-container'), false);
		});
		
		jQuery(document).on('click', function (event) {
		  if (!jQuery(event.target).closest('#jobs_accordion').length) {
			$this.parent().toggleClass('open');
		  }
		  
		});
			
		jQuery(document).ready(function() {
			if( jQuery(".fancybox-inline").length ) {
				jQuery(".fancybox-inline").find("a").addClass("fancybox-inline");
			}
			if( jQuery(".ast-header-button-1").length ) {
				jQuery(".ast-header-button-1").find("a").addClass("fancybox-inline");
			}
			
			function filterChange(event) {
				event.preventDefault();
				var filter_year = this.value;
				this.setAttribute("data-year", filter_year);
				// Set filter params
				var transition = 'fade';
				var speed = 250;
				var data = this.dataset;

				// Call core Ajax Load More `filter` function.
				// @see https://connekthq.com/plugins/ajax-load-more/docs/public-functions/#filter
				ajaxloadmore.filter(transition, speed, data);
			}
			
			// Get all filter buttons.
			var filter_buttons = document.querySelectorAll('.filter-dd');
			if (filter_buttons) {
				// Loop buttons.
				[].forEach.call(filter_buttons, function (button) {
					// Add button click event.
					button.addEventListener('change', filterChange);
				});
			}
		});
		
		/*window.almComplete = function(alm){
			console.log(alm);
		};*/
		</script>

		<style type="text/css">
			.author_meta .size-profile_48 {
				border-radius: 50%;
			}
		</style>
    <?php
}


//add_shortcode('display_jobs', 'get_jobs_shortcode_init');
function get_jobs_shortcode_init($atts){
	
	ob_start();
	
		$terms = get_terms( 'jobs_cat', array("orderby" => "name",));
		global $post;
		$counter = 0;
         
    echo '<div id="jobs_accordion" class="accordion-pro-container">';
		foreach ( $terms as $term ) {
			$counter ++;
			if ($counter == 1){
				echo '<div class="content-entry open">';
			} else {
				echo '<div class="content-entry">';
			}
			
			echo '<h4 class="accordion-title">'.$term->name.'<span class="number_of_post">('.$term->count.')</span> <img src="/wp-content/uploads/2021/05/ic-chevron.png" alt="" /></h4>';
			echo '<div class="accordion-content">';
				$loop = new WP_Query( array(
				'post_type' => 'jobs',
				'posts_per_page' => '-1', 				
				'tax_query' => array(
					array(
						'taxonomy' => 'jobs_cat',
						'field'    => 'slug',
						'terms'    => $term->slug,
					)), 
			    )); 
				
				  echo '<ul class="job_lists">';
					while ( $loop->have_posts() ) : $loop->the_post();
						echo '<li>';
							echo '<div class="jobs_content_area">';
								echo '<h5>'.get_the_title().'</h5>';
								echo '<p>'.wp_trim_words( get_the_excerpt(), 20).'</p>';
							echo '</div>';
							echo '<div class="jobs_button"><a href="'.get_post_meta( $post->ID, '_jobs_apply_now_link', true).'">Apply Now</a></div>';
		
						echo '</li>'; 
					endwhile;
				  echo "</ul>";
			echo '</div><!--.accordion-content-->';
			echo '</div><!--.content-entry-->';
		}	
	echo '</div><!--#jobs_accordion-->';   
	 
 
	$output = ob_get_clean();
	return $output;
}

if ( !file_exists(  dirname(__FILE__) .'/cmb2-taxonomy/init.php' ) ) {
	exit;
}

require_once  dirname(__FILE__) .'/cmb2-taxonomy/init.php';

add_filter('cmb2-taxonomy_meta_boxes', 'cmb2_taxonomy_pronav_metaboxes');
function cmb2_taxonomy_pronav_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_pn_txn_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$meta_boxes['test_metabox'] = array(
		'id'            => 'test_metabox',
		'title'         => __( 'Taxonomy Metabox', 'cmb2' ),
		'object_types'  => array( 'category', ), // Taxonomy
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		'fields'        => array(
			array(
				'name'       => __( 'Category Color', 'cmb2' ),
				'desc'       => __( 'field description (optional)', 'cmb2' ),
				'id'         => $prefix . 'cat_color',
				'type'       => 'colorpicker',
				'default' => '#ffffff'
			),
		),
	);

	return $meta_boxes;
}

function load_statuspage_scripts() {
	?>
	<div style="display: none;">
		<div id="hubspot-popup">
			<div class="popup_header">
			<h4>Request a Sage demo</h4> 
			</div>
			<script>
				hbspt.forms.create({
					region: "na1",
					portalId: "4511975",
					formId: "70c69e79-ffec-43ec-bc25-da5314bed489"
				});
			</script>
		</div>
	</div>
	<script src="https://cdn.statuspage.io/se-v2.js"></script>
	<script>
		var dotClass = "statuspage-dot";
		var descriptionClass = "statuspage-description";
		var statuspageID = "73nj5jhb3tk4";

		var container = document.querySelector(".js-statuspage a");
		var dot = document.createElement("span");
		dot.classList.add(dotClass);
		var description = document.createElement("span");
		description.classList.add(descriptionClass)
		var sp = new StatusPage.page({ page: statuspageID });

		container.innerHTML = "";
		container.appendChild(dot);
		container.appendChild(description);

		sp.summary({
			success: function(data) {
				description.textContent = data.status.description;
				dot.classList.add(data.status.indicator);
			}
		});
	</script>
	<?php
}
add_action('wp_footer', 'load_statuspage_scripts');

add_action( 'init', 'register_team_custompost_type' );
function register_team_custompost_type() {

	$labels = array(
		'name' => _x('Team', 'Team name', 'astra'),
		'singular_name' => _x('Team', 'Team type singular name', 'astra'),
		'add_new' => _x('Add New', 'Team', 'astra'),
		'all_items' => __('All Team', 'astra'),
		'add_new_item' => __('Add New Team', 'astra'),
		'edit_item' => __('Edit Team', 'astra'),
		'new_item' => __('New Team', 'astra'),
		'view_item' => __('View Team', 'astra'),
		'search_items' => __('Search Team', 'astra'),
		'not_found' => __('No Team Found', 'astra'),
		'not_found_in_trash' => __('No Team Found in Trash', 'astra'),
		'parent_item_colon' => ''
	);

	register_post_type('team', array('labels' => $labels,

			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'capability_type' => 'post',//or page
            'map_meta_cap' => true,			
			'hierarchical' => false,
			'publicly_queryable' => true,
			'query_var' => true,
			'exclude_from_search' => false,
			'rewrite' => array('slug' => 'team'),
			'show_in_nav_menus' => false,
			'supports' => array('title', 'thumbnail', 'page-attributes', 'revisions')
		)
	);
}



add_shortcode( 'display_team_grid', 'team_grid_shortcode_init' );
function team_grid_shortcode_init() {	
	
	$args_post = array(
		'post_type' => 'team',
		'posts_per_page' => '-1', 
        'order' => 'ASC',
        'orderby' => 'title',
		'ignore_sticky_posts' => 1		
	);
				
	$query_post = new WP_Query( $args_post );	
	
	$output = '';
    if ( $query_post->have_posts() ):  
		$output .='<ul class="team_grid_list">';   
			global $post;       
			 while ( $query_post->have_posts() ) : $query_post->the_post(); 
			 	 $output .='<li>';
				 if(has_post_thumbnail()){	
                 $feature_img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium');
				 $output .='<img src="'.$feature_img[0].'" alt="'.get_the_title().'" />';
                 }
				 $output .='<h5>'.get_the_title().'</h5>';				 
				 $output .='</li>';
			 endwhile;
		$output .='</ul><!--team_grid_list-->';
       endif;
	wp_reset_query();
	return $output;					 

}// End

// Load more ajax filter dropdown
function filter_by_years_callback($atts) {
	extract(shortcode_atts(array(
		'years' => '',
		'id' => '',
	), $atts));
	
	$output = '';
	$select_name = str_replace("-", "_", $id);
	$dataset = '';
	if($id == "pr-filter") {
		$dataset .= 'data-post-type="press_releases" data-posts-per-page="5" data-target="filter-pr" data-year=""';
	} elseif($id == "mc-filter") {
		$dataset .= 'data-post-type="media_coverage" data-posts-per-page="5" data-target="filter-mc" data-year=""';
	} else {
	}
	$output .= '<select name="'.$select_name.'" id="'.$id.'" class="filter-dd" '.$dataset.'>';
	if( $years ) {
		$years_array = explode(",", $years);
		for($i=0; $i < count($years_array); $i++) {
			$output .= '<option value="'.$years_array[$i].'">'.$years_array[$i].'</option>';
		}
	} else {
		$year = date("Y");
		$depth = 3;
		for($i=0; $i < $depth; $i++) {
			$output .= '<option value="'.($year-$i).'">'.($year-$i).'</option>';
		}
	}
	$output .= '</select>';
	/*
	https://connekthq.com/plugins/ajax-load-more/docs/parameters/
	https://connekthq.com/plugins/ajax-load-more/examples/filtering/
	https://connekthq.com/plugins/ajax-load-more/examples/filtering/multiple-filters/
	https://connekthq.com/plugins/ajax-load-more/examples/
	*/
	return $output;
}
add_shortcode( 'filter_by_year', 'filter_by_years_callback' );