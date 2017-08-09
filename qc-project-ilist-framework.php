<?php




if( !function_exists('ot_register_settings') ){
  require_once 'inc/option-tree/ot-loader.php';	
}

add_filter( 'ot_show_pages', '__return_false' );
add_filter( 'ot_show_new_layout', '__return_false' );

function qcld_ilist_remove_ot_menu () {
    remove_submenu_page( 'themes.php', 'ot-theme-options' );
}

add_action( 'admin_init', 'qcld_ilist_remove_ot_menu' );

add_filter( 'ot_header_version_text', 'ilist_ot_version_text_custom' );

function ilist_ot_version_text_custom()
{
	$text = 'Developed by <a href="http://www.quantumcloud.com" target="_blank">Web Design Company - QuantumCloud</a>';
	
	return $text;
}

/**
 * Hook to register admin pages 
 */
add_action( 'init', 'ilist_register_options_pages' );

/**
 * Registers all the required admin pages.
 */

function ilist_register_options_pages() {

  // Only execute in admin & if OT is installed
  if ( is_admin() && function_exists( 'ot_register_settings' ) ) {

    // Register the pages
    ot_register_settings( 
      array(
        array( 
          'id'              => 'ilist_plugin_options',
          'pages'           => array(
            array(
              'id'              => 'ilist_options',
              'parent_slug'     => 'edit.php?post_type=ilist',
              'page_title'      => 'Settings',
              'menu_title'      => 'Settings',
              'capability'      => 'edit_theme_options',
              'menu_slug'       => 'ilist-options-page',
              'icon_url'        => null,
              'position'        => null,
              'updated_message' => 'iList Options Updated.',
              'reset_message'   => 'iList Options Reset.',
              'button_text'     => 'Save Changes',
              'show_buttons'    => true,
              'screen_icon'     => 'options-general',
              'contextual_help' => null,
			  
    'sections'        => array( 
      array(
        'id'          => 'general',
        'title'       => __( 'General', 'theme-text-domain' )
      ),
      array(
        'id'          => 'custom_css',
        'title'       => __( 'Custom CSS', 'theme-text-domain' )
      ),
      array(
        'id'          => 'help',
        'title'       => __( 'Help', 'theme-text-domain' )
      )
    ),
    'settings'        => array( 
      array(
		'label'       => __('Enable RTL Direction'),
		'id'          => 'sl_enable_rtl',
		'type'        => 'on-off',
		'desc'        => __('If you make this option ON, then list heading and list items will be arranged in Right-to-Left direction.'),
		'std'         => 'off',
		'rows'        => '',
		'post_type'   => '',
		'taxonomy'    => '',
		'class'       => '',
		'section'     => 'general'
	),
	array(
		'label'       => __('Enable embed List button on listing pages'),
		'id'          => 'sl_enable_embed_list',
		'type'        => 'on-off',
		'desc'        => __('Enable embed link button to generate iFrame embed code for particular list.'),
		'std'         => 'off',
		'rows'        => '',
		'post_type'   => '',
		'taxonomy'    => '',
		'class'       => '',
		'section'     => 'general'
	),
	array(
		'label'       => __('Title for Embed option'),
		'id'          => 'sl_embed_title',
		'type'        => 'text',
		'desc'        => __('Credit title displayed in embed option.'),
		'std'         => '',
		'rows'        => '',
		'post_type'   => '',
		'taxonomy'    => '',
		'class'       => '',
		'section'     => 'general'
	),
	array(
		'label'       => __('Link for Embed option'),
		'id'          => 'sl_embed_link',
		'type'        => 'text',
		'desc'        => __('Credit link displayed in embed option.'),
		'std'         => '',
		'rows'        => '',
		'post_type'   => '',
		'taxonomy'    => '',
		'class'       => '',
		'section'     => 'general'
	),
	array(
		'label'       => 'Custom CSS',
		'id'          => 'sl_custom_style',
		'type'        => 'textarea-simple',
		'desc'        => __('Write your custom CSS here.'),
		'std'         => '',
		'rows'        => '',
		'post_type'   => '',
		'taxonomy'    => '',
		'class'       => '',
		'section'     => 'custom_css'
	),
	array(
		'label'       => __('Help'),
		'id'          => 'aid',
		'type'        => 'Textblock',
		'desc'        => '<div>
							<h3>Shortcode Generator</h3>
							<p>Use the shortcode generator to easily embed ilist on your page.</p>
							<img src="'.QCOPD_IMG_URL1.'/shortcode_generator.jpg" alt="Shortcode Generator" />
							<h3>Shortcode Example</h3>
							<p>
								
								[qcld-ilist mode="one" list_id="75"]
								<br>
								<br>
								<strong><u>Available Parameters:</u></strong>
								<br>
							</p>
							<p>
								<strong>1. mode</strong>
								<br>
								Value for this option can be set as "one" or "all".
								Example: mode="one"
							</p>
							<p>
								<strong>2. column</strong>
								<br>
								Avaialble values: "1", "2", "3".
								Example: column=1
							</p>

							<p>
								<strong>6. list_id</strong>
								<br>
								Only applicable if you want to display a single list [not all]. You can provide specific list id here as a value. You can also get ready shortcode for a single list under "Manage List Items" menu.
								Example: list_id="404"
							</p>
							
							<p>
								<strong>9. upvote</strong>
								<br>
								Values: on or off. This options allows upvoting of your list items.
								<br>
								Example: upvote="on"
							</p>

						</div>',
		'std'         => '',
		'rows'        => '',
		'post_type'   => '',
		'taxonomy'    => '',
		'class'       => '',
		'section'     => 'help'
	),
    )
            )
          )
        )
      )
    );

  }

}

?>