<?php
/**
 * The default template to display the content of the single post, page or attachment
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

$muji_seo = muji_is_on(muji_get_theme_option('seo_snippets'));
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post_item_single post_type_'.esc_attr(get_post_type()) 
												. ' post_format_'.esc_attr(str_replace('post-format-', '', get_post_format())) 
												);
		if ($muji_seo) {
			?> itemscope="itemscope" 
			   itemprop="articleBody" 
			   itemtype="http://schema.org/<?php echo esc_attr(muji_get_markup_schema()); ?>" 
			   itemid="<?php echo esc_url(get_the_permalink()); ?>"
			   content="<?php echo esc_attr(get_the_title()); ?>"<?php
		}
?>><?php

	do_action('muji_action_before_post_data'); 

	// Structured data snippets
	if ($muji_seo)
		get_template_part('templates/seo');

	// Featured image
	if ( muji_is_off(muji_get_theme_option('hide_featured_on_single'))
			&& !muji_sc_layouts_showed('featured') 
			&& strpos(get_the_content(), '[trx_widget_banner]')===false) {
		do_action('muji_action_before_post_featured'); 
		muji_show_post_featured(array( 'thumb_size' => muji_get_thumb_size( strpos(muji_get_theme_option('body_style'), 'full')!==false ? 'full' : 'big' ) ));
		do_action('muji_action_after_post_featured'); 
	} else if (has_post_thumbnail()) {
		?><meta itemprop="image" itemtype="http://schema.org/ImageObject" content="<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id())); ?>"><?php
	}

	// Title and post meta
	if ( (!muji_sc_layouts_showed('title') || !muji_sc_layouts_showed('postmeta')) && !in_array(get_post_format(), array('link', 'aside', 'status', 'quote')) ) {
		do_action('muji_action_before_post_title'); 
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			if (!muji_sc_layouts_showed('title')) {
				the_title( '<h3 class="post_title entry-title"'.($muji_seo ? ' itemprop="headline"' : '').'>', '</h3>' );
			}
			// Post meta
			if (!muji_sc_layouts_showed('postmeta') && muji_is_on(muji_get_theme_option('show_post_meta'))) {
				muji_show_post_meta(apply_filters('muji_filter_post_meta_args', array(
					'components' => muji_array_get_keys_by_value(muji_get_theme_option('meta_parts')),
					'counters' => muji_array_get_keys_by_value(muji_get_theme_option('counters')),
					'seo' => muji_is_on(muji_get_theme_option('seo_snippets'))
					), 'single', 1)
				);
			}
			?>
		</div><!-- .post_header -->
		<?php
		do_action('muji_action_after_post_title'); 
	}

	do_action('muji_action_before_post_content'); 

	// Post content
	?>
	<div class="post_content entry-content" itemprop="mainEntityOfPage">
		<?php
		the_content( );

		do_action('muji_action_before_post_pagination'); 

		wp_link_pages( array(
			'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'muji' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'muji' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) );

		// Taxonomies and share
		if ( is_single() && !is_attachment() ) {
			
			do_action('muji_action_before_post_meta'); 

			?><div class="post_meta post_meta_single"><?php

			// Share
			if (muji_is_on(muji_get_theme_option('show_share_links'))) {
				muji_show_share_links(array(
					'type' => 'block',
					'caption' => 'Share:',
					'before' => '<span class="post_meta_item post_share">',
					'after' => '</span>'
				));
			}

			// Post meta
			if (!muji_sc_layouts_showed('postmeta') && muji_is_on(muji_get_theme_option('show_post_meta'))) {
				muji_show_post_meta(apply_filters('muji_filter_post_meta_args', array(
						'components' => muji_array_get_keys_by_value(muji_get_theme_option('meta_parts')),
						'counters' => muji_array_get_keys_by_value(muji_get_theme_option('counters')),
						'seo' => muji_is_on(muji_get_theme_option('seo_snippets'))
					), 'single', 1)
				);
			}

				// Post taxonomies
				the_tags( '<span class="post_meta_item post_tags"><span class="post_meta_label">'.esc_html__('Tags:', 'muji').'</span> ', ', ', '</span>' );


			?></div><?php

			do_action('muji_action_after_post_meta'); 
		}
		?>
	</div><!-- .entry-content -->
	

	<?php
	do_action('muji_action_after_post_content'); 

	// Author bio.
	if ( muji_get_theme_option('show_author_info')==1 && is_single() && !is_attachment() && get_the_author_meta( 'description' ) ) {
		do_action('muji_action_before_post_author'); 
		get_template_part( 'templates/author-bio' );
		do_action('muji_action_after_post_author'); 
	}

	do_action('muji_action_after_post_data'); 
	?>
</article>
