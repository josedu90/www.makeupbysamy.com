<div class="front_page_section front_page_section_blog<?php
			$muji_scheme = muji_get_theme_option('front_page_blog_scheme');
			if (!muji_is_inherit($muji_scheme)) echo ' scheme_'.esc_attr($muji_scheme);
			echo ' front_page_section_paddings_'.esc_attr(muji_get_theme_option('front_page_blog_paddings'));
		?>"<?php
		$muji_css = '';
		$muji_bg_image = muji_get_theme_option('front_page_blog_bg_image');
		if (!empty($muji_bg_image)) 
			$muji_css .= 'background-image: url('.esc_url(muji_get_attachment_url($muji_bg_image)).');';
		if (!empty($muji_css))
			echo ' style="' . esc_attr($muji_css) . '"';
?>><?php
	// Add anchor
	$muji_anchor_icon = muji_get_theme_option('front_page_blog_anchor_icon');	
	$muji_anchor_text = muji_get_theme_option('front_page_blog_anchor_text');	
	if ((!empty($muji_anchor_icon) || !empty($muji_anchor_text)) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="front_page_section_blog"'
										. (!empty($muji_anchor_icon) ? ' icon="'.esc_attr($muji_anchor_icon).'"' : '')
										. (!empty($muji_anchor_text) ? ' title="'.esc_attr($muji_anchor_text).'"' : '')
										. ']');
	}
	?>
	<div class="front_page_section_inner front_page_section_blog_inner<?php
			if (muji_get_theme_option('front_page_blog_fullheight'))
				echo ' muji-full-height sc_layouts_flex sc_layouts_columns_middle';
			?>"<?php
			$muji_css = '';
			$muji_bg_mask = muji_get_theme_option('front_page_blog_bg_mask');
			$muji_bg_color = muji_get_theme_option('front_page_blog_bg_color');
			if (!empty($muji_bg_color) && $muji_bg_mask > 0)
				$muji_css .= 'background-color: '.esc_attr($muji_bg_mask==1
																	? $muji_bg_color
																	: muji_hex2rgba($muji_bg_color, $muji_bg_mask)
																).';';
			if (!empty($muji_css))
				echo ' style="' . esc_attr($muji_css) . '"';
	?>>
		<div class="front_page_section_content_wrap front_page_section_blog_content_wrap content_wrap">
			<?php
			// Caption
			$muji_caption = muji_get_theme_option('front_page_blog_caption');
			if (!empty($muji_caption) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><h2 class="front_page_section_caption front_page_section_blog_caption front_page_block_<?php echo !empty($muji_caption) ? 'filled' : 'empty'; ?>"><?php echo wp_kses_post($muji_caption); ?></h2><?php
			}
		
			// Description (text)
			$muji_description = muji_get_theme_option('front_page_blog_description');
			if (!empty($muji_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><div class="front_page_section_description front_page_section_blog_description front_page_block_<?php echo !empty($muji_description) ? 'filled' : 'empty'; ?>"><?php echo wp_kses_post(wpautop($muji_description)); ?></div><?php
			}
		
			// Content (widgets)
			?><div class="front_page_section_output front_page_section_blog_output"><?php 
				if (is_active_sidebar('front_page_blog_widgets')) {
					dynamic_sidebar( 'front_page_blog_widgets' );
				} else if (current_user_can( 'edit_theme_options' )) {
					if (!muji_exists_trx_addons())
						muji_customizer_need_trx_addons_message();
					else
						muji_customizer_need_widgets_message('front_page_blog_caption', 'ThemeREX Addons - Blogger');
				}
			?></div>
		</div>
	</div>
</div>