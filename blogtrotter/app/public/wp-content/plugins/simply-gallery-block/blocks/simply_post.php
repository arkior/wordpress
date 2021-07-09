<?php
if (!defined('ABSPATH')) {
	exit;
}
function pgc_sgb_shortcode_render($atts, $content = null)
{
	if (!is_array($atts) && !isset($atts['id'])) {
		return '';
	}
	$post_id = intval($atts['id']);
	$postData = get_post_field('post_content', $post_id, 'raw');
	if ($postData === '' || is_wp_error($postData)) return '';
	$blocks = parse_blocks($postData);
	$output = '';

	foreach ($blocks as $block) {
		$output .= render_block($block);
	}
	$priority = has_filter('the_content', 'wpautop');
	if (false !== $priority && doing_filter('the_content') && has_blocks($content)) {
		remove_filter('the_content', 'wpautop', $priority);
		add_filter('the_content', '_restore_wpautop_hook', $priority + 1);
	}
	return $output;
}
/** SimpLy Galleries Block */
function pgc_sgb_render_post_blocks_callback($atr, $content)
{
	if (isset($atr['galleryId'])) {
		$shortcode = '[' . PGC_SGB_POST_TYPE . ' id="' . $atr['galleryId'] . '"]';
		return do_shortcode($shortcode);
	}
	return '<div>SIMPLY GALLERY NOT AVAILABLE</div>';
}
function pgc_sgb_register_posts_block()
{
	wp_register_style(
		PGC_SGB_SLUG . '-post-blocks-style',
		PGC_SGB_URL . 'blocks/dist/blocks/blocks.build.style.css',
		array('code-editor'),
		PGC_SGB_VERSION
	);
	wp_register_script(
		PGC_SGB_SLUG . '-post-blocks-script',
		PGC_SGB_URL . 'blocks/dist/blocks/blocks.build.js',
		array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-data', 'code-editor'),
		PGC_SGB_VERSION,
		false
	);
	register_block_type(
		'pgcsimplygalleryblock/galleries',
		array(
			'editor_script' => PGC_SGB_SLUG . '-post-blocks-script',
			'editor_style'  => PGC_SGB_SLUG . '-post-blocks-style',
			'render_callback' => 'pgc_sgb_render_post_blocks_callback'
		)
	);
	/** Main Blocks Translatrion */
	if (function_exists('wp_set_script_translations')) {
		wp_set_script_translations(PGC_SGB_SLUG . '-post-blocks-script', 'simply-gallery-block', PGC_SGB_URL . 'languages');
	}
}
/** Plug Editor And Meta Box Render */
function pgc_sgb_post_enqueue_scripts()
{
	global $pgc_sgb_skins_list;
	$screen = get_current_screen();
	if (PGC_SGB_POST_TYPE === $screen->post_type) {
		if ('post' === $screen->base) {
			wp_register_style(
				PGC_SGB_SLUG . '-post-edit-style',
				PGC_SGB_URL . 'blocks/dist/post.editor.build.style.css',
				array('wp-components'),
				PGC_SGB_VERSION
			);
			wp_enqueue_style(PGC_SGB_SLUG . '-post-edit-style');

			/** Parser */
			wp_register_script(
				PGC_SGB_SLUG . '-post-editor-script',
				PGC_SGB_URL . 'blocks/dist/post.editor.build.js',
				array('wp-plugins', 'wp-edit-post', 'wp-element', 'wp-i18n', 'wp-components', 'wp-data'),
				PGC_SGB_VERSION,
				true
			);
			wp_enqueue_script(PGC_SGB_SLUG . '-post-editor-script');
			$globalJS = array(
				// 'assets' => PGC_SGB_URL.'assets/',
				// 'ajaxurl'   => admin_url('admin-ajax.php'),
				// 'nonce' => wp_create_nonce('pgc-sgb-nonce'),
				'postType' => PGC_SGB_POST_TYPE,
				'skinsList' => $pgc_sgb_skins_list,
			);
			wp_localize_script(
				PGC_SGB_SLUG . '-post-editor-script',
				'PGC_SGB_POST',
				$globalJS
			);
			if (function_exists('wp_set_script_translations')) {
				wp_set_script_translations(PGC_SGB_SLUG . '-post-editor-script', 'simply-gallery-block', PGC_SGB_URL . 'languages');
			}
		} else if ('edit' === $screen->base) {
			wp_enqueue_style(
				PGC_SGB_SLUG . '-post-editor-halper-style',
				PGC_SGB_URL . 'blocks/dist/post.editor.helper.build.style.css',
				array(),
				PGC_SGB_VERSION
			);
			wp_enqueue_script(
				PGC_SGB_SLUG . '-post-editor-halper-script',
				PGC_SGB_URL . 'blocks/dist/post.editor.helper.build.js',
				array(),
				PGC_SGB_VERSION,
				true
			);
		}
	}
}
function pgc_sgb_register_post_type()
{
	$tax_labels = array(
		'name'              => __('SimpLy Categories', 'simply-gallery-block'),
		'singular_name'     => __('Category', 'simply-gallery-block'),
		'search_items'      => __('Search Categories', 'simply-gallery-block'),
		'all_items'         => __('All Categories', 'simply-gallery-block'),
		'view_item '        => __('View Category', 'simply-gallery-block'),
		'parent_item'       => __('Parent Category', 'simply-gallery-block'),
		'parent_item_colon' => __('Parent Category:', 'simply-gallery-block'),
		'edit_item'         => __('Edit Category', 'simply-gallery-block'),
		'update_item'       => __('Update Category', 'simply-gallery-block'),
		'add_new_item'      => __('Add New Category', 'simply-gallery-block'),
		'new_item_name'     => __('New Category Name', 'simply-gallery-block'),
		'menu_name'         => __('Categories', 'simply-gallery-block'),
	);
	register_taxonomy(
		PGC_SGB_TAXONOMY,
		PGC_SGB_POST_TYPE,
		array(
			'hierarchical'      => true,
			'label'             => $tax_labels['name'],
			'singular_name'     => $tax_labels['name'],
			'labels'            => $tax_labels,
			'hierarchical'          => true,
			'public'                => true,
			'publicly_queryable'    => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => false,
			'show_in_rest'          => true,
			'show_tagcloud'         => false,
			'show_in_quick_edit'    => false,
			'show_admin_column'     => true,
			'rewrite'               => false,
			'capability_type'     	=> PGC_SGB_POST_TYPE,
		)
	);
	register_post_type(
		PGC_SGB_POST_TYPE,
		array(
			'labels'              => array(
				'name'                  => _x('SimpLy Galleries', 'Post Type General Name', 'imply-gallery-block'),
				'singular_name'         => _x('SimpLy Gallery', 'simply-gallery-block'),
				'menu_name'             => __('SimpLy Galleries', 'simply-gallery-block'),
				'add_new'               => __('Add New', 'simply-gallery-block'),
				'add_new_item'          => __('Add New SimpLy Gallery', 'simply-gallery-block'),
				'edit_item'             => __('Edit Gallery', 'simply-gallery-block'),
				'view_item'             => __('View SimpLy Gallery', 'simply-gallery-block'),
				'search_items'          => __('Search SimpLy Gallery', 'simply-gallery-block'),
				'not_found'             => __('No Galleries Found', 'simply-gallery-block'),
				'not_found_in_trash'    => __('No Galleries Found in Trash', 'simply-gallery-block'),
				'parent_item_colon'     => __('Parent Gallery', 'simply-gallery-block'),
				'filter_items_list'     => __('Filter Galleries list', 'simply-gallery-block'),
				'items_list_navigation' => __('Galleries list navigation', 'simply-gallery-block'),
				'items_list'            => __('Galleries list', 'simply-gallery-block')
			),
			'public'              => true,
			'publicly_queryable'  => true,
			'with_front'          => true,
			'hierarchical'        => true,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'query_var'           => true,
			'can_export'          => true,
			'capability_type'     => 'page',
			'show_in_menu'        => false,
			'menu_position'       => 12,
			'menu_icon'						=> 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjxzdmcgIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDI0IDI0OyIgdmVyc2lvbj0iMS4xIiB4bWw6c3BhY2U9InByZXNlcnZlIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iMjJweCIgaGVpZ2h0PSIyMnB4IiB2aWV3Qm94PSIwIDAgMjk4LjczIDI5OC43MyIgZmlsbD0iIzAwODViYSIgZmlsbC1ydWxlPSJub256ZXJvIj48ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgZD0iTTI2NC45NTksOS4zNUgzMy43ODdDMTUuMTUzLDkuMzUsMCwyNC40OTgsMCw0My4xNTR2MjEyLjQ2MWMwLDE4LjYzNCwxNS4xNTMsMzMuNzY2LDMzLjc4NywzMy43NjYgICBoMjMxLjE3MWMxOC42MzQsMCwzMy43NzEtMTUuMTMyLDMzLjc3MS0zMy43NjZWNDMuMTU0QzI5OC43MywyNC40OTgsMjgzLjU5Myw5LjM1LDI2NC45NTksOS4zNXogTTE5My4xNzQsNTkuNjIzICAgYzE4LjAyLDAsMzIuNjM0LDE0LjYxNSwzMi42MzQsMzIuNjM0cy0xNC42MTUsMzIuNjM0LTMyLjYzNCwzMi42MzRjLTE4LjAyNSwwLTMyLjYzNC0xNC42MTUtMzIuNjM0LTMyLjYzNCAgIFMxNzUuMTQ5LDU5LjYyMywxOTMuMTc0LDU5LjYyM3ogTTI1NC4zNjMsMjU4LjE0OUgxNDkuMzYySDQ5LjAzOWMtOS4wMTMsMC0xMy4wMjctNi41MjEtOC45NjQtMTQuNTY2bDU2LjAwNi0xMTAuOTMgICBjNC4wNTgtOC4wNDQsMTEuNzkyLTguNzYyLDE3LjI2OS0xLjYwNWw1Ni4zMTYsNzMuNTk2YzUuNDc3LDcuMTU4LDE1LjA1LDcuNzY3LDIxLjM4NiwxLjM1NGwxMy43NzctMTMuOTUxICAgYzYuMzMxLTYuNDEzLDE1LjY1OS01LjYxOSwyMC44MjYsMS43NjJsMzUuNjc1LDUwLjk1OUMyNjYuNDg3LDI1Mi4xNiwyNjMuMzc2LDI1OC4xNDksMjU0LjM2MywyNTguMTQ5eiI+PC9wYXRoPjwvZz48L3N2Zz4=',
			'show_ui'             => true,
			'show_in_rest'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'rewrite'             => false,
			'supports'            => array(
				'title',
				'thumbnail',
				'editor',
			),
			'taxonomies' => array(PGC_SGB_TAXONOMY),
		)
	);
	add_shortcode('pgc_simply_gallery', 'pgc_sgb_shortcode_render');

	pgc_sgb_register_posts_block();
}
function pgc_sgb_filter_custom_post_by_taxonomies($post_type)
{
	if ($post_type !== PGC_SGB_POST_TYPE) {
		return;
	}
	$taxonomies = array(PGC_SGB_TAXONOMY);
	foreach ($taxonomies as $taxonomy_slug) {
		$taxonomy_obj  = get_taxonomy($taxonomy_slug);
		$taxonomy_name = $taxonomy_obj->labels->name;

		$terms = get_terms($taxonomy_slug);

		echo '<select name="' . esc_attr($taxonomy_slug) . '" id="' . esc_attr($taxonomy_slug) . '" class="postform">';
		echo '<option value="">' . sprintf(esc_html__('Show All', 'simply-gallery-block'), esc_html($taxonomy_name)) . '</option>';
		foreach ($terms as $term) {
			printf(
				'<option value="%1$s" %2$s>%3$s (%4$s)</option>',
				esc_attr($term->slug),
				isset($_GET[$taxonomy_slug]) && $_GET[$taxonomy_slug] === $term->slug ? ' selected="selected"' : '',
				esc_html($term->name),
				esc_html($term->count)
			);
		}
		echo '</select>';
	}
}
/** SimpLy Items List  */
function pgc_sgb_is_classic_editor_plugin_active()
{
	if (!function_exists('is_plugin_active')) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	if (is_plugin_active('classic-editor/classic-editor.php')) {
		return true;
	}
	return false;
}
function pgc_sgb_simply_directory_notice()
{
	$current_screen = get_current_screen();
	if (
		isset($current_screen->post_type)
		&& $current_screen->post_type === PGC_SGB_POST_TYPE
		&& pgc_sgb_is_classic_editor_plugin_active() === true
	) {
		echo '<div class="notice notice-error ">'
			. '<div class="pgc-notic-text" style="font-size: larger; padding: 20px;	font-weight: 600;">'
			. __('You are using the legacy "Classic Editor" page builder. This makes visiting this page meaningless. Time to step forward and try the Block Editor!', 'simply-gallery-block')
			. '</div></div>';
	}
	if (
		!isset($current_screen->post_type)
		|| $current_screen->post_type !== PGC_SGB_POST_TYPE
		|| $current_screen->taxonomy !== ''
		|| $current_screen->action === 'add'
	) {
		return;
	}
	echo '<div class="notice notice-info pgc-sgb-notic">'
		. '<div class="pgc-close-button"><span class="dashicons dashicons-no-alt"></span></div>'
		. '<span class="dashicons dashicons-welcome-learn-more pgc-notic-icon"></span>'
		. '<div class="pgc-notic-text">' . __('The SimpLy Gallery Directory allows you to intelligently structure your media content. It will also allow you to easily manage the content of galleries that need frequent updates. Galleries from this directory can be easily used as WordPress Widgets, as well as added to third-party Page Builders (such as Elementor and others that support the Shortcodes system).', 'simply-gallery-block')
		. '</div></div>';
}
function pgc_sgb_table_columns($columns)
{
	// Add additional columns we want to display.
	$pgc_sgb_columns = [
		'cb'        => '<input type="checkbox" />',
		'image'     => __('Cover', 'simply-gallery-block'),
		'title'     => __('Title', 'simply-gallery-block'),
		'shortcode' => __('Shortcode', 'simply-gallery-block'),
		'date'      => __('Date', 'simply-gallery-block'),
	];

	// Allow filtering of columns.
	$pgc_sgb_columns = apply_filters('pgc_sgb_table_columns', $pgc_sgb_columns, $columns, PGC_SGB_POST_TYPE);

	// Return merged column set.  This allows plugins to output their columns (e.g. Yoast SEO),
	// and column management plugins, such as Admin Columns, should play nicely.
	return array_merge($pgc_sgb_columns, $columns);
}
function pgc_sgb_custom_columns_data($column, $post_id)
{
	$post = get_post($post_id);
	switch ($column) {
		case 'image':
			// Get Gallery Images.
			if (has_post_thumbnail($post)) {
				$src = get_the_post_thumbnail_url($post, 'thumbnail');
			} else {
				$src = PGC_SGB_URL . 'assets/icon-75x75.png';
			}
			// Display the cover.
			echo '<img src="' . esc_url($src) . '" width="75" />'; // @codingStandardsIgnoreLine
			break;
		case 'shortcode':
			echo '<code class="pgc-sgb-onclick-selection" role="button" tabIndex="0" aria-hidden="true">';
			echo '[pgc_simply_gallery id="' . get_the_ID() . '"]';
			echo '</code>';
			break;
	}
}
/** Meta Box */
function pgc_sgb_adding_custom_meta_boxes($post)
{
	add_meta_box('pgc-sgb-sc-meta-box', 'SimpLy Gallery Shortcode', 'pgc_sgb_render_meta_box', PGC_SGB_POST_TYPE, 'side', 'high');
}
function pgc_sgb_render_meta_box($post)
{
	echo '<div id="' . PGC_SGB_SLUG . '-post-editor"></div>';
}
/** 5.8 */
function pgc_sgb_allow_my_block_types($allowed_block_types, $block_editor_context)
{
	$post = $block_editor_context->post;
	if (!isset($post) || $post === '') {
		if (isset($block_editor_context->post_type)) {
			$post = $block_editor_context;
		}
	}
	if (!isset($post)) return null;
	global $pgc_sgb_skins_list;
	$allowed_blocks = array();
	foreach ($pgc_sgb_skins_list as $key => $value) {
		$skinName = substr($key, 8);
		if (isset($skinName)) {
			$skinName = 'pgcsimplygalleryblock/' . $skinName;
			array_push($allowed_blocks, $skinName);
		}
	}
	if (in_array($post->post_type, [PGC_SGB_POST_TYPE])) {
		return $allowed_blocks;
	}
	return $allowed_block_types;
}
/** Menu */
function pgc_sgb_add_galleries_admin_pages()
{
	add_submenu_page(
		PGC_SGB_PLUGIN_SLUG,
		__('SimpLy Galleries', 'simply-gallery-block'),
		__('SimpLy Galleries', 'simply-gallery-block'),
		'manage_options',
		'edit.php?post_type=' . PGC_SGB_POST_TYPE
	);
	add_submenu_page(
		PGC_SGB_PLUGIN_SLUG,
		__('Categories', 'simply-gallery-block'),
		__('Categories', 'simply-gallery-block'),
		'manage_options',
		'edit-tags.php?taxonomy=' . PGC_SGB_TAXONOMY . '&post_type=' . PGC_SGB_POST_TYPE
	);
}
add_filter('admin_notices', 'pgc_sgb_simply_directory_notice');
add_filter('manage_edit-' . PGC_SGB_POST_TYPE . '_columns', 'pgc_sgb_table_columns');
add_action('manage_' . PGC_SGB_POST_TYPE . '_posts_custom_column', 'pgc_sgb_custom_columns_data', 10, 2);
add_filter('allowed_block_types_all', 'pgc_sgb_allow_my_block_types', 10, 2);
add_action('add_meta_boxes_' . PGC_SGB_POST_TYPE, 'pgc_sgb_adding_custom_meta_boxes');
add_action('admin_menu', 'pgc_sgb_add_galleries_admin_pages', 99);
add_action('init', 'pgc_sgb_register_post_type');
add_action('restrict_manage_posts', 'pgc_sgb_filter_custom_post_by_taxonomies', 10);
add_action('admin_enqueue_scripts', 'pgc_sgb_post_enqueue_scripts');
