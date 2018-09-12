<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function theme_enqueue_styles() {
    wp_enqueue_style( 'divi', get_template_directory_uri() . '/style.css' );
}


//auto install
require_once get_stylesheet_directory() . '/auto-install/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'df_child_theme_register_required_plugins' );

function df_child_theme_register_required_plugins() {
	$plugins = array(
		array(
			'name' => 'One Click Demo Import',
			'slug' => 'one-click-demo-import',
			'required'  => true,
		),
		array(
			'name'         => 'Divi Framework Dashboard Plugin',
			'slug'         => 'diviframework',
			'source'       => 'https://s3-ap-southeast-2.amazonaws.com/diviframework/diviframework/diviframework-1.0.7.zip',
			'required'     => true,
		),
	);


	$config = array(
		'id'           => 'divi-child-theme',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}


function ocdi_import_files() {
	return array(
		array(
			'import_file_name'           => 'Divi Child Theme Import',
			'categories' => array('Divi Child Theme Import'),
			'import_file_url'            => 'https://s3-ap-southeast-2.amazonaws.com/diviframework/public/divi-child-theme/content.xml',
			'import_widget_file_url'     => 'https://s3-ap-southeast-2.amazonaws.com/diviframework/public/divi-child-theme/widgets.wie',
			'import_customizer_file_url' => 'https://s3-ap-southeast-2.amazonaws.com/diviframework/public/divi-child-theme/customizer.dat',
			'import_notice' => __( 'Please wait for a few minutes. Do not close the window or refresh the page until the data is imported.', 'your_theme_name' ),

		),
	);
}
add_filter( 'pt-ocdi/import_files', 'ocdi_import_files' );

// Reset the standard WordPress widgets
function ocdi_before_widgets_import($selected_import) {
	if (!get_option('acme_cleared_widgets')) {
		update_option('sidebars_widgets', array());
		update_option('acme_cleared_widgets', true);
	}
}

add_action('pt-ocdi/before_widgets_import', 'ocdi_before_widgets_import');

function ocdi_after_import_setup() {
	$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
	$secondary_menu = get_term_by( 'name', 'Secondary Menu', 'nav_menu' );
	set_theme_mod( 'nav_menu_locations', array(
		'primary-menu' => $main_menu->term_id,
		'secondary-menu' => $secondary_menu->term_id,
	));

    // Assign home page and posts page (blog page).
	$front_page_id = get_page_by_title( 'Home' );
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front_page_id->ID );

	$et_divi = json_decode('{}', true);

	update_option('et_divi', $et_divi);
}

add_action( 'pt-ocdi/after_import', 'ocdi_after_import_setup' );

add_filter('pt-ocdi/disable_pt_branding', '__return_true');


function ocdi_plugin_intro_text( $default_text ) {
    $default_text .= '<div class="ocdi__intro-text">One click import of demo data, Divi theme customizer settings and WordPress widgets for the <b>Divi Child Theme</b></div>';

    return $default_text;
}
add_filter( 'pt-ocdi/plugin_intro_text', 'ocdi_plugin_intro_text' );
