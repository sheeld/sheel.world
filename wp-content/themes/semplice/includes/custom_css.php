<?php
/*
 * custom css
 * semplice.theme
 * 
 */

// add mobile detection
$detect = new Mobile_Detect;
 
// output css var
$output_css = '';

// page content after slider
if(get_field('coverslider_orientation') === 'horizontal' && get_field('content_after_slider')) {
	$slider_content_id = get_field('content_after_slider');
	$slider_content_id = $slider_content_id->ID;
}

  
#---------------------------------------------------------------------------#
# Fullscreen Cover															#
#---------------------------------------------------------------------------#

// cover visbility
$cover_visibility = get_field('cover_visibility');

// content vertical position
$vertical_positioning = get_field('content_vertical_positioning');
 
// get project objects
$covers = get_field('cover_slider');

if($covers && get_Field('use_semplice') === 'coverslider') {

	// loader bar color
	$output_css .= '.bar { background: ' . get_field('loader_bar_color_coverslider') . ' !important; }';

	foreach($covers as $post) {
		
		// get post id
		setup_postdata($post);

		// look if there is a fullscreen cover		
		if(get_field('cover_visibility') === 'visible') {
			semplice_cover($post->ID, $detect, true);
		}
	}
	
	wp_reset_postdata();
	
} else {

	if($cover_visibility === 'visible') {
		// call semplice cover
		semplice_cover(get_the_id(), $detect, false);
	}
	
}

// semlice cover function
function semplice_cover($id, $detect, $is_slider) {

	$cover_css = '#content { height: 100%; }';
	
	if(get_field('cover_bg_color')) {
		$cover_css .= '.cover-' . $id . ', .cover-' . $id . ' .cover-image, .cover-' . $id . ' .video-fadein { background-color: ' . get_field('cover_bg_color') . ' !important; }';
	}
	
	// is image or video
	if(get_field('cover_bg_type') === 'image') {
		if(get_field('cover_bg_image')) {
			$cover_css .= '.cover-' . $id . ' .cover-image { background-image: url(' . get_field('cover_bg_image') . '); }';
			$cover_css .= '.cover-' . $id . ' .cover-image { opacity: '. get_field('cover_bg_image_opacity') .'; }';
			$cover_css .= '.cover-' . $id . ' .cover-image { background-repeat: ' . get_field('cover_bg_image_repeat') . ' !important; }';
			
			if(get_field('cover_bg_image_scale') === 'full-screen') {
				$cover_css .= '.cover-' . $id . ' .cover-image { background-size: cover; }';
			} else if(get_field('cover_bg_image_repeat') !== 'no-repeat') {
				$cover_css .= '.cover-' . $id . ' .cover-image { background-size: auto !important; }';
			}
			if(get_field('cover_bg_image_align') && get_field('cover_bg_image_scale') !== 'full-screen') {
				$cover_css .= '.cover-' . $id . ' .cover-image { background-position: ' . get_field('cover_bg_image_align') . '; }';
			} else {
				$cover_css .= '.cover-' . $id . ' .cover-image { background-position: top center; }';
			}
		}
	} else {
		$cover_css .= '.cover-' . $id . ' .cover-video-responsive { background-image: url(' . get_field('video_fallback_bg') . '); }';
		$cover_css .= '.cover-' . $id . ' .cover-video-responsive { background-position: 50% 0; }';
		if(get_field('cover_video_opacity')) {
			$cover_css .= '.cover-' . $id . ' .cover-video { opacity: '. get_field('cover_video_opacity') . '}';
			$cover_css .= '.cover-' . $id . ' { background: '. get_field('cover_bg_color') . '}';
		}
	}
	
	// cover headline width	
	if(get_field('cover_headline_width') === 'fluid') {
		$cover_css .= '@media screen and ( min-width: 1200px ) { .cover-' . $id . ' .container { width: 100%; } }';
		$cover_css .= '.cover-' . $id . ' .cover-headline { width: 100%; }';
		// cover headline responsive width and fullscreen cover responsive container width
		$cover_css .= '@media (min-width: 980px) and (max-width: 1199px) { .cover-' . $id . ' .cover-headline { width: 940px; }}';
		$cover_css .= '@media (max-width: 979px) { .cover-' . $id . ' .cover-headline { width: 724px; }}';
		$cover_css .= '@media (max-width: 767px) { .cover-' . $id . ' .container { width: 100%; } .cover-' . $id . ' .cover-headline { width: 100%; }}';
		$cover_css .= '@media (max-width: 567px) { .cover-' . $id . ' .container { width: 100%; } .cover-' . $id . ' .cover-headline { width: 100%; }}';
	}
	
	// cover headline image responsive width
	if(get_field('cover_headline_format') === 'image') {

		// get headline image src
		$headline_img = wp_get_attachment_image_src(get_field('cover_headline_image'), 'full');
		
		// filetype
		$filetype = wp_check_filetype($headline_img[0]);
		
		// percent width
		if($filetype['ext'] === 'svg') {
			$auto_width = 'auto';
		} else {
			$auto_width = $headline_img[1] / 1170 * 100 . '%';
		}
		
		$mobile_sizes = get_field('cover_headline_mobile_width');

		if($mobile_sizes) {

			// get sizes
			foreach ($mobile_sizes as $key => $value) {
				if($value == 'auto') {
					$mobile_sizes[$key] = $auto_width;
				} else {
					$mobile_sizes[$key] = $value . '%';
				}
			}

			// tablet wide
			if($mobile_sizes['tablet_wide'] !== 'auto') {
				$cover_css .= '@media (max-width: 979px) { .cover-' . $id . ' .cover-headline .headline-image { width: ' . $mobile_sizes['tablet_wide'] . '; height: auto; } }';
			} 
			// tablet portrait
			if($mobile_sizes['tablet_portrait'] !== 'auto') {
				$cover_css .= '@media (max-width: 767px) { .cover-' . $id . ' .cover-headline .headline-image { width: ' . $mobile_sizes['tablet_portrait'] . '; height: auto; } }';
			}

			// mobile
			if($mobile_sizes['mobile'] !== 'auto') {
				$cover_css .= '@media (max-width: 567px) { .cover-' . $id . ' .cover-headline .headline-image { width: ' . $mobile_sizes['mobile'] . '; height: auto; } }';
			}	
		}
	}

	if(get_field('cover_headline_color')) {
		$cover_css .= '.cover-' . $id . ' .cover-headline h1 { color: '. get_field('cover_headline_color') . ' !important; }';
	}
	
	if(get_field('cover_headline_fontsize')) {
		$font_size = get_field('cover_headline_fontsize');
		cover_heading_fontsize($font_size, $id);
	}
	
	if(get_field('cover_headline_text_transform')) {
		$cover_css .= '.cover-' . $id . ' .cover-headline h1 { text-transform: '. get_field('cover_headline_text_transform') . ' !important; }';
	}

	if(get_field('loader_bar_color') && !$is_slider) {
		$cover_css .= '.bar { background: ' . get_field('loader_bar_color') . ' !important; }';
	}
	
	if(get_field('cover_scroll') === 'visible') {
		if(get_field('cover_scroll_text_color')) {
			$cover_css .= '.cover-' . $id . ' .see-more .text { color: ' . get_field('cover_scroll_text_color') . ' !important; }';
		}
		if(get_field('cover_scroll_color')) {
			$cover_css .= '.cover-' . $id . ' .see-more .icon svg { fill: ' . get_field('cover_scroll_color') . ' !important; }';
		}
	}
	
	if(!$detect->isMobile()) {
		$cover_css .= '.cover-' . $id . ' .see-more:hover .icon { transform: scale(1.15);-ms-transform: scale(1.15);-webkit-transform: scale(1.15); }';
	}
	
	// if slider make some adjustments
	if($is_slider) {
		$cover_css .= '.see-more { display: none !important; }';
		
		if(get_field('use_vp_button') === 'enabled') {
		
			if(get_field('vp_button_font_color')) {
			$cover_css .= '.vp-' . $id . ' a { color: ' . get_field('vp_button_font_color') . ' !important; }';
			}
			if(get_field('vp_button_bg_color')) {
				$cover_css .= '.vp-' . $id . ' a { background: ' . get_field('vp_button_bg_color') . ' !important; }';
			}
			if(get_field('vp_button_border_color')) {
				$cover_css .= '.vp-' . $id . ' a { border-color: ' . get_field('vp_button_border_color') . ' !important; }';
			}
			if(get_field('vp_button_hover_font_color')) {
				$cover_css .= '.vp-' . $id . ' a:hover { color: ' . get_field('vp_button_hover_font_color') . ' !important; }';
			}
			if(get_field('vp_button_hover_bg_color')) {
				$cover_css .= '.vp-' . $id . ' a:hover { background: ' . get_field('vp_button_hover_bg_color') . ' !important; }';
			}
			if(get_field('vp_button_hover_border_color')) {
				$cover_css .= '.vp-' . $id . ' a:hover { border-color: ' . get_field('vp_button_hover_border_color') . ' !important; }';
			}
		}
	}
	
	echo $cover_css;
}

#---------------------------------------------------------------------------#
# Custom Navbar																#
#---------------------------------------------------------------------------#

// get custom navbar post object
if(get_field('use_semplice') === 'coverslider' && get_field('navbar_visibility_coverslider') === 'hidden' || get_field('use_semplice') !== 'coverslider' && get_field('navbar_visibility') === 'hidden') {
	$output_css .= 'header { display: none !important; }';
	$output_css .= '#content-holder { margin-top: 0px !important; }';
	$custom_navbar = false;
} else if(get_field('custom_navbar_coverslider') && get_field('use_semplice') === 'coverslider') {
	$custom_navbar = get_field('custom_navbar_coverslider');
} else if(get_field('custom_navbar')) {
	$custom_navbar = get_field('custom_navbar');
} else if(get_field('so_custom_navbar', 'options')) {
	$custom_navbar = get_field('so_custom_navbar', 'options');
} else {
	$custom_navbar = false;
}

// hex to rgb
function HexToRGB($hex) {
	$hex = str_replace("#", "", $hex);
	$color = array();
	 
	if(strlen($hex) == 3) {
		$color['r'] = hexdec(substr($hex, 0, 1) . $r);
		$color['g'] = hexdec(substr($hex, 1, 1) . $g);
		$color['b'] = hexdec(substr($hex, 2, 1) . $b);
	}
	else if(strlen($hex) == 6) {
		$color['r'] = hexdec(substr($hex, 0, 2));
		$color['g'] = hexdec(substr($hex, 2, 2));
		$color['b'] = hexdec(substr($hex, 4, 2));
	}
	 
	return $color;
}

if($custom_navbar) {
	
	$post = $custom_navbar;
    setup_postdata($post);
	
	// navbar output
	$output_navbar = '';
	
	if(get_field('navbar_bar_bg_color')) {
		$output_navbar .= '#navbar-bg { background: ' . get_field('navbar_bar_bg_color') . ' !important; }';
	}
	
	if(get_field('navbar_fluid') === 'enabled' && get_field('navbar_fluid_gap')) {
		$output_navbar .= '#navbar .fluid-container { left: ' . get_field('navbar_fluid_gap') . '; right: ' . get_field('navbar_fluid_gap') . '; }';
	}
	
	if(get_field('menu_bg_color')) {
		$rgba = HexToRGB(get_field('menu_bg_color'));
		$output_navbar .= '#fullscreen-menu { background: rgb(' . $rgba['r'] . ', ' . $rgba['g'] . ', ' . $rgba['b'] . '); background: rgba(' . $rgba['r'] . ', ' . $rgba['g'] . ', ' . $rgba['b'] . ', ' . get_field('menu_bg_opacity') . '); }';
	}
	
	$output_navbar .= '#navbar-bg { opacity: ' . get_field('navbar_bar_bg_opacity') . '; }';
	
	if(get_field('navbar_bar_border_bottom_color')) {
		$output_navbar .= '#navbar { border-bottom: 1px solid ' . get_field('navbar_bar_border_bottom_color') . ' !important; }';
	}	
	
	if(get_field('navbar_transparent')) {
		$output_navbar .= '.transparent { opacity: 0 !important; }';
		$output_navbar .= '@media (max-width: 767px) {';
		$output_navbar .= '.transparent { background: ' . get_field('navbar_bar_bg_color') . ' !important; }';
		$output_navbar .= '}';
	}

	if(get_field('navbar_bar_menu_button_color')) {
		$output_navbar .= '#navbar .controls a .nav-icon { background: ' . get_field('navbar_bar_menu_button_color') . ' !important; }';
		$output_navbar .= '#navbar .controls a svg { fill: ' . get_field('navbar_bar_menu_button_color') . ' !important; }';
	}
	
	if(get_field('menu_font_letter_spacing')) {
		$output_navbar .= '#fullscreen-menu .menu-inner nav ul li a, .follow-links ul li a svg, nav.standard ul li a { letter-spacing: ' . get_field('menu_font_letter_spacing') . ' !important; }';
	} else {
		$output_navbar .= '#fullscreen-menu .menu-inner nav ul li a, .follow-links ul li a svg, nav.standard ul li a { letter-spacing: 0px !important; }';
	}
	
	if(get_field('menu_font_text_transform')) {
		$output_navbar .= '#fullscreen-menu .menu-inner nav ul li a, .follow-links ul li a svg, nav.standard ul li a { text-transform: ' . get_field('menu_font_text_transform') . ' !important; }';
	}
	
	if(get_field('menu_style') === 'normal') {
		$output_navbar .= '#navbar .controls a.project-panel-button { margin-right: -10px; padding-left: 10px; }';
	}
	
	if(get_field('menu_font_hor_padding') && get_field('menu_style') === 'normal') {
		$output_navbar .= 'nav.standard ul li a { padding: 0px ' . ( get_field('menu_font_hor_padding') / 2 ) . 'px; }';		
	}
	
	if(get_field('dropdown_menu_ver_padding') && get_field('menu_style') === 'fullscreen') {
		$output_navbar .= '#fullscreen-menu .menu-inner nav ul li { padding: ' . get_field('dropdown_menu_ver_padding') . ' 0px; }';		
	}
	
	if(get_field('dropdown_menu_container_padding')) {
		$output_navbar .= '#fullscreen-menu .menu-inner nav { padding: ' . get_field('dropdown_menu_container_padding') . ' 0px; }';
	}

	if(get_field('dropdown_menu_underline_visibility') === 'visible') {
		if(get_field('dropdown_menu_underline_height')) {
			$output_navbar .= '#fullscreen-menu .menu-inner nav ul li a { border-bottom: ' . get_field('dropdown_menu_underline_height') . ' solid; }';
		}
		
		if(get_field('dropdown_menu_underline_padding')) {
			$output_navbar .= '#fullscreen-menu .menu-inner nav ul li a { padding-bottom: ' . get_field('dropdown_menu_underline_padding') . '; }';
		}
	}
	
	if(get_field('menu_link_color')) {
		$output_navbar .= '#fullscreen-menu .menu-inner nav ul li a, .follow-links ul li a svg, nav.standard ul li a { color: ' . get_field('menu_link_color') . ' !important; }';
		$output_navbar .= '.follow-links ul li a svg { fill: ' . get_field('menu_link_color') . ' !important; }';
	}
	
	if(get_field('menu_link_text_decoration')) {
		$output_navbar .= 'nav.standard ul li a { text-decoration: ' . get_field('menu_link_text_decoration') . ' !important; }';
	}
	
	if(get_field('dropdown_link_underline') !== 'transparent') {
		$output_navbar .= '#fullscreen-menu .menu-inner nav ul li a { border-color: ' . get_field('dropdown_link_underline') . ' !important; }';
	}
	
	if(get_field('menu_active_text_color')) {
		$output_navbar .= '#fullscreen-menu .menu-inner nav ul li.current-menu-item a, #fullscreen-menu .menu-inner nav ul li.current_page_item a, nav.standard ul li.current-menu-item a, nav.standard ul li.current_page_item a, #fullscreen-menu .menu-inner nav ul li.current-menu-item a:hover, #fullscreen-menu .menu-inner nav ul li.current_page_item a:hover, nav.standard ul li.current-menu-item a:hover, nav.standard ul li.current_page_item a:hover, .is-work nav.standard ul li.portfolio-grid a, .is-work #fullscreen-menu .menu-inner nav ul li.portfolio-grid a { color: ' . get_field('menu_active_text_color') . ' !important; }';
	}
	
	if(get_field('menu_active_text_decoration')) {
		$output_navbar .= 'nav.standard ul li.current-menu-item a, nav.standard ul li.current_page_item a, nav.standard ul li.current-menu-item a:hover, nav.standard ul li.current_page_item a:hover, .is-work nav.standard ul li.portfolio-grid a { text-decoration: ' . get_field('menu_active_text_decoration') . ' !important; }';
	}
	
	if(get_field('dropdown_active_underline') !== 'transparent') {
		$output_navbar .= '#fullscreen-menu .menu-inner nav ul li.current-menu-item a, #fullscreen-menu .menu-inner nav ul li.current_page_item a, #fullscreen-menu .menu-inner nav ul li.current-menu-item a:hover, #fullscreen-menu .menu-inner nav ul li.current_page_item a:hover, .is-work #fullscreen-menu .menu-inner nav ul li.portfolio-grid a { border-color: ' . get_field('dropdown_active_underline') . ' !important; }';
	}
	
	if(get_field('menu_hover_color')) {
		$output_navbar .= '#fullscreen-menu .menu-inner nav ul li a:hover, .follow-links ul li a svg, nav.standard ul li a:hover { color: ' . get_field('menu_hover_color') . ' !important; }';
		$output_navbar .= '.follow-links ul li a:hover svg { fill: ' . get_field('menu_hover_color') . ' !important; }';
	}
	
	if(get_field('menu_hover_text_decoration')) {
		$output_navbar .= 'nav.standard ul li a:hover { text-decoration: ' . get_field('menu_hover_text_decoration') . ' !important; }';
	}
	
	if(get_field('dropdown_hover_underline') !== 'transparent') {
		$output_navbar .= '#fullscreen-menu .menu-inner nav ul li a:hover { border-color: ' . get_field('dropdown_hover_underline') . ' !important; }';
	}
	
	if(get_field('standard_menu_hover_bg_color')) {
		$output_navbar .= 'nav.standard ul li a:hover { background: ' . get_field('standard_menu_hover_bg_color') . ' !important; }';
	}
	
	if(get_field('standard_menu_active_bg_color')) {
		$output_navbar .= 'nav.standard ul li.current-menu-item a, nav.standard ul li.current-menu-item a:hover, .is-work nav.standard ul li.portfolio-grid a, nav.standard ul li.current_page_item a, nav.standard ul li.current_page_item a:hover { background: ' . get_field('standard_menu_active_bg_color') . ' !important; }';
	}
	
	if(get_field('menu_border_bottom')) {
		$output_navbar .= '#fullscreen-menu .menu-inner nav ul li { border-color: ' . get_field('menu_border_bottom') . ' !important; }';
	}
	
	// standard menu fontsize
	if(get_field('standard_menu_fontsize')) {
		$output_navbar .= 'nav.standard ul li a { font-size: ' . get_field('standard_menu_fontsize') . ' !important; }';
	}
	
	// is sticky?
	if(get_field('sticky_navbar') === 'normal') {
		$output_navbar .= 'header { position: absolute; }';
	}
	
	// full height?
	if(get_field('menu_height') === 'full') {
		$output_navbar .= '#fullscreen-menu { height: 100%; }';
		$output_navbar .= '.menu-inner nav { transform: translateY(-50%); -webkit-transform: translateY(-50%); position: absolute; top: 50%; width: 100%; }';
		$output_navbar .= '.follow-links { position: absolute; bottom: 0px; width: 100%; }';
		$output_navbar .= '#fullscreen-menu .menu-inner nav ul li { border: 0px !important; }';
	} else {
		$output_navbar .= '#fullscreen-menu .menu-inner nav { margin-top: 70px !important; }';
	}
	
	/* set position to absolute for mobile devices */
	if($detect->isMobile()) {
		$output_navbar .= '#fullscreen-menu, #project-panel-header { position: absolute; max-height: none; overflow: visible !important; }';
		$rgba = HexToRGB(get_field('menu_bg_color'));
		$output_navbar .= '#fullscreen-menu .menu-inner nav.align-top { background: rgb(' . $rgba['r'] . ', ' . $rgba['g'] . ', ' . $rgba['b'] . '); background: rgba(' . $rgba['r'] . ', ' . $rgba['g'] . ', ' . $rgba['b'] . ', ' . get_field('menu_bg_opacity') . '); }';
	}
	
	// menu vertical alignment
	if(get_field('menu_alignment_ver') === 'top') {
		$output_navbar .= '#fullscreen-menu .menu-inner nav { top: 70px; transform: translate(0px, 0px); -webkit-transform: translate(0px, 0px); }';
	}
	
	// menu items alignment
	if(get_field('menu_alignment_hor')) {
		$output_navbar .= '#fullscreen-menu .menu-inner nav ul li { text-align: ' . get_field('menu_alignment_hor') . '; }';
		if(get_field('navbar_fluid') === 'enabled' && get_field('navbar_fluid_gap')) {
			if(get_field('menu_alignment_hor') === 'left') {
				$output_navbar .= '#fullscreen-menu .menu-inner nav .fluid-menu-container li a { left: ' . get_field('navbar_fluid_gap') . '; }';
			} else if(get_field('menu_alignment_hor') === 'right') {
				$output_navbar .= '#fullscreen-menu .menu-inner nav .fluid-menu-container li a { right: ' . get_field('navbar_fluid_gap') . '; }';
			}
		} else if(get_field('navbar_fluid')) {
			if(get_field('menu_alignment_hor') === 'left') {
				$output_navbar .= '#fullscreen-menu .menu-inner nav .fluid-menu-container li a { left: 40px; }';
			} else if(get_field('menu_alignment_hor') === 'right') {
				$output_navbar .= '#fullscreen-menu .menu-inner nav .fluid-menu-container li a { right: 40px; }';
			}
		}
	}

	// social bar bg color and opacity
	if(get_field('social_bar_bg_color') && get_field('social_bar_bg_color') !== 'transparent') {
		$rgba = HexToRGB(get_field('social_bar_bg_color'));
		$output_navbar .= '#fullscreen-menu .menu-inner .follow-links { background: rgb(' . $rgba['r'] . ', ' . $rgba['g'] . ', ' . $rgba['b'] . '); background: rgba(' . $rgba['r'] . ', ' . $rgba['g'] . ', ' . $rgba['b'] . ', ' . get_field('social_bar_bg_color_opacity') . '); }';
	}
	
	// social bar ver padding
	if(get_field('social_bar_ver_padding')) {
		$output_navbar .= '#fullscreen-menu .menu-inner .follow-links ul li a { padding-top: ' . get_field('social_bar_ver_padding') . '; padding-bottom: ' . get_field('social_bar_ver_padding') . '; }';
	}
	
	// social bar border top color
	if(get_field('social_bar_border_top_color')) {
		$output_navbar .= '#fullscreen-menu .menu-inner .follow-links { border-top: 1px solid ' . get_field('social_bar_border_top_color') . ' !important; }';
	}
	
	// social bar hor padding
	if(get_field('social_bar_hor_padding')) {
		$output_navbar .= '#fullscreen-menu .menu-inner .follow-links ul li a { padding-right: ' . get_field('social_bar_hor_padding') . '; padding-left: ' . get_field('social_bar_hor_padding') . '; }';
	}
	
	// social bar link color
	if(get_field('social_bar_link_color')) {
		$output_navbar .= '#fullscreen-menu .menu-inner .follow-links ul li a svg { fill: ' . get_field('social_bar_link_color') . ' !important; }';
	}
	
	// social bar icon hover color
	if(get_field('social_bar_hover_color')) {
		$output_navbar .= '#fullscreen-menu .menu-inner .follow-links ul li a:hover svg { fill: ' . get_field('social_bar_hover_color') . ' !important; }';
	}
	
	// social bar icon hover bg color
	if(get_field('social_bar_hover_bg_color')) {
		$output_navbar .= '#fullscreen-menu .menu-inner .follow-links ul li a:hover { background: ' . get_field('social_bar_hover_bg_color') . ' !important; }';
	}

	// get logo height and define header height
	if (get_field('logo_format') !== 'text') {
		
		if(get_field('logo_format') === 'svg') {
			if(get_field('navbar_padding') === 'custom' && get_field('navbar_padding_custom')) {
				$header_height = get_field('logo_svg_height') + ( get_field('navbar_padding_custom') * 2 );
			} else if(get_field('navbar_padding') === 'fourty') {
				$header_height = get_field('logo_svg_height') + 80; #40 top and 40 bottom padding
			} else {
				$header_height = get_field('logo_svg_height') + 40;
			}
			
			$menu_icon_margin = $header_height / 2 - 12;
			$output_navbar .= '.logo svg { fill: ' . get_field('logo_svg_color') . ' !important; }';
			$output_navbar .= '.logo svg { width: ' . get_field('logo_svg_width') . 'px; height: ' . get_field('logo_svg_height') . 'px; }';
			
		}else if(get_field('logo_format') === 'image') {
			
			$logo = wp_get_attachment_image_src(get_field('logo_img_upload'), 'full');
			
			if(get_field('navbar_padding') === 'custom' && get_field('navbar_padding_custom')) {
				$header_height = $logo[2] + ( get_field('navbar_padding_custom') * 2 );
			} else if(get_field('navbar_padding') === 'fourty') {
				$header_height = $logo[2] + 80;
			} else {
				$header_height = $logo[2] + 40;
			}
			
			$menu_icon_margin = $header_height / 2 - 12;
		}
		
		$output_navbar .= 'header { top: -' . $header_height . 'px; }';
		//$output_navbar .= '#fullscreen-menu { top: ' . $header_height . 'px; }';
		$output_navbar .= '#navbar, #navbar-bg, .controls a, .controls a span { height: ' . $header_height . 'px; }';
		$output_navbar .= '#navbar .controls a .nav-icon { margin-top: ' . (($header_height - 14) / 2 + 7) . 'px !important; }';
		$output_navbar .= '.navbar-inner { height: ' . $header_height . 'px; overflow: hidden; }';
		$output_navbar .= '.controls a svg, div.header-icon { margin-top: ' . round($menu_icon_margin) . 'px; }';
		$output_navbar .= 'section#blog, .post-password-form, section#not-found { margin-top: ' . $header_height . 'px !important; }';
		$output_navbar .= '#fullscreen-menu .menu-inner nav.align-top { margin-top: ' . $header_height . 'px !important; transform: none; -webkit-transform: none; }';
		
		if(get_field('menu_height') === 'full') {
			// menu vertical alignment
			if(get_field('menu_alignment_ver') === 'top') {
				$output_navbar .= '#fullscreen-menu .menu-inner nav { top: ' . $header_height . 'px; transform: translate(0px, 0px); -webkit-transform: translate(0px, 0px); }';
			}
		} else {
			/* margin top for non fullscreen menus (position relative) */
			$output_navbar .= '#fullscreen-menu .menu-inner nav { margin-top: ' . $header_height . 'px !important; }';
		}
		
		// custom navbar padding
		if(get_field('navbar_padding') === 'custom' && get_field('navbar_padding_custom')) {
			$output_navbar .= '#navbar #logo { padding-top: ' . get_field('navbar_padding_custom') . 'px !important; }';
		}
		
		// is fullscreen cover?
		if($cover_visibility !== 'visible' && $vertical_positioning !== 'before') {
			$output_navbar .= '#content-holder, #page-content { margin-top: ' . $header_height . 'px !important; }';
		}
		
		$output_navbar .= '.menu-style-nobutton ul li a, nav.standard ul li a { line-height: ' . $header_height . 'px !important; }';
		$output_navbar .= '.title-top { top: ' . $header_height . 'px; }';
		
	} else {
		smp_text_logo($cover_visibility, $vertical_positioning);
	}
	// output
	echo $output_navbar;
} else {
	
	// set position absolute for mobile devices
	if($detect->isMobile()) {
		$output_css .= '#fullscreen-menu, #project-panel-header { position: absolute; max-height: none; overflow: visible !important; }';
	}
	
	smp_text_logo($cover_visibility, $vertical_positioning);
}
// reset postdata
wp_reset_postdata();

#---------------------------------------------------------------------------#
# Text Logo																	#
#---------------------------------------------------------------------------#

function smp_text_logo($cover_visibility, $vertical_positioning) {
	
	$output_textlogo = '';
	
	if(get_field('logo_text_color')) {
		$output_textlogo .= 'div.logo a { color: ' . get_field('logo_text_color') . ' !important; }';
	}
	$output_textlogo .= 'header { top: -70px; }';
	$output_textlogo .= '#fullscreen-menu { top: 70px; }';
	$output_textlogo .= '#navbar, .controls a, div.loader, div.header-icon, #navbar-bg, .controls a span { height: 70px; }';
	$output_textlogo .= 'div.logo h1 { line-height: 70px !important; }';
	$output_textlogo .= '.controls a svg, div.header-icon { margin-top: 23px !important; }';
	$output_textlogo .= 'section#blog, .post-password-form, section#not-found { margin-top: 70px !important; }';
	// is fullscreen cover?
	if($cover_visibility !== 'visible' && $vertical_positioning !== 'before') {
		$output_textlogo .= '#content-holder, #page-content { margin-top: 70px !important; }';
	}
	$output_textlogo .= '.menu-style-nobutton ul li a, nav.standard ul li a { line-height: 70px !important; }';
	$output_textlogo .= '.title-top { top: 70px; }';
	$output_textlogo .= '.logo svg { margin-top: 24px; }';

	echo $output_textlogo;
}


#---------------------------------------------------------------------------#
# Custom Title Fontsize														#
#---------------------------------------------------------------------------#

function cover_heading_fontsize($font_size, $id) {

	// 1170px
	$output_titlesize = '';
	$output_titlesize .= '.cover-' . $id . ' .cover-headline h1 { font-size: '. $font_size . 'px !important; line-height: '. ($font_size + 15) . 'px !important; }';
	$output_titlesize .= '';
	// 940px
	$output_titlesize .= '@media (min-width: 980px) and (max-width: 1199px) {';
	$output_titlesize .= '.cover-' . $id . ' .cover-headline h1 { font-size: '. ($font_size / 1.5) . 'px !important; line-height: '. ($font_size / 1.3) . 'px !important; }';
	$output_titlesize .= '}';
	// 768-979px
	$output_titlesize .= '@media (min-width: 768px) and (max-width: 979px) {';
	$output_titlesize .= '.cover-' . $id . ' .cover-headline h1 { font-size: '. ($font_size / 1.7) . 'px !important; line-height: '. ($font_size / 1.4) . 'px !important; }';
	$output_titlesize .= '}';
	// 767 (tablet)
	$output_titlesize .= '@media (max-width: 767px) {';
	$output_titlesize .= '.cover-' . $id . ' .cover-headline h1 { font-size: '. ($font_size / 2.6) . 'px !important; line-height: '. ($font_size / 2.2) . 'px !important; }';
	$output_titlesize .= '}';
	// mobile
	$output_titlesize .= '@media (max-width: 567px) {';
	$output_titlesize .= '.cover-' . $id . ' .cover-headline h1 { font-size: '. ($font_size / 3.4) . 'px !important; line-height: '. ($font_size / 3) . 'px !important; }';
	$output_titlesize .= '}';
	
	echo $output_titlesize;
	
}

#---------------------------------------------------------------------------#
# Project Panel																#
#---------------------------------------------------------------------------#

if(get_post_type($post->ID) === 'work'  || get_field('use_semplice') === 'coverslider' || get_field('project_panel_global', 'options') === 'enabled') {
	
	// thumbnav css
	if(get_field('project_panel_background', 'options')) {
		$output_css .= '#project-panel-header, #project-panel-footer, .project-panel { background: ' . get_field('project_panel_background', 'options') . ' !important; }';
	}
	
	if(get_field('project_panel_title_color', 'options')) {
		$output_css .= '.project-panel h3 { color: ' . get_field('project_panel_title_color', 'options') . ' !important; }';
		$output_css .= 'div.close-project-panel svg { stroke: ' . get_field('project_panel_title_color', 'options') . ' !important; }';
	}
	
	if(get_field('project_panel_link_color', 'options')) {
		$output_css .= '.project-panel-thumb h3 { color: ' . get_field('project_panel_link_color', 'options') . ' !important; }';
	}
	
	if(get_field('project_panel_category_color', 'options')) {
		$output_css .= '.project-panel-thumb h3 span { color: ' . get_field('project_panel_category_color', 'options') . ' !important; }';
	}
	
	if(get_field('project_panel_title_visibility', 'options') !== 'visible') {
		$output_css .= '.project-panel-title h3 { opacity: 0; }';
	}
	
	if(get_field('project_panel_visibility', 'options') !== 'visible') {
	
		// get pp visiblity
		$pp_visibility = get_field('project_panel_visibility', 'options');
		
		if($pp_visibility === 'hidden') {
			$output_css .= '#project-panel-header, #project-panel-footer, .project-panel-button { display: none !important; }';
		} elseif($pp_visibility === 'hide-header') {
			$output_css .= '#project-panel-header, .project-panel-button { display: none !important; }';
		} elseif($pp_visibility === 'hide-footer') {
			$output_css .= '#project-panel-footer{ display: none !important; }';
		}
	}
}

#---------------------------------------------------------------------------#
# Sharebox																	#
#---------------------------------------------------------------------------#

if(get_post_type($post->ID) === 'post') {
	$share_options = 'options';
} else if(isset($slider_content_id)) {
	$share_options = $slider_content_id;
} else {
	$share_options = '';
}

if(get_field('share_bg_color', $share_options)) {
	$output_css .= '.share-box { background: ' . get_field('share_bg_color', $share_options) . ' !important; }';
}

// Buttons
if(get_field('share_box_style', $share_options) !== 'icons') {
	// share button bg color
	if(get_field('share_button_bg_color', $share_options)) {
		$output_css .= '.semplice-share .text { background: ' . get_field('share_button_bg_color', $share_options) . ' !important; }';
	}

	// share text color
	if(get_field('share_button_text_color', $share_options)) {
		$output_css .= '.semplice-share .text { color: ' . get_field('share_button_text_color', $share_options) . ' !important; }';
	}

	// share button border color
	if(get_field('share_button_border_color', $share_options)) {
		$output_css .= '.share-box .semplice-share .text { border-color: ' . get_field('share_button_border_color', $share_options) . ' !important; }';
	}
} else {
	// Icons
	if(get_field('share_text_color', $share_options)) {
		$output_css .= '.share-icons-wrapper p { color: ' . get_field('share_text_color', $share_options) . ' !important; }';
	}
	if(get_field('share_icon_color', $share_options)) {
		$output_css .= '.share-icon a svg { fill: ' . get_field('share_icon_color', $share_options) . ' !important; }';
	}
}

#---------------------------------------------------------------------------#
# Theme Options																#
#---------------------------------------------------------------------------#

// default text color
if(get_field('skinoptions_text_color', 'options')) {
	$output_css .= '.wysiwyg, .wysiwyg p, .wysiwyg pre, .post-heading h2, #post .wysiwyg .meta p span, p.quote, .wysiwyg h1, .wysiwyg h2, .wysiwyg h3, .wysiwyg h4, .wysiwyg h5, .wysiwyg h6, #category-archives h4, h4#comments, .comment-autor, .comment-autor a, .comment-time, .comment-content p, h3#reply-title, .comments-pagination, .meta p a, section#comment h3#comments, .no-results, blockquote p, .quote-container p, .result-header h3 { color: ' . get_field('skinoptions_text_color', 'options') . '!important ;}';
	$output_css .= '.archives-close svg { fill: ' . get_field('skinoptions_text_color', 'options') . '!important ;}';
	$output_css .= 'abbr, acronym { border-color: ' . get_field('skinoptions_text_color', 'options') . ' !important;}';
}

// default link color
if(get_field('skinoptions_link_color', 'options')) {
	$output_css .= '#post .wysiwyg  a, .wysiwyg-ce a, #post .wysiwyg p a, p.quote a, .next p a, .previous p a, a.page-numbers, #category-archives nav ul li a, .cover-headline a { color: ' . get_field('skinoptions_link_color', 'options') . '!important ;}';
	$output_css .= '#post .wysiwyg { border-color: ' . get_field('skinoptions_link_color', 'options') . '!important ;}';
	// make share buttons white again 
	$output_css .= '#post .semplice-share .button a { color: white !important; }';
}

// default hover color
if(get_field('skinoptions_hover_color', 'options')) {
	$output_css .= '#post .wysiwyg a:hover, .wysiwyg-ce a:hover, #post .wysiwyg p a:hover, .post-heading p a:hover, #post .wysiwyg .meta p a:hover, p.quote a:hover, #post .wysiwyg a.more-link:hover, .description a.more-link:hover, .next p a:hover, .previous p a:hover, #category-archives nav ul li a:hover, a.comment-edit-link:hover, a.comment-reply-link:hover, a.page-numbers:hover, section#category-archives nav ul li a:hover,  .cover-headline a:hover { color: ' . get_field('skinoptions_hover_color', 'options') . '!important ;}';
	// make share buttons white again 
	$output_css .= '#post .semplice-share .button a:hover { color: white !important; }';
}

// heading color
if (get_field('skinoptions_heading_color', 'options')) {
	$output_css .= '.post-heading h2 a, .result-header h3, #category-archives h2 { color: ' . get_field('skinoptions_heading_color', 'options') . '!important ;}';
}

// default heading subline color
if(get_field('skinoptions_heading_color_subline', 'options')) {
	$output_css .= '.post-heading p, .post-heading p a, #post .wysiwyg .meta p a { color: ' . get_field('skinoptions_heading_color_subline', 'options') . '!important ;}';
}

/*
if(get_field('blog_bg_color', 'options') && get_post_type($post->ID) === 'post') {
	$output_css .= 'body.blog-bg { background-color: ' . get_field('blog_bg_color', 'options') . ' !important;}';
}
*/

// blog comments bg color
if(get_field('blog_comments_bg', 'options')) {
	$output_css .= '#comment { background-color: ' . get_field('blog_comments_bg', 'options') . ' !important;}';
}

// blog comments textarea bg color
if(get_field('blog_comments_input_bg', 'options')) {
	$output_css .= 'form#commentform textarea, form#commentform input { background-color: ' . get_field('blog_comments_input_bg', 'options') . ' !important;}';
}

if(get_field('blog_comments_input_text_color', 'options')) {
	$output_css .= 'form#commentform #submit, form#commentform input, form#commentform textarea { color: ' . get_field('blog_comments_input_text_color', 'options') . ' !important; }';
	$output_css .= 'form#commentform textarea::-webkit-input-placeholder { color: ' . get_field('blog_comments_input_text_color', 'options') . ' !important; opacity: .5 !important;}';
	$output_css .= 'form#commentform textarea::-moz-placeholder { color: ' . get_field('blog_comments_input_text_color', 'options') . ' !important; opacity: .5 !important;}';
	$output_css .= 'form#commentform textarea:-ms-input-placeholder { color: ' . get_field('blog_comments_input_text_color', 'options') . ' !important; opacity: .5 !important;}';
	$output_css .= 'form#commentform textareainput:-moz-placeholder { color: ' . get_field('blog_comments_input_text_color', 'options') . ' !important; opacity: .5 !important;}';
	$output_css .= 'form#commentform input::-webkit-input-placeholder { color: ' . get_field('blog_comments_input_text_color', 'options') . ' !important; opacity: .5 !important;}';
	$output_css .= 'form#commentform input::-moz-placeholder { color: ' . get_field('blog_comments_input_text_color', 'options') . ' !important; opacity: .5 !important;}';
	$output_css .= 'form#commentform input:-ms-input-placeholder { color: ' . get_field('blog_comments_input_text_color', 'options') . ' !important; opacity: .5 !important;}';
	$output_css .= 'form#commentform inputinput:-moz-placeholder { color: ' . get_field('blog_comments_input_text_color', 'options') . ' !important; opacity: .5 !important;}';
}

// blog comments border color
if(get_field('blog_comments_input_border', 'options')) {
	$output_css .= 'form#commentform textarea, form#commentform input { border-color: ' . get_field('blog_comments_input_border', 'options') . ' !important;}';
}

// read more text color
if(get_field('skinoptions_blog_read_more_text_color', 'options')) {
	$output_css .= '#post .wysiwyg a.more-link, .description a.more-link { color: ' . get_field('skinoptions_blog_read_more_text_color', 'options') . ' !important; }';
}

// read more button border color
if(get_field('skinoptions_blog_read_more_border_color', 'options')) {
	$output_css .= '#post .wysiwyg a.more-link, .description a.more-link { border-color: ' . get_field('skinoptions_blog_read_more_border_color', 'options') . '!important ;}';
}

// reply buttons text color
if(get_field('skinoptions_blog_reply_text_color', 'options')) {
	$output_css .= '.edit-reply a { color: ' . get_field('skinoptions_blog_reply_text_color', 'options') . '!important ;}';
}

// reply buttons border color
if(get_field('skinoptions_blog_reply_border_color', 'options')) {
	$output_css .= '.edit-reply a { border-color: ' . get_field('skinoptions_blog_reply_border_color', 'options') . '!important ;}';
}

// post divider color
if (get_field('skinoptions_hr_color', 'options')) {
	$output_css .= '.post-divider { background: ' . get_field('skinoptions_hr_color', 'options') . '!important ;}';
	$output_css .= '.post-password-form input, .result-header, #category-archives h4, .comment-content, pre, tt, code, kbd, blockquote, .wysiwyg table, .description table, .wysiwyg-ce table , .wysiwyg table th, .wysiwyg table td, .description table th, .description table td, .wysiwyg-ce table th, .wysiwyg-ce table td { border-color: ' . get_field('skinoptions_hr_color', 'options') . '!important ;}';
}

// blog searchbox background
if (get_field('skinoptions_blog_search_bg_color', 'options')) {
	$output_css .= '.search-field { background: ' . get_field('skinoptions_blog_search_bg_color', 'options') . '!important ;}';
}

// blog searchbox border-color
if (get_field('skinoptions_blog_search_border_color', 'options')) {
	$output_css .= '.search-field { border-color: ' . get_field('skinoptions_blog_search_border_color', 'options') . '!important ;}';
}

// blog searchbox text-color
if (get_field('skinoptions_blog_search_text_color', 'options')) {
	$output_css .= '.search-field { color: ' . get_field('skinoptions_blog_search_text_color', 'options') . '!important ;}';
}

// blog searchbox placeholder
if (get_field('skinoptions_blog_search_placeholder_color', 'options')) {
	$output_css .= '.close-search svg { fill: ' . get_field('skinoptions_blog_search_placeholder_color', 'options') . ' !important;}';
	$output_css .= '.search-form input::-webkit-input-placeholder { color: ' . get_field('skinoptions_blog_search_placeholder_color', 'options') . ' !important; opacity: 1;}';
	$output_css .= '.search-form input::-moz-placeholder { color: ' . get_field('skinoptions_blog_search_placeholder_color', 'options') . ' !important; opacity: 1;}';
	$output_css .= '.search-form input:-ms-input-placeholder { color: ' . get_field('skinoptions_blog_search_placeholder_color', 'options') . ' !important; opacity: 1;}';
}

#---------------------------------------------------------------------------#
# Skinoptions Body Background												#
#---------------------------------------------------------------------------#

function get_background($prepend, $options) {

	$output_background = '';

	$output_background .= 'body {';
	if(get_field($prepend . '_bg_image', $options)) {
		$output_background .= 'background-image: url(' . get_field($prepend . '_bg_image', $options) . ') !important;';
		$output_background .= 'background-repeat: ' . get_field($prepend . '_bg_image_repeat', $options) . ' !important;';
		if(get_field($prepend . '_bg_image_position', $options)) {
			$output_background .= 'background-position: ' . get_field($prepend . '_bg_image_position', $options) . ' !important;';
		} else {
			$output_background .= 'background-position: top center !important;';
		}
	}
	if(get_field($prepend . '_bg_color', $options)) {
		$output_background .= 'background-color: ' . get_field($prepend . '_bg_color', $options) . ' !important;';
	}
	$output_background .= '}';
	
	echo $output_background;
}

if(get_field('use_semplice') !== 'active' && get_field('use_semplice') !== 'coverslider' && get_post_type($post->ID) !== 'work') {
	get_background('skinoptions', 'options');
}

#---------------------------------------------------------------------------#
# Content Editor Body Background Branding									#
#---------------------------------------------------------------------------#

// is content after slider?
if(isset($slider_content_id)) {
	$post_id = $slider_content_id;
} else {
	$post_id = get_the_ID();
}

$ce_branding = json_decode(get_post_meta( $post_id, 'semplice_ce_branding', true ), true);

if(get_field('use_semplice') === 'active' || get_post_type($post->ID) === 'work' || isset($slider_content_id)) {
	if($ce_branding) {
		$output_css .= 'body {';
		if($ce_branding['background-image']) {			
			$output_css .= 'background-image: url(' . $ce_branding['background-image'] . ') !important;';
			$output_css .= 'background-repeat: ' . $ce_branding['background-repeat'] . ' !important;';
			if($ce_branding['background-size'] === 'cover') {
				$output_css .= 'background-size: cover !important;';	
			} else if($ce_branding['background-repeat'] !== 'no-repeat') {
				$output_css .= 'background-size: auto !important;';
			}
			if($ce_branding['background-position']) {
				$output_css .= 'background-position: ' . $ce_branding['background-position'] . ' !important;';
			} else {
				$output_css .= 'background-position: top center !important;';
			}
			if($ce_branding['background-attachment']) {
				$output_css .= 'background-attachment: ' . $ce_branding['background-attachment'] . ' !important;';
			}
		}
		if($ce_branding['background-color']) {
			$output_css .= 'background-color: ' . $ce_branding['background-color'] . ' !important;';
		}
		$output_css .= '}';
	}
}

#---------------------------------------------------------------------------#
# Other																		#
#---------------------------------------------------------------------------#

if(get_field('top_arrow_color') && get_field('top_arrow_color') !== '#b2b2b2') {
	$output_css .= '.to-the-top a svg { fill: ' . get_field('top_arrow_color') . ' !important; }';
} elseif(get_field('top_arrow_color', 'options')) {
	$output_css .= '.to-the-top a svg { fill: ' . get_field('top_arrow_color', 'options') . ' !important; }';
}

#---------------------------------------------------------------------------#
# Cover Slider																#
#---------------------------------------------------------------------------#

if(get_field('use_semplice') === 'coverslider') {
	if(get_field('vp_button_font_color', 'options')) {
		$output_css .= '.view-project a { color: ' . get_field('vp_button_font_color', 'options') . '; }';
	}
	if(get_field('vp_button_bg_color', 'options')) {
		$output_css .= '.view-project a { background: ' . get_field('vp_button_bg_color', 'options') . '; }';
	}
	if(get_field('vp_button_border_color', 'options')) {
		$output_css .= '.view-project a { border-color: ' . get_field('vp_button_border_color', 'options') . '; }';
	}
	if(get_field('vp_button_hover_font_color', 'options')) {
		$output_css .= '.view-project a:hover { color: ' . get_field('vp_button_hover_font_color', 'options') . '; }';
	}
	if(get_field('vp_button_hover_bg_color', 'options')) {
		$output_css .= '.view-project a:hover { background: ' . get_field('vp_button_hover_bg_color', 'options') . '; }';
	}
	if(get_field('vp_button_hover_border_color', 'options')) {
		$output_css .= '.view-project a:hover { border-color: ' . get_field('vp_button_hover_border_color', 'options') . '; }';
	}
	if(get_field('vp_button_custom_css', 'options')) {
		$output_css .= '.view-project a { ' . get_field('vp_button_custom_css', 'options') . ' }';
	}
	if(get_field('cs_navigation_arrow_color', 'options')) {
		$output_css .= '.fp-hor-nav a svg, .fp-vert-nav a svg { fill: ' . get_field('cs_navigation_arrow_color', 'options') . ' !important; }';
	}
	if(get_field('cs_navigation_dots_bg_color', 'options')) {
		$rgba = HexToRGB(get_field('cs_navigation_dots_bg_color', 'options'));
		$output_css .= '#fp-nav ul li a span, .fp-slidesNav ul li a span { background: rgba(' . $rgba['r'] . ', ' . $rgba['g'] . ', ' . $rgba['b'] . ', ' . get_field('cs_navigation_dots_bg_color_opacity', 'options') . '); }';
	}
	if(get_field('cs_navigation_dots_bg_color_active', 'options')) {
		$output_css .= '#fp-nav ul li a.active span, .fp-slidesNav ul li a.active span { background: ' . get_field('cs_navigation_dots_bg_color_active', 'options') . '; }';
	}
}

#---------------------------------------------------------------------------#
# No Site Transitions														#
#---------------------------------------------------------------------------#

if(get_field('site_transitions', 'options') === 'disabled') {

	// headers
	$output_css .= 'header { top: 0px !important; opacity: 1; }';
	$output_css .= '.fade-content, #project-panel-footer, #blog { opacity: 1 }';
	
}

#---------------------------------------------------------------------------#
# masonry no gutter percentage grid											#
#---------------------------------------------------------------------------#

$output_css .= '.no-gutter-grid-sizer { width: 8.3333% !important; }';
$output_css .= '.no-gutter-gutter-sizer { width: 0px; }';
$output_css .= '.remove-gutter-yes { margin: 0px !important; }';

for($i=0; $i<=12; $i++) {
	$breite = 100 / 12 * $i;
	$breite = round($breite, 4);
	$output_css .= '.masonry-span' . $i . '{ width: ' . $breite . '% !important; float: left; }';
}

// masonry tablet
$output_css .= '@media (max-width: 767px) {';
$output_css .= '.masonry-span1, .masonry-span2, .masonry-span3, .masonry-span4, .masonry-span5, .masonry-span6, .masonry-span7, .masonry-span8, .masonry-span9, .masonry-span10, .masonry-span11, .masonry-span12 { width: 100% !important; }';
$output_css .= '}';

#---------------------------------------------------------------------------#
# lightbox fadein															#
#---------------------------------------------------------------------------#

if(get_field('lightbox_overlay_bg_color', 'options')) {
	$rgba = HexToRGB(get_field('lightbox_overlay_bg_color', 'options'));
	$overlay_opacity = get_field('lightbox_overlay_opacity', 'options');
} else {
	$rgba = HexToRGB('#ffffff');
	$overlay_opacity = '0.98';
}	

$output_css .= '.lightbox-overlay { background-color: rgba(' . $rgba['r'] . ',' . $rgba['g'] . ',' . $rgba['b'] . ',' . $overlay_opacity . '); -webkit-animation: fadein 0.35s; -moz-animation: fadein 0.35s; -ms-animation: fadein 0.35s; -o-animation: fadein 0.35s; animation: fadein 0.35s; }';
$output_css .= '@keyframes fadein { from { background-color: rgba(' . $rgba['r'] . ',' . $rgba['g'] . ',' . $rgba['b'] . ',0); } to { background-color: rgba(' . $rgba['r'] . ',' . $rgba['g'] . ',' . $rgba['b'] . ',' . $overlay_opacity . '); } }';
$output_css .= '@-moz-keyframes fadein { from { background-color: rgba(' . $rgba['r'] . ',' . $rgba['g'] . ',' . $rgba['b'] . ',0); } to { background-color: rgba(' . $rgba['r'] . ',' . $rgba['g'] . ',' . $rgba['b'] . ',' . $overlay_opacity . '); } }';
$output_css .= '@-webkit-keyframes fadein { from { background-color: rgba(' . $rgba['r'] . ',' . $rgba['g'] . ',' . $rgba['b'] . ',0); } to { background-color: rgba(' . $rgba['r'] . ',' . $rgba['g'] . ',' . $rgba['b'] . ',' . $overlay_opacity . '); } }';
$output_css .= '@-ms-keyframes fadein { from { background-color: rgba(' . $rgba['r'] . ',' . $rgba['g'] . ',' . $rgba['b'] . ',0); } to { background-color: rgba(' . $rgba['r'] . ',' . $rgba['g'] . ',' . $rgba['b'] . ',' . $overlay_opacity . '); } }';
$output_css .= '@-o-keyframes fadein { from { background-color: rgba(' . $rgba['r'] . ',' . $rgba['g'] . ',' . $rgba['b'] . ',0); } to { background-color: rgba(' . $rgba['r'] . ',' . $rgba['g'] . ',' . $rgba['b'] . ',' . $overlay_opacity . '); } }';

// lightbox arrow color
if(get_field('lightbox_arrow_color', 'options')) {
	$output_css .= '.lightbox-arrows .imagelightbox-arrow svg { fill: ' . get_field('lightbox_arrow_color', 'options') .  '; }';
}

#---------------------------------------------------------------------------#
# Custom CSS																#
#---------------------------------------------------------------------------#

if(get_field('skinoptions_custom_css', 'options')) {
	$output_css .= get_field('skinoptions_custom_css', 'options');
}

if(get_field('pb_custom_css')) {
	$output_css .= get_field('pb_custom_css');
}

if(get_field('page_custom_css')) {
	$output_css .= get_field('page_custom_css');
}

#---------------------------------------------------------------------------#
# Responsive Custom CSS 													#
#---------------------------------------------------------------------------#

// desktop
if(get_field('skinoptions_custom_css_desktop_only', 'options')) {
	$output_css .= '
		@media screen and (min-width: 1200px) {
			' . get_field('skinoptions_custom_css_desktop_only', 'options') . '
		}
	';
}

// tablet landscape
if(get_field('skinoptions_custom_css_tablet_landscape', 'options')) {
	$output_css .= '
		@media (min-width: 980px) and (max-width: 1199px) {
			' . get_field('skinoptions_custom_css_tablet_landscape', 'options') . '
		}
	';
}

// tablet portrait
if(get_field('skinoptions_custom_css_tablet_portrait', 'options')) {
	$output_css .= '
		@media (min-width: 768px) and (max-width: 979px) {
			' . get_field('skinoptions_custom_css_tablet_portrait', 'options') . '
		}
	';
}

// mobile landscape
if(get_field('skinoptions_custom_css_mobile_landscape', 'options')) {
	$output_css .= '
		@media (max-width: 767px) {
			' . get_field('skinoptions_custom_css_mobile_landscape', 'options') . '
		}
	';
}

// mobile portrait
if(get_field('skinoptions_custom_css_mobile_portrait', 'options')) {
	$output_css .= '
		@media (max-width: 567px) {
			' . get_field('skinoptions_custom_css_mobile_portrait', 'options') . '
		}
	';
}


// output css
echo $output_css;

// semplice, out
?>