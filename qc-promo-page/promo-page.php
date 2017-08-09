<?php

//$menu_slug = 'edit.php?post_type=sld';

/**
 * Register a custom promo menu page.
 */
function qc_ilist_promo_add_promo_menu_page_qcopd(){

	$menu_slug = 'edit.php?post_type=ilist';
	
	add_submenu_page(
        $menu_slug,
        __( 'More WordPress Goodies for You!', 'quantumcloud' ),
        __( 'More', 'quantumcloud' ),
        'manage_options',
        'qcpro-promo-page',
        'qc_ilist_promo_add_promo_page_callaback_qcopd'
    );
	
}

add_action( 'admin_menu', 'qc_ilist_promo_add_promo_menu_page_qcopd' );
 
/**
 * Display promo page content
 */
function qc_ilist_promo_add_promo_page_callaback_qcopd()
{
    //Include Part File
	require_once('main-part-file.php');  
}