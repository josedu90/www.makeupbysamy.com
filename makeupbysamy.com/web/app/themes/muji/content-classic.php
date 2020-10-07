<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

$muji_template_args = get_query_var( 'muji_template_args' );
if ( is_array( $muji_template_args ) ) {
	$muji_columns    = empty( $muji_template_args['columns'] ) ? 2 : max( 1, $muji_template_args['columns'] );
	$muji_blog_style = array( $muji_template_args['type'], $muji_columns );
} else {
	$muji_blog_style = explode( '_', muji_get_theme_option( 'blog_style' ) );
	$muji_columns    = empty( $muji_blog_style[1] ) ? 2 : max( 1, $muji_blog_style[1] );
}
$muji_expanded = !muji_sidebar_present() && muji_is_on(muji_get_theme_option('expand_content'));
$muji_post_format = get_post_format();
$muji_post_format = empty($muji_post_format) ? 'standard' : str_replace('post-format-', '', $muji_post_format);
$muji_animation = muji_get_theme_option('blog_animation');
$muji_components = muji_array_get_keys_by_value(muji_get_theme_option('meta_parts'));
$muji_counters = muji_array_get_keys_by_value(muji_get_theme_option('counters'));

?><div class="<?php
if ( ! empty( $muji_template_args['slider'] ) ) {
	echo ' slider-slide swiper-slide';
} else {
	echo ( 'classic' == $muji_blog_style[0] ? 'column' : 'masonry_item masonry_item' ) . '-1_' . esc_attr( $muji_columns );
}
?>"><article id="post-<?php the_ID(); ?>"
	<?php post_class( 'post_item post_format_'.esc_attr($muji_post_format)
					. ' post_layout_classic post_layout_classic_'.esc_attr($muji_columns)
					. ' post_layout_'.esc_attr($muji_blog_style[0]) 
					. ' post_layout_'.esc_attr($muji_blog_style[0]).'_'.esc_attr($muji_columns)
					); ?>
	<?php echo (!muji_is_off($muji_animation) ? ' data-animation="'.esc_attr(muji_get_animation_classes($muji_animation)).'"' : ''); ?>>
	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	muji_show_post_featured(
		array(
			'thumb_size' => muji_get_thumb_size(
				$muji_blog_style[0] == 'classic'
					? (strpos(muji_get_theme_option('body_style'), 'full')!==false
					? ( $muji_columns > 2 ? 'big' : 'huge' )
					: (	$muji_columns > 2
						? ($muji_expanded ? 'med' : 'small')
						: ($muji_expanded ? 'big' : 'med')
					)
				)
					: (strpos(muji_get_theme_option('body_style'), 'full')!==false
					? ( $muji_columns > 2 ? 'masonry-big' : 'full' )
					: (	$muji_columns <= 2 && $muji_expanded ? 'masonry-big' : 'masonry')
				)
			)
		)
	);


	if ( !in_array($muji_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<div class="cat-block"><?php

				do_action('muji_action_before_post_meta');

				// Post meta
				if (!empty($muji_components))
					muji_show_post_meta(apply_filters('muji_filter_post_meta_args', array(
							'components' => $muji_components,
							'counters' => $muji_counters,
							'seo' => false
						), $muji_blog_style[0], $muji_columns)
					);

				do_action('muji_action_after_post_meta');

				?>
			</div>
			<?php
			do_action('muji_action_before_post_title');

			// Post title
			the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );

			do_action('muji_action_before_post_meta');

			// Post meta
			if (!empty($muji_components))
				muji_show_post_meta(apply_filters('muji_filter_post_meta_args', array(
						'components' => $muji_components,
						'counters' => $muji_counters,
						'seo' => false
					), $muji_blog_style[0], $muji_columns)
				);

			do_action('muji_action_after_post_meta');
			?>
		</div><!-- .entry-header -->
		<?php
	}		
	?>

	<div class="post_content entry-content">
		<div class="post_content_inner">
			<?php
			$muji_show_learn_more = false;
			if (has_excerpt()) {
				the_excerpt();
			} else if (strpos(get_the_content('!--more'), '!--more')!==false) {
				the_content( '' );
			} else if (in_array($muji_post_format, array('link', 'aside', 'status'))) {
				the_content();
			} else if ($muji_post_format == 'quote') {
				if (($quote = muji_get_tag(get_the_content(), '<blockquote>', '</blockquote>'))!='')
					muji_show_layout(wpautop($quote));
				else
					the_excerpt();
			} else if (substr(get_the_content(), 0, 4)!='[vc_') {
				the_excerpt();
			}
			?>
		</div>
		<?php
		// Post meta
		if (in_array($muji_post_format, array('link', 'aside', 'status', 'quote'))) {
			if (!empty($muji_components))
				muji_show_post_meta(apply_filters('muji_filter_post_meta_args', array(
					'components' => $muji_components,
					'counters' => $muji_counters
					), $muji_blog_style[0], $muji_columns)
				);
		}
		// More button
		if ( $muji_show_learn_more ) {
			?><p><a class="more-link" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Read more', 'muji'); ?></a></p><?php
		}
		?>
	</div><!-- .entry-content -->

</article></div>