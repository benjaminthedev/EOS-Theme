<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme's shortcodes
 *
 * @filter us_config_shortcodes
 */
global $us_template_directory;

$social_links = us_config( 'social_links' );

$social_links_config = array();

foreach ( $social_links as $name => $title ) {
	$social_links_config[$name] = '';
}

return array(

	/**
	 * Registering new shortcodes
	 */
	'us_blog' => array(
		'atts' => array(
			'type' => 'grid',
			'columns' => 2,
			'orderby' => 'date',
			'items' => NULL,
			'pagination' => 'none',
			'categories' => NULL,
			'layout' => 'classic',
			'img_size' => 'default',
			'title_size' => '',
			'show_date' => TRUE,
			'show_author' => TRUE,
			'show_categories' => TRUE,
			'show_tags' => TRUE,
			'show_comments' => TRUE,
			'show_read_more' => TRUE,
			'content_type' => 'excerpt',
			'el_class' => '',
			'carousel_arrows' => FALSE,
			'carousel_dots' => FALSE,
			'carousel_center' => FALSE,
			'carousel_slideby' => FALSE,
			'carousel_autoplay' => FALSE,
			'carousel_interval' => 3,
			'filter' => 'none',
			'filter_style' => 'style_1',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_blog.php',
	),
	'us_btn' => array(
		'atts' => array(
			'text' => __( 'Click Me', 'us' ),
			'link' => '',
			'color' => 'primary',
			'bg_color' => '',
			'text_color' => '',
			'style' => 'solid',
			'icon' => '',
			'iconpos' => 'left',
			'size' => '15px',
			'align' => 'left',
			'el_class' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_btn.php',
	),
	'us_cform' => array(
		'atts' => array(
			'receiver_email' => get_option( 'admin_email' ),
			'name_field' => 'required',
			'email_field' => 'required',
			'phone_field' => 'required',
			'message_field' => 'required',
			'captcha_field' => 'hidden',
			'button_color' => 'primary',
			'button_bg_color' => '',
			'button_text_color' => '',
			'button_style' => 'solid',
			'button_size' => '',
			'button_align' => 'left',
			'button_text' => '',
			'el_class' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_cform.php',
	),
	'us_contacts' => array(
		'atts' => array(
			'address' => '',
			'phone' => '',
			'fax' => '',
			'email' => '',
			'el_class' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_contacts.php',
	),
	'us_counter' => array(
		'atts' => array(
			'initial' => '0',
			'target' => '99',
			'prefix' => '',
			'suffix' => '',
			'color' => 'heading',
			'custom_color' => '',
			'size' => 'medium',
			'title' => __( 'Projects completed', 'us' ),
			'title_tag' => 'h6',
			'title_size' => '',
			'align' => 'center',
			'el_class' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_counter.php',
	),
	'us_cta' => array(
		'atts' => array(
			'title' => 'This is ActionBox',
			'title_tag' => 'h2',
			'title_size' => '',
			'color' => 'primary',
			'bg_color' => '',
			'text_color' => '',
			'controls' => 'right',
			'btn_label' => __( 'Click Me', 'us' ),
			'btn_link' => '',
			'btn_color' => 'white',
			'btn_bg_color' => '',
			'btn_text_color' => '',
			'btn_style' => 'solid',
			'btn_size' => '15px',
			'btn_icon' => '',
			'btn_iconpos' => 'left',
			'second_button' => FALSE,
			'btn2_label' => __( 'Click Me', 'us' ),
			'btn2_link' => '',
			'btn2_color' => 'secondary',
			'btn2_bg_color' => '',
			'btn2_text_color' => '',
			'btn2_style' => 'solid',
			'btn2_size' => '15px',
			'btn2_icon' => '',
			'btn2_iconpos' => 'left',
			'el_class' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_cta.php',
	),
	'us_gallery' => array(
		'atts' => array(
			'ids' => '',
			'columns' => 6,
			'layout' => 'default',
			'masonry' => FALSE, // Masonry checkbox used in WP gallery
			'orderby' => '',
			'indents' => FALSE,
			'meta' => FALSE,
			'meta_style' => 'simple',
			'img_size' => 'default',
			'el_class' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_gallery.php',
	),
	'us_gmaps' => array(
		'atts' => array(
			'marker_address' => '1600 Amphitheatre Parkway, Mountain View, CA 94043, United States',
			'marker_text' => base64_encode( '<h6>Hey, we are here!</h6><p>We will be glad to see you in our office.</p>' ),
			'show_infowindow' => FALSE,
			'markers' => '',
			'custom_marker_img' => '',
			'custom_marker_size' => 20,
			'height' => 400,
			'type' => 'roadmap',
			'zoom' => 14,
			'latitude' => '',
			'longitude' => '',
			'hide_controls' => FALSE,
			'disable_zoom' => FALSE,
			'disable_dragging' => FALSE,
			'map_bg_color' => '',
			'map_style_json' => '',
			'el_class' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_gmaps.php',
	),
	'us_iconbox' => array(
		'atts' => array(
			'icon' => 'fa-star',
			'style' => 'default',
			'color' => 'primary',
			'icon_color' => FALSE,
			'bg_color' => FALSE,
			'iconpos' => 'top',
			'size' => '36px',
			'title' => '',
			'title_tag' => 'h4',
			'title_size' => '',
			'link' => '',
			'img' => '',
			'el_class' => '',
		),
		'content' => '',
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_iconbox.php',
	),
	'us_image_slider' => array(
		'atts' => array(
			'ids' => '',
			'arrows' => 'always',
			'nav' => 'none',
			'transition' => 'slide',
			'autoplay' => FALSE,
			'autoplay_period' => 3,
			'fullscreen' => FALSE,
			'meta' => FALSE,
			'orderby' => '',
			'img_size' => 'large',
			'img_fit' => 'scaledown',
			'style' => 'none',
			'el_class' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_image_slider.php',
	),
	'us_logos' => array(
		'atts' => array(
			'type' => 'carousel',
			'columns' => 5,
			'with_indents' => FALSE,
			'style' => 1,
			'orderby' => '',
			'items' => array(),
			'el_class' => '',
			'carousel_arrows' => FALSE,
			'carousel_dots' => FALSE,
			'carousel_center' => FALSE,
			'carousel_slideby' => FALSE,
			'carousel_autoplay' => FALSE,
			'carousel_interval' => 3,
		),
		'items_atts' => array(
			'image' => '',
			'link' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_logos.php',
	),
	'us_message' => array(
		'atts' => array(
			'color' => 'info',
			'bg_color' => '',
			'text_color' => '',
			'icon' => '',
			'closing' => FALSE,
			'el_class' => '',
		),
		'content' => 'I am message box. Click edit button to change this text.',
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_message.php',
	),
	'us_person' => array(
		'atts' => array(
			'image' => '',
			'image_hover' => '',
			'name' => 'John Doe',
			'role' => 'UpSolution Team',
			'layout' => 'circle',
			'effect' => 'none',
			'link' => '',
			'email' => '',
			'facebook' => '',
			'twitter' => '',
			'google_plus' => '',
			'linkedin' => '',
			'skype' => '',
			'custom_icon' => '',
			'custom_link' => '',
			'el_class' => '',
		),
		'content' => '',
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_person.php',
	),
	'us_portfolio' => array(
		'atts' => array(
			'type' => 'grid',
			'columns' => 3,
			'orderby' => 'date',
			'items' => '',
			'pagination' => 'none',
			'ratio' => '1x1',
			'categories' => NULL,
			'items_action' => 'default',
			'popup_width' => '',
			'style' => 'style_1',
			'align' => 'center',
			'with_indents' => FALSE,
			'title_size' => '',
			'meta' => '',
			'meta_size' => '',
			'text_color' => '',
			'bg_color' => '',
			'img_size' => 'medium_large',
			'el_class' => '',
			'carousel_arrows' => FALSE,
			'carousel_dots' => FALSE,
			'carousel_center' => FALSE,
			'carousel_slideby' => FALSE,
			'carousel_autoplay' => FALSE,
			'carousel_interval' => 3,
			'filter' => 'none',
			'filter_style' => 'style_1',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_portfolio.php',
	),
	'us_pricing' => array(
		'atts' => array(
			'style' => '1',
			'items' => '%5B%7B%22title%22%3A%22Free%22%2C%22price%22%3A%22%240%22%2C%22substring%22%3A%22per%20month%22%2C%22features%22%3A%221%20project%5Cn1%20user%5Cn200%20tasks%5CnNo%20support%22%2C%22btn_text%22%3A%22Sign%20up%22%2C%22btn_color%22%3A%22light%22%2C%22btn_style%22%3A%22solid%22%2C%22btn_size%22%3A%2215px%22%2C%22btn_iconpos%22%3A%22left%22%7D%2C%7B%22title%22%3A%22Standard%22%2C%22type%22%3A%22featured%22%2C%22price%22%3A%22%2424%22%2C%22substring%22%3A%22per%20month%22%2C%22features%22%3A%2210%20projects%5Cn10%20users%5CnUnlimited%20tasks%5CnPremium%20support%22%2C%22btn_text%22%3A%22Sign%20up%22%2C%22btn_color%22%3A%22primary%22%2C%22btn_style%22%3A%22solid%22%2C%22btn_size%22%3A%2215px%22%2C%22btn_iconpos%22%3A%22left%22%7D%2C%7B%22title%22%3A%22Premium%22%2C%22price%22%3A%22%2450%22%2C%22substring%22%3A%22per%20month%22%2C%22features%22%3A%22Unlimited%20projects%5CnUnlimited%20users%5CnUnlimited%20tasks%5CnPremium%20support%22%2C%22btn_text%22%3A%22Sign%20up%22%2C%22btn_color%22%3A%22light%22%2C%22btn_style%22%3A%22solid%22%2C%22btn_size%22%3A%2215px%22%2C%22btn_iconpos%22%3A%22left%22%7D%5D',
			'el_class' => '',
		),
		'items_atts' => array(
			'title' => 'New Item',
			'type' => 'default',
			'price' => '$99',
			'substring' => 'per month',
			'features' => '',
			'btn_text' => 'Sign up',
			'btn_color' => 'primary',
			'btn_bg_color' => '',
			'btn_text_color' => '',
			'btn_style' => 'solid',
			'btn_size' => '15px',
			'btn_icon' => '',
			'btn_iconpos' => 'left',
			'btn_link' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_pricing.php',
	),
	'us_progbar' => array(
		'atts' => array(
			'title' => 'This is Progress Bar',
			'count' => '50',
			'style' => '1',
			'color' => 'primary',
			'bar_color' => '',
			'size' => '10px',
			'hide_count' => FALSE,
			'el_class' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_progbar.php',
	),
	'us_scroller' => array(
		'atts' => array(
			'speed' => '1000',
			'disable_width' => '768px',
			'dots' => FALSE,
			'dots_style' => 'style_1',
			'dots_pos' => 'right',
			'dots_size' => '10px',
			'dots_color' => '',
			'el_class' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_scroller.php',
	),
	'us_separator' => array(
		'atts' => array(
			'type' => 'default',
			'size' => 'medium',
			'thick' => '1',
			'style' => 'solid',
			'color' => 'border',
			'bdcolor' => '',
			'icon' => '',
			'text' => '',
			'title_tag' => 'h6',
			'title_size' => '',
			'el_class' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_separator.php',
	),
	'us_sharing' => array(
		'atts' => array(
			'type' => 'simple',
			'align' => 'left',
			'color' => 'default',
			'counters' => 'show',
			'email' => FALSE,
			'facebook' => TRUE,
			'twitter' => TRUE,
			'linkedin' => FALSE,
			'gplus' => TRUE,
			'pinterest' => FALSE,
			'vk' => FALSE,
			'url' => '',
			'el_class' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_sharing.php',
	),
	'us_single_image' => array(
		'atts' => array(
			'image' => '',
			'size' => 'large',
			'align' => '',
			'style' => '',
			'meta' => FALSE,
			'meta_style' => 'simple',
			'onclick' => '',
			'link' => '',
			'animate' => '',
			'animate_delay' => '',
			'el_class' => '',
			'css' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_single_image.php',
	),
	'us_social_links' => array(
		'atts' => array_merge(
			array(
				'email' => '',
			), $social_links_config, array(
				'custom_link' => '',
				'custom_title' => '',
				'custom_icon' => '',
				'custom_color' => '#1abc9c',
				'style' => 'default',
				'color' => 'brand',
				'size' => '20px',
				'align' => 'left',
				'el_class' => '',
			)
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_social_links.php',
	),
	'us_testimonial' => array(
		'atts' => array(
			'style' => 1,
			'author' => 'John Doe',
			'company' => 'UpSolution Client',
			'img' => '',
			'link' => '',
			'el_class' => '',
		),
		'content' => 'Text goes here',
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_testimonial.php',
	),
	'us_testimonials' => array(
		'atts' => array(
			'type' => 'grid',
			'columns' => 3,
			'orderby' => 'date',
			'style' => 1,
			'text_size' => '',
			'items' => '',
			'ids' => '',
			'el_class' => '',
			'categories' => NULL,
			'carousel_arrows' => FALSE,
			'carousel_dots' => FALSE,
			'carousel_center' => FALSE,
			'carousel_slideby' => FALSE,
			'carousel_autoplay' => FALSE,
			'carousel_interval' => 3,
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/us_testimonials.php',
	),
	
	/**
	 * Overloading existing shortcodes
	 */
	'vc_column' => array(
		'atts' => array(
			'width' => '1/1',
			'text_color' => '',
			'animate' => '',
			'animate_delay' => '',
			'el_id' => '',
			'el_class' => '',
			'offset' => '',
			'css' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/vc_column.php',
	),
	'vc_column_inner' => array(
		'atts' => array(
			'width' => '1/1',
			'text_color' => '',
			'animate' => '',
			'animate_delay' => '',
			'el_id' => '',
			'el_class' => '',
			'offset' => '',
			'css' => '',
		),
		'alias_of' => 'vc_column',
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/vc_column_inner.php',
	),
	'vc_column_text' => array(
		'atts' => array(
			'el_id' => '',
			'el_class' => '',
			'css' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/vc_column_text.php',
	),
	'vc_custom_heading' => array(
		'overload' => FALSE,
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/vc_custom_heading.php',
	),
	'vc_row' => array(
		'atts' => array(
			'content_placement' => 'top',
			'columns_type' => 'default',
			'gap' => '',
			'height' => 'default',
			'valign' => '',
			'width' => '',
			'color_scheme' => '',
			'us_bg_color' => '',
			'us_text_color' => '',
			'us_bg_image' => '',
			'us_bg_size' => 'cover',
			'us_bg_repeat' => 'repeat',
			'us_bg_pos' => 'center center',
			'us_bg_parallax' => '',
			'us_bg_parallax_width' => '130',
			'us_bg_parallax_reverse' => FALSE,
			'us_bg_video' => '',
			'us_bg_overlay_color' => '',
			'sticky' => FALSE,
			'sticky_disable_width' => '900px',
			'el_id' => '',
			'el_class' => '',
			'disable_element' => '',
			'css' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/vc_row.php',
	),
	'vc_row_inner' => array(
		'atts' => array(
			'content_placement' => 'top',
			'columns_type' => 'default',
			'gap' => '',
			'el_id' => '',
			'el_class' => '',
			'disable_element' => '',
			'css' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/vc_row_inner.php',
	),
	'vc_tta_accordion' => array(
		'atts' => array(
			'toggle' => FALSE,
			'c_align' => 'left',
			'c_icon' => 'chevron',
			'c_position' => 'right',
			'title_tag' => 'h5',
			'title_size' => '',
			'el_id' => '',
			'el_class' => '',
			'css' => '',
		),
		'alias_of' => 'vc_tta_tabs',
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/vc_tta_accordion.php',
	),
	'vc_tta_section' => array(
		'atts' => array(
			'title' => '',
			'tab_id' => '',
			'icon' => '',
			'i_position' => 'left',
			'active' => FALSE,
			'indents' => '',
			'bg_color' => '',
			'text_color' => '',
			'c_position' => 'right',
			'title_tag' => 'h5',
			'title_size' => '',
			'el_class' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/vc_tta_section.php',
	),
	'vc_tta_tabs' => array(
		'atts' => array(
			'layout' => 'default',
			'stretch' => FALSE,
			'title_size' => '',
			'el_id' => '',
			'el_class' => '',
			'css' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/vc_tta_tabs.php',
	),
	'vc_tta_tour' => array(
		'atts' => array(
			'c_align' => 'left',
			'tab_position' => 'left',
			'controls_size' => 'auto',
			'title_size' => '',
			'el_id' => '',
			'el_class' => '',
			'css' => '',
		),
		'alias_of' => 'vc_tta_tabs',
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/vc_tta_tour.php',
	),
	'vc_video' => array(
		'atts' => array(
			'link' => 'https://youtu.be/XuWr9gJa6P0',
			'video_related' => FALSE,
			'video_title' => TRUE,
			'ratio' => '16x9',
			'max_width' => '',
			'align' => 'left',
			'el_id' => '',
			'el_class' => '',
			'css' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/vc_video.php',
	),
	'vc_wp_custommenu' => array(
		'atts' => array(
			'title' => '',
			'layout' => 'ver',
			'align' => 'left',
			'font_size' => '',
			'nav_menu' => NULL,
			'el_id' => '',
			'el_class' => '',
		),
		'custom_vc_map' => $us_template_directory . '/framework/plugins-support/js_composer/map/vc_wp_custommenu.php',
	),
	
	/**
	 * Backward compatibility
	 */
	'gallery' => array(
		'alias_of' => 'us_gallery',
	),
	'vc_tabs' => array(
		'alias_of' => 'vc_tta_tabs',
	),
	'vc_accordion' => array(
		'alias_of' => 'vc_tta_tabs',
	),
	'vc_tab' => array(
		'alias_of' => 'vc_tta_section',
	),
	'vc_accordion_tab' => array(
		'alias_of' => 'vc_tta_section',
	),
	'vc_separator' => array(
		'alias_of' => 'us_separator',
	),
	'vc_text_separator' => array(
		'alias_of' => 'us_separator',
	),
	'vc_message' => array(
		'alias_of' => 'us_message',
	),
	'vc_single_image' => array(
		'alias_of' => 'us_single_image',
	),
	'vc_gallery' => array(
		'alias_of' => 'us_gallery',
	),
	'vc_gmaps' => array(
		'alias_of' => 'us_gmaps',
	),
	
	/**
	 * Shortcodes that are not supported by the theme, and should be temporarily disabled
	 */
	'vc_icon' => array(
		'supported' => FALSE,
	),
	'vc_btn' => array(
		'supported' => FALSE,
	),
	'vc_facebook' => array(
		'supported' => FALSE,
	),
	'vc_tweetmeme' => array(
		'supported' => FALSE,
	),
	'vc_googleplus' => array(
		'supported' => FALSE,
	),
	'vc_pinterest' => array(
		'supported' => FALSE,
	),
	'vc_flickr' => array(
		'supported' => FALSE,
	),
	'vc_tta_pageable' => array(
		'supported' => FALSE,
	),
	'vc_toggle' => array(
		'supported' => FALSE,
	),
	'vc_tour' => array(
		'supported' => FALSE,
	),
	'vc_posts_slider' => array(
		'supported' => FALSE,
	),
	'vc_progress_bar' => array(
		'supported' => FALSE,
	),
	'vc_pie' => array(
		'supported' => FALSE,
	),
	'vc_basic_grid' => array(
		'supported' => FALSE,
	),
	'vc_media_grid' => array(
		'supported' => FALSE,
	),
	'vc_images_carousel' => array(
		'supported' => FALSE,
	),
	'vc_masonry_grid' => array(
		'supported' => FALSE,
	),
	'vc_masonry_media_grid' => array(
		'supported' => FALSE,
	),
	'vc_section' => array(
		'supported' => FALSE,
	),
	'vc_button2' => array(
		'supported' => FALSE,
	),
	'vc_separator' => array(
		'supported' => FALSE,
	),
	'vc_text_separator' => array(
		'supported' => FALSE,
	),

);
