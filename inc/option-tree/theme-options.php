<?php add_action( 'init', 'custom_theme_options' ); function custom_theme_options() { if ( ! function_exists( 'ot_settings_id' ) || ! is_admin() ) return false; $saved_settings = get_option( ot_settings_id(), array() ); $custom_settings = array( 'contextual_help' => array( 
      'content'       => array( 
        array(
          'id'        => 'option_types_help',
          'title'     => __( 'Option Types', 'theme-text-domain' ),
          'content'   => '
 
' . __( 'Help content goes here!', 'theme-text-domain' ) . '
 
'
        )
      ),
      'sidebar'       => '
 
' . __( 'Sidebar content goes here!', 'theme-text-domain' ) . '
 
'
    ),
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
  );
    $custom_settings = apply_filters( ot_settings_id() . '_args', $custom_settings );
    if ( $saved_settings !== $custom_settings ) {
    update_option( ot_settings_id(), $custom_settings ); 
  }
    global $ot_has_custom_theme_options;
  $ot_has_custom_theme_options = true;
   
}