<?php
/* WPBakery Page Builder support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('muji_vc_theme_setup9')) {
	add_action( 'after_setup_theme', 'muji_vc_theme_setup9', 9 );
	function muji_vc_theme_setup9() {
		
		add_filter( 'muji_filter_merge_styles',		'muji_vc_merge_styles' );

		if (muji_exists_visual_composer()) {
	
			// Add/Remove params in the standard VC shortcodes
			//-----------------------------------------------------
			add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,	'muji_vc_add_params_classes', 10, 3 );
			add_filter( 'vc_iconpicker-type-fontawesome',	'muji_vc_iconpicker_type_fontawesome' );
			
			// Color scheme
			$scheme = array(
				"param_name" => "scheme",
				"heading" => esc_html__("Color scheme", 'muji'),
				"description" => wp_kses_data( __("Select color scheme to decorate this block", 'muji') ),
				"group" => esc_html__('Colors', 'muji'),
				"admin_label" => true,
				"value" => array_flip(muji_get_list_schemes(true)),
				"type" => "dropdown"
			);
			$sc_list = apply_filters('muji_filter_add_scheme_in_vc', array('vc_section', 'vc_row', 'vc_row_inner', 'vc_column', 'vc_column_inner', 'vc_column_text'));
			foreach ($sc_list as $sc)
				vc_add_param($sc, $scheme);
		}
		if (is_admin()) {
			add_filter( 'muji_filter_tgmpa_required_plugins', 'muji_vc_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'muji_vc_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('muji_filter_tgmpa_required_plugins',	'muji_vc_tgmpa_required_plugins');
	function muji_vc_tgmpa_required_plugins($list=array()) {
		if (muji_storage_isset('required_plugins', 'js_composer')) {
			$path = muji_get_file_dir('plugins/js_composer/js_composer.zip');
			if (!empty($path) || muji_get_theme_setting('tgmpa_upload')) {
				$list[] = array(
					'name' 		=> muji_storage_get_array('required_plugins', 'js_composer'),
					'slug' 		=> 'js_composer',
					'version'	=> '6.0.5',
					'source'	=> !empty($path) ? $path : 'upload://js_composer.zip',
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Check if WPBakery Page Builder installed and activated
if ( !function_exists( 'muji_exists_visual_composer' ) ) {
	function muji_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if WPBakery Page Builder in frontend editor mode
if ( !function_exists( 'muji_vc_is_frontend' ) ) {
	function muji_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
	}
}
	
// Merge custom styles
if ( !function_exists( 'muji_vc_merge_styles' ) ) {
	//Handler of the add_filter('muji_filter_merge_styles', 'muji_vc_merge_styles');
	function muji_vc_merge_styles($list) {
		if (muji_exists_visual_composer()) {
			$list[] = 'plugins/js_composer/_js_composer.scss';
		}
		return $list;
	}
}

// Merge responsive styles
if ( !function_exists( 'muji_vc_merge_styles_responsive' ) ) {
	//Handler of the add_filter('muji_filter_merge_styles_responsive', 'muji_vc_merge_styles_responsive');
	function muji_vc_merge_styles_responsive($list) {
		if (muji_exists_visual_composer()) {
			$list[] = 'plugins/js_composer/_js_composer-responsive.scss';
		}
		return $list;
	}
}



// Shortcodes support
//------------------------------------------------------------------------

// Add params to the standard VC shortcodes
if ( !function_exists( 'muji_vc_add_params_classes' ) ) {
	//Handler of the add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'muji_vc_add_params_classes', 10, 3 );
	function muji_vc_add_params_classes($classes, $sc, $atts) {
		// Add color scheme
		if (in_array($sc, apply_filters('muji_filter_add_scheme_in_vc', array('vc_section', 'vc_row', 'vc_row_inner', 'vc_column', 'vc_column_inner', 'vc_column_text')))) {
			if (!empty($atts['scheme']) && !muji_is_inherit($atts['scheme']))
				$classes .= ($classes ? ' ' : '') . 'scheme_' . $atts['scheme'];
		}
		return $classes;
	}
}
	
// Add theme icons to the VC iconpicker list
if ( !function_exists( 'muji_vc_iconpicker_type_fontawesome' ) ) {
	//Handler of the add_filter( 'vc_iconpicker-type-fontawesome',	'muji_vc_iconpicker_type_fontawesome' );
	function muji_vc_iconpicker_type_fontawesome($icons) {
		$list = muji_get_list_icons();
		if (!is_array($list) || count($list) == 0) return $icons;
		$rez = array();
		foreach ($list as $icon)
			$rez[] = array($icon => str_replace('icon-', '', $icon));
		return array_merge( $icons, array(esc_html__('Theme Icons', 'muji') => $rez) );
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (muji_exists_visual_composer()) { require_once MUJI_THEME_DIR . 'plugins/js_composer/js_composer-styles.php'; }
?>