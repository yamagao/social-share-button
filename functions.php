<?php

/*-----------------------------------------------------------------------------------------------------//	
	Initiate the localization of the theme domain		       	     	 
-------------------------------------------------------------------------------------------------------*/

load_theme_textdomain( 'organicthemes', TEMPLATEPATH.'/languages' );

/*-----------------------------------------------------------------------------------------------------//	
	Category ID to a Name		       	     	 
-------------------------------------------------------------------------------------------------------*/

function cat_id_to_name($id) {
	foreach((array)(get_categories()) as $category) {
    	if ($id == $category->cat_ID) { return $category->cat_name; break; }
	}
}

/*-----------------------------------------------------------------------------------------------------//	
	Post Meta Options		       	     	 
-------------------------------------------------------------------------------------------------------*/

require_once('function-meta.php');

$ot_metaboxes = array(

		"image" => array (
			"name"	=> "post_bg",
			"label" => "Background Image",
            "type" 	=> "upload",
			"desc"  => "Automatically resized/enlarged, but ideally 1200px x 600px to avoid pixelation."
		),
		"checkbox" => array (
			"name"	=> "repeat_bg",
			"label"	=> "Repeat Background",
			"std" 	=> "1",
			"type" 	=> "checkbox"
		),
		"background_color" => array (
			"name"	=> "bg_color",
			"label"	=> "Background Color",
			"std" 	=> "#FFFFFF",
			"type" 	=> "color",
			"desc" 	=> "Optionally choose a color or enter hex code to change the post background color."
		),

	);

update_option('ot_custom_template', $ot_metaboxes);

/*-----------------------------------------------------------------------------------------------------//	
	Custom Excerpt Length		       	     	 
-------------------------------------------------------------------------------------------------------*/

function custom_excerpt_length( $length ) {
	return 52;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

function new_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');

/*-----------------------------------------------------------------------------------------------------//	
	404 Pagination Fix For Home Page		       	     	 
-------------------------------------------------------------------------------------------------------*/

function my_post_queries( $query ) {
	// Not an admin page and it is the main query
	if (!is_admin() && $query->is_main_query()){
		if(is_home() ){
			$query->set('posts_per_page', 1);
		}
	}
}

add_action( 'pre_get_posts', 'my_post_queries' );

/*-----------------------------------------------------------------------------------------------------//	
	Mobile Dropdown Menu		       	     	 
-------------------------------------------------------------------------------------------------------*/

class Walker_Nav_Menu_Dropdown extends Walker_Nav_Menu {
 
	 function start_lvl(&$output, $depth) {
		$indent = str_repeat("\t", $depth);
		$output .= "";
	}
 
 
	function end_lvl(&$output, $depth) {
		$indent = str_repeat("\t", $depth);
		$output .= "";
	}
 
	 function start_el(&$output, $item, $depth, $args) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
 
		$class_names = $value = '';
 
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
 
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';
 
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
 
		//check if current page is selected page and add selected value to select element  
		  $selc = '';
		  $curr_class = 'current-menu-item';
		  $is_current = strpos($class_names, $curr_class);
		  if($is_current === false){
	 		  $selc = "";
		  }else{
	 		  $selc = "selected ";
		  }
 
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
 
		$sel_val =  ' value="'   . esc_attr( $item->url        ) .'"';
 
		//check if the menu is a submenu
		switch ($depth){
		  case 0:
			   $dp = "";
			   break;
		  case 1:
			   $dp = "-";
			   break;
		  case 2:
			   $dp = "--";
			   break;
		  case 3:
			   $dp = "---";
			   break;
		  case 4:
			   $dp = "----";
			   break;
		  default:
			   $dp = "";
		}
 
 
		$output .= $indent . '<option'. $sel_val . $id . $value . $class_names . $selc . '>'.$dp;
 
		$item_output = $args->before;
		//$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		//$item_output .= '</a>';
		$item_output .= $args->after;
 
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
 
	function end_el(&$output, $item, $depth) {
		$output .= "</option>\n";
	}
 
}

/*-----------------------------------------------------------------------------------------------------//	
	Register Scripts		       	     	 
-------------------------------------------------------------------------------------------------------*/

if( !function_exists('ot_enqueue_scripts') ) {
	function ot_enqueue_scripts() {
		
		// Enqueue jQuery First
		wp_enqueue_script('jquery');
		
		// Resgister Scripts
		wp_register_script('modernizr', get_template_directory_uri() . '/js/modernizr.custom.js');
		wp_register_script('custom', get_template_directory_uri() . '/js/jquery.custom.js');
		wp_register_script('superfish', get_template_directory_uri() . '/js/superfish.js', 'jquery', '1.0', true);
		wp_register_script('hover', get_template_directory_uri() . '/js/hoverIntent.js', 'jquery', '1.0', true);
		wp_register_script('flexslider', get_template_directory_uri() . '/js/jquery.flexslider.js', 'jquery', '1.6.2', true);
		wp_register_script('fitvids', get_template_directory_uri() . '/js/jquery.fitVids.js', 'jquery', '', true);
		wp_register_script('modal', get_template_directory_uri() . '/js/jquery.modal.min.js', 'jquery', '', true);
		wp_register_script('lightbox', get_template_directory_uri() . '/js/jquery.prettyPhoto.js', 'jquery', '', true);
		wp_register_script('retina', get_template_directory_uri() . '/js/retina.js');
	
		// Enqueue Scripts
		wp_enqueue_script('modernizr');
		wp_enqueue_script('custom');
		wp_enqueue_script('superfish');
		wp_enqueue_script('hover');
		wp_enqueue_script('flexslider');
		wp_enqueue_script('fitvids');
		wp_enqueue_script('retina');
		wp_enqueue_script('modal');
		wp_enqueue_script('lightbox');
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('jquery-ui-accordion');
		wp_enqueue_script('jquery-ui-dialog');
	
		// load single scripts only on single pages
	    if( is_singular() ) wp_enqueue_script( 'comment-reply' ); // loads the javascript required for threaded comments 
	}
	add_action('wp_enqueue_scripts', 'ot_enqueue_scripts');
}

add_action('init', 'ilc_farbtastic_script');
function ilc_farbtastic_script() {
  wp_enqueue_style( 'farbtastic' );
  wp_enqueue_script( 'farbtastic' );
}

/*-----------------------------------------------------------------------------------------------------//	
	Register Sidebars		       	     	 
-------------------------------------------------------------------------------------------------------*/

if ( function_exists('register_sidebars') )
	register_sidebar(array(
		'name'=> __( "Sidebar", 'organicthemes' ),
		'id' => 'sidebar-right',
		'before_widget'=>'<div id="%1$s" class="widget %2$s">',
		'after_widget'=>'</div>',
		'before_title'=>'<h6 class="title">',
		'after_title'=>'</h6>'
	));
	register_sidebar(array(
		'name'=> __( "Sidebar Blog", 'organicthemes' ),
		'id' => 'sidebar-blog',
		'before_widget'=>'<div id="%1$s" class="widget %2$s">',
		'after_widget'=>'</div>',
		'before_title'=>'<h6 class="title">',
		'after_title'=>'</h6>'
	));

/*-----------------------------------------------------------------------------------------------------//	
	Options Framework		       	     	 
-------------------------------------------------------------------------------------------------------*/

if ( !function_exists( 'of_get_option' ) ) {
	function of_get_option($name, $default = 'false') {
		
		$optionsframework_settings = get_option('optionsframework');
		
		// Gets the unique option id
		$option_name = $option_name = $optionsframework_settings['id'];
		
		if ( get_option($option_name) ) {
			$options = get_option($option_name);
		}
			
		if ( !empty($options[$name]) ) {
			return $options[$name];
		} else {
			return $default;
		}
	}	
}

if ( !function_exists( 'optionsframework_add_page' ) && current_user_can('edit_theme_options') ) {
	function options_default() {
		add_theme_page(__("Theme Options",'organicthemes'), __("Theme Options",'organicthemes'), 'edit_theme_options', 'options-framework','optionsframework_page_notice');
	}
	add_action('admin_menu', 'options_default');
}

// Displays a notice on the theme options page if the Options Framework plugin is not installed
if ( !function_exists( 'optionsframework_page_notice' ) ) {
	add_thickbox(); // Required for the plugin install dialog.

	function optionsframework_page_notice() { ?>
	
		<div class="wrap">
		<?php screen_icon( 'themes' ); ?>
		<h2><?php _e("Theme Options", 'organicthemes'); ?></h2>
        <p><b><?php _e("This theme requires the Options Framework plugin installed and activated to manage your theme options.", 'organicthemes'); ?> <a href="<?php echo admin_url('plugin-install.php?tab=plugin-information&plugin=options-framework&TB_iframe=true&width=640&height=517'); ?>" class="thickbox onclick"><?php _e("Install Now", 'organicthemes'); ?></a></b></p>
		</div>
		<?php
	}
}

/*-----------------------------------------------------------------------------------------------------//	
	Comments function		       	     	 
-------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'organicthemes_comment' ) ) :
function organicthemes_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'organicthemes' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'organicthemes' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-avatar">
				<div class="vcard">
					<?php
						$avatar_size = 136;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 136;

						echo get_avatar( $comment, $avatar_size );
					?>
				</div><!-- .comment-author .vcard -->
			</footer>

			<div class="comment-content">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'organicthemes' ); ?></em>
					<br />
				<?php endif; ?>
				<?php
				/* translators: 1: comment author, 2: date and time */
				printf( __( '<span class="comment-meta">%2$s &nbsp;by&nbsp; %1$s </span>', 'organicthemes' ),
					sprintf( '<span class="comment-author">%s</span>', get_comment_author_link() ),
					sprintf( '<a class="comment-time" href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s', 'organicthemes' ), get_comment_date(), get_comment_time() )
					)
				);
				?>
				<?php comment_text(); ?>
				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'organicthemes' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div><!-- .reply -->
				<?php edit_comment_link( __( 'Edit', 'organicthemes' ), '<span class="edit-link">', '</span>' ); ?>
			</div>

		</article><!-- #comment-## -->

	<?php
	break;
	endswitch;
}
endif; // ends check for organicthemes_comment()

/*-----------------------------------------------------------------------------------------------------//	
	WooCommerce Functions		       	     	 
-------------------------------------------------------------------------------------------------------*/

// Remove WC sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// woocommerce content wrappers
function mytheme_prepare_woocommerce_wrappers(){
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
    add_action( 'woocommerce_before_main_content', 'mytheme_open_woocommerce_content_wrappers', 10 );
    add_action( 'woocommerce_after_main_content', 'mytheme_close_woocommerce_content_wrappers', 10 );
}
add_action( 'wp_head', 'mytheme_prepare_woocommerce_wrappers' );

function mytheme_open_woocommerce_content_wrappers() {
	?>
	<div id="content">
		<div class="row">
			<div class="eight columns">
				<div class="type-page content holder" id="woocommerce-page">
					<div class="article">
    <?php
}

function mytheme_close_woocommerce_content_wrappers() {
	?>
		    		</div> <!-- /article -->
	    		</div> <!-- /type-page -->
	    	</div> <!-- /columns -->
	 
	        <div class="four columns">
	            <?php get_sidebar(); ?> 
	        </div>
	        
	 	</div> <!-- /row -->
	</div> <!-- /content -->
    <?php
}

// Add the WC sidebar in the right place
add_action( 'woo_main_after', 'woocommerce_get_sidebar', 10 );

// woocommerce thumbnail image sizes
global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action( 'init', 'woo_install_theme', 1 );
function woo_install_theme() {
 
update_option( 'woocommerce_thumbnail_image_width', '200' );
update_option( 'woocommerce_thumbnail_image_height', '200' );
update_option( 'woocommerce_single_image_width', '640' );
update_option( 'woocommerce_single_image_height', '640' );
update_option( 'woocommerce_catalog_image_width', '200' );
update_option( 'woocommerce_catalog_image_height', '200' );
}

// woocommerce default product columns
function loop_columns() {
    return 3;
}
add_filter('loop_shop_columns', 'loop_columns');

// woocommerce remove related products
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

/*-----------------------------------------------------------------------------------------------------//	
	Press Trends		       	     	 
-------------------------------------------------------------------------------------------------------*/

// Start of Presstrends Magic
if(of_get_option('enable_presstrends') == '1') {

/**
* PressTrends Theme API
*/
function presstrends_theme() {

	// PressTrends Account API Key
	$api_key = 'o5byp75idn9s80nvvahx361kb4m55t5wz9yj';
	$auth = '6ymnh1qslg4rtr62simlhn9ds4xb3k2am';

	// Start of Metrics
	global $wpdb;
	$data = get_transient( 'presstrends_theme_cache_data' );
	if ( !$data || $data == '' ) {
		$api_base = 'http://api.presstrends.io/index.php/api/sites/add/auth/';
		$url      = $api_base . $auth . '/api/' . $api_key . '/';

		$count_posts    = wp_count_posts();
		$count_pages    = wp_count_posts( 'page' );
		$comments_count = wp_count_comments();

		// wp_get_theme was introduced in 3.4, for compatibility with older versions.
		if ( function_exists( 'wp_get_theme' ) ) {
			$theme_data    = wp_get_theme();
			$theme_name    = urlencode( $theme_data->Name );
			$theme_version = $theme_data->Version;
		} else {
			$theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
			$theme_name = $theme_data['Name'];
			$theme_versino = $theme_data['Version'];
		}

		$plugin_name = '&';
		foreach ( get_plugins() as $plugin_info ) {
			$plugin_name .= $plugin_info['Name'] . '&';
		}
		$posts_with_comments = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type='post' AND comment_count > 0" );
		$data                = array(
			'url'             => stripslashes( str_replace( array( 'http://', '/', ':' ), '', site_url() ) ),
			'posts'           => $count_posts->publish,
			'pages'           => $count_pages->publish,
			'comments'        => $comments_count->total_comments,
			'approved'        => $comments_count->approved,
			'spam'            => $comments_count->spam,
			'pingbacks'       => $wpdb->get_var( "SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_type = 'pingback'" ),
			'post_conversion' => ( $count_posts->publish > 0 && $posts_with_comments > 0 ) ? number_format( ( $posts_with_comments / $count_posts->publish ) * 100, 0, '.', '' ) : 0,
			'theme_version'   => $theme_version,
			'theme_name'      => $theme_name,
			'site_name'       => str_replace( ' ', '', get_bloginfo( 'name' ) ),
			'plugins'         => count( get_option( 'active_plugins' ) ),
			'plugin'          => urlencode( $plugin_name ),
			'wpversion'       => get_bloginfo( 'version' ),
			'api_version'	  => '2.4',
		);

		foreach ( $data as $k => $v ) {
			$url .= $k . '/' . $v . '/';
		}
		wp_remote_get( $url );
		set_transient( 'presstrends_theme_cache_data', $data, 60 * 60 * 24 );
	}
}
// PressTrends WordPress Action
add_action('admin_init', 'presstrends_theme');		

} else { 
}

/*-----------------------------------------------------------------------------------*/
/*	Custom Page Links
/*-----------------------------------------------------------------------------------*/

function wp_link_pages_args_prevnext_add($args) {
    global $page, $numpages, $more, $pagenow;

    if (!$args['next_or_number'] == 'next_and_number') 
        return $args; 

    $args['next_or_number'] = 'number'; // Keep numbering for the main part
    if (!$more)
        return $args;

    if($page-1) // There is a previous page
        $args['before'] .= _wp_link_page($page-1)
            . $args['link_before']. $args['previouspagelink'] . $args['link_after'] . '</a>';

    if ($page<$numpages) // There is a next page
        $args['after'] = _wp_link_page($page+1)
            . $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a>'
            . $args['after'];

    return $args;
}

add_filter('wp_link_pages_args', 'wp_link_pages_args_prevnext_add');

/*-----------------------------------------------------------------------------------------------------//	
	Page Numbering Pagination		       	     	 
-------------------------------------------------------------------------------------------------------*/

function number_paginate($args = null) {
	$defaults = array(
		'page' => null, 'pages' => null, 
		'range' => 5, 'gap' => 5, 'anchor' => 1,
		'before' => '<div class="number-paginate">', 'after' => '</div>',
		'title' => '',
		'nextpage' => __('<i class="icon-chevron-right"></i>'), 'previouspage' => __('<i class="icon-chevron-left"></i>'),
		'echo' => 1
	);

	$r = wp_parse_args($args, $defaults);
	extract($r, EXTR_SKIP);

	if (!$page && !$pages) {
		global $wp_query;
		$page = get_query_var('paged');
		$page = !empty($page) ? intval($page) : 1;
		$posts_per_page = intval(get_query_var('posts_per_page'));
		$pages = intval(ceil($wp_query->found_posts / $posts_per_page));
	}	

	$output = "";

	if ($pages > 1) {	
		$output .= "$before<span class='number-title'>$title</span>";
		$ellipsis = "<span class='number-gap'>...</span>";
		if ($page > 1 && !empty($previouspage)) {
			$output .= "<a href='" . get_pagenum_link($page - 1) . "' class='number-prev'>$previouspage</a>";
		}

		$min_links = $range * 2 + 1;
		$block_min = min($page - $range, $pages - $min_links);
		$block_high = max($page + $range, $min_links);
		$left_gap = (($block_min - $anchor - $gap) > 0) ? true : false;
		$right_gap = (($block_high + $anchor + $gap) < $pages) ? true : false;

		if ($left_gap && !$right_gap) {
			$output .= sprintf('%s%s%s', 
				number_paginate_loop(1, $anchor), 
				$ellipsis, 
				number_paginate_loop($block_min, $pages, $page)
			);
		}

		else if ($left_gap && $right_gap) {
			$output .= sprintf('%s%s%s%s%s', 
				number_paginate_loop(1, $anchor), 
				$ellipsis, 
				number_paginate_loop($block_min, $block_high, $page), 
				$ellipsis, 
				number_paginate_loop(($pages - $anchor + 1), $pages)
			);
		}

		else if ($right_gap && !$left_gap) {
			$output .= sprintf('%s%s%s', 
				number_paginate_loop(1, $block_high, $page),
				$ellipsis,
				number_paginate_loop(($pages - $anchor + 1), $pages)
			);
		}
		
		else {
			$output .= number_paginate_loop(1, $pages, $page);
		}
		if ($page < $pages && !empty($nextpage)) {
			$output .= "<a href='" . get_pagenum_link($page + 1) . "' class='number-next'>$nextpage</a>";
		}
		$output .= $after;
	}
	if ($echo) {
		echo $output;
	}
	return $output;
}

function number_paginate_loop($start, $max, $page = 0) {
	$output = "";
	for ($i = $start; $i <= $max; $i++) {
		$output .= ($page === intval($i)) 
			? "<span class='number-page number-current'>$i</span>" 
			: "<a href='" . get_pagenum_link($i) . "' class='number-page'>$i</a>";
	}
	return $output;
}

/*-----------------------------------------------------------------------------------------------------//	
	Featured Video Custom Meta Box		       	     	 
-------------------------------------------------------------------------------------------------------*/

$prefix = 'custom_meta_';

$meta_box = array(
    'id' => 'video-meta-box',
    'title' => 'Featured Video',
    'page' => 'post',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => __("Paste Video Embed Code", 'organicthemes'),
            'desc' => __("Enter Vimeo, YouTube or other embed code to display a featured video.", 'organicthemes'),
            'id' => $prefix . 'video',
            'type' => 'textarea',
            'std' => ''
        ),
    )
);

add_action('admin_menu', 'mytheme_add_box');

// Add meta box
function mytheme_add_box() {
    global $meta_box;
    add_meta_box($meta_box['id'], $meta_box['title'], 'mytheme_show_box', $meta_box['page'], $meta_box['context'], $meta_box['priority']);
}

// Callback function to show fields in meta box
function mytheme_show_box() {
    global $meta_box, $post;
    
    // Use nonce for verification
    echo '<input type="hidden" name="mytheme_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    
    echo '<table class="form-table">';

    foreach ($meta_box['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);
        
        echo '<tr>',
                '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                '<td>';
        switch ($field['type']) {
            case 'textarea':
                echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width: 99%;">', $meta ? $meta : $field['std'], '</textarea>', $field['desc'];
                break;
        }
        echo     '<td>',
            '</tr>';
    }
    
    echo '</table>';
}

add_action('save_post', 'mytheme_save_data');

// Save data from meta box
function mytheme_save_data($post_id) {
    global $meta_box;
    
    // verify nonce
    if (!wp_verify_nonce($_POST['mytheme_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    
    foreach ($meta_box['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}

/*-----------------------------------------------------------------------------------------------------//	
	Add post link meta box       	     	 
-------------------------------------------------------------------------------------------------------*/

add_action("admin_init", "admin_init");
add_action('save_post', 'save_titlelink');

function admin_init(){
	add_meta_box("prodInfo-meta", __("Post Title Link (For Link Format)", 'organicthemes'), "meta_options", "post", "side", "low");
}

function meta_options(){
	global $post;
	$custom = get_post_custom($post->ID);
	$titlelink = $custom["titlelink"][0];

	echo '<label>URL: </label><input type="text" style="width: 220px;" name="titlelink" value="'.$titlelink.'" />';
}

function save_titlelink($post_id){
	global $post;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	        return $post_id;
	    }
	update_post_meta($post->ID, "titlelink", $_POST["titlelink"]);
}

/*-----------------------------------------------------------------------------------------------------//	
	Filter post title for post format links	       	     	 
-------------------------------------------------------------------------------------------------------*/

function sd_link_filter($link, $post) {
     if (has_post_format('link', $post) && get_post_meta($post->ID, 'titlelink', true)) {
          $link = get_post_meta($post->ID, 'titlelink', true);
     }
     return $link;
}
add_filter('post_link', 'sd_link_filter', 10, 2);

/*-----------------------------------------------------------------------------------------------------//	
	Add post formats	       	     	 
-------------------------------------------------------------------------------------------------------*/

add_theme_support( 'post-formats', array( 
	'gallery',
	'link',
	'image',
	'quote',
	'video'	
	) 
);

/*-----------------------------------------------------------------------------------------------------//	
	Add ID and CLASS attributes to the first <ul> occurence in wp_page_menu		       	     	 
-------------------------------------------------------------------------------------------------------*/

function add_menuclass($ulclass) {
	return preg_replace('/<ul>/', '<ul class="menu">', $ulclass, 1);
}
add_filter('wp_page_menu','add_menuclass');
add_filter('wp_nav_menu','add_menuclass');

/*-----------------------------------------------------------------------------------------------------//	
	Custom Search Widget		       	     	 
-------------------------------------------------------------------------------------------------------*/

function style_search_form($form) {
    $form = '<form method="get" id="searchform" action="' . get_option('home') . '/" >
            <label for="s">' . __('Search') . '</label>
            <div>';
    if (is_search()) {
        $form .='<input type="text" value="' . attribute_escape(apply_filters('the_search_query', get_search_query())) . '" name="s" id="s" />';
    } else {
        $form .='<input type="text" value="Search Site" name="s" id="s"  onfocus="if(this.value==this.defaultValue)this.value=\'\';" onblur="if(this.value==\'\')this.value=this.defaultValue;"/>';
    }
    $form .= '<input type="submit" id="searchsubmit" value="'.attribute_escape(__('Go')).'" />
            </div>
            </form>';
    return $form;
}
add_filter('get_search_form', 'style_search_form');

/*-----------------------------------------------------------------------------------------------------//	
	WP 3.4+ custom header		       	     	 
-------------------------------------------------------------------------------------------------------*/

if ( function_exists('add_theme_support') )
$defaults = array(
	'width'                  => 440,
	'height'                 => 160,
	'default-image' => get_template_directory_uri() . '/images/logo.png',
	'flex-height'            => true,
	'flex-width'             => true,
	'default-text-color'     => '333333',
	'header-text'            => false,
	'uploads'                => true,
);
add_theme_support( 'custom-header', $defaults );

/*-----------------------------------------------------------------------------------------------------//	
	WP 3.4+ custom background		       	     	 
-------------------------------------------------------------------------------------------------------*/

if ( function_exists('add_theme_support') )
$defaults = array(
	'default-color'          => 'F9F9F9',
	'wp-head-callback'       => '_custom_background_cb',
	'admin-head-callback'    => '',
	'admin-preview-callback' => ''
);
add_theme_support( 'custom-background', $defaults );

/*-----------------------------------------------------------------------------------------------------//	
	Navigation support		       	     	 
-------------------------------------------------------------------------------------------------------*/

if( !function_exists( 'ot_register_menu' ) ) {
    function ot_register_menu() {
	    register_nav_menu('header-menu', __('Header Menu'));
    }
    add_action('init', 'ot_register_menu');
}

// Display home link in custom menu
function home_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter('wp_page_menu_args', 'home_page_menu_args');

/*-----------------------------------------------------------------------------------------------------//	
	Add default posts and comments RSS feed links to head		       	     	 
-------------------------------------------------------------------------------------------------------*/

if ( function_exists('add_theme_support') )
add_theme_support( 'automatic-feed-links' );

/*-----------------------------------------------------------------------------------------------------//	
	Strip inline width and height attributes from WP generated images		       	     	 
-------------------------------------------------------------------------------------------------------*/
 
function remove_thumbnail_dimensions( $html ) { 
	$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html ); 
	return $html; 
	}
add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10 ); 
add_filter( 'image_send_to_editor', 'remove_thumbnail_dimensions', 10 );

/*-----------------------------------------------------------------------------------------------------//	
	Include Shortcodes		       	     	 
-------------------------------------------------------------------------------------------------------*/

include('includes/shortcodes.php');

/*-----------------------------------------------------------------------------------------------------//	
	Thumbnail support		       	     	 
-------------------------------------------------------------------------------------------------------*/

if ( function_exists('add_theme_support') )
add_theme_support('post-thumbnails');
set_post_thumbnail_size(1080, 357);  // setting the post thumbnail size to 1080* 357
add_image_size( 'feature', 1080, 800 ); // Featured Image
add_image_size( 'banner', 1600, 800, true ); // Slideshow and Banner Image
add_image_size( 'mycustomsize', 299, 224 );
add_image_size( 'fullsize', 1080, 357 );
add_image_size( 'cropping', 480, 357, true);
/*
original

if ( function_exists('add_theme_support') )
add_theme_support('post-thumbnails');
add_image_size( 'feature', 1080, 800 ); // Featured Image
add_image_size( 'banner', 1600, 800, true ); // Slideshow and Banner Image
add_image_size( 'thumb', 1080, 357 ); // Thumbnai Image Size

*/

/*-----------------------------------------------------------------------------------------------------//	
	Addition of Jquery for the mPortfolio page      // added by Binesh		       	     	 
-------------------------------------------------------------------------------------------------------*/

if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);
function my_jquery_enqueue() {
   wp_deregister_script('jquery');
   wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js", false, null);
   wp_enqueue_script('jquery');
}

/*-----------------------------------------------------------------------------------------------------//	
	Another code for adding jquery      // added by Binesh		       	     	 
-------------------------------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------------------------//	
	Count post view      // added by Yiqi	       	     	 
-------------------------------------------------------------------------------------------------------*/
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    return $count;
}
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
    }else{
        $count++;        
    }
//	if($postID == 63){ //63,777(cheerleader)
//		$count = 0;
//	}
	$ssb_post_sites = get_post_meta($postID, 'ssb_post_sites', true );
	$url_fb = "http://graph.facebook.com/?id=" . get_permalink($postID);
	$content_fb = file_get_contents($url_fb);
	$json_fb = json_decode($content_fb, true);
	$ssb_post_sites["fb"] = $json_fb['shares'];
	
	$url_tw = "http://cdn.api.twitter.com/1/urls/count.json?url=" . get_permalink($postID);
	$content_tw = file_get_contents($url_tw);
	$json_tw = json_decode($content_tw, true);
	$ssb_post_sites["twitter"] = $json_tw['count'];	
/* 	//curl_init() not working in WP
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . "http://blogs.ifas.ufl.edu/global/2015/03/23/spring-vegetable-garden/" . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	$curl_results = curl_exec ($curl);
	curl_close ($curl);
	$json = json_decode($curl_results, true);
	$ssb_post_sites["gplus"] = intval( $json[0]['result']['metadata']['globalCounts']['count'] );
*/
	$json_string = wp_remote_request('https://clients6.google.com/rpc',
	array(
		'method'    => 'POST',
		'body'      => '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'. get_permalink($postID) .'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]',
		'headers' => array('Content-Type' => 'application/json')
	));
	$json = json_decode($json_string['body'], true);
	$ssb_post_sites["gplus"] = intval($json[0]['result']['metadata']['globalCounts']['count']);

	update_post_meta($postID, $count_key, $count);
	update_post_meta($postID, 'ssb_post_sites', $ssb_post_sites );
	$global_postID = get_post_meta($postID, "mapping_ID", true);
	switch_to_blog(48);
	update_post_meta($global_postID, $count_key, $count);
	update_post_meta($global_postID, 'ssb_post_sites', $ssb_post_sites );
	restore_current_blog();
	//echo $count;
	//echo get_post_meta($postID, "mapping_ID", true);
	//echo get_current_blog_id();
	//echo $postID;
	//echo get_post_id_fromURL(the_permalink());
	//echo ' - '.$count.' views';
}

// Remove issues with prefetching adding extra views
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0); 

