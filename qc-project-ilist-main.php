<?php 
/*
*Plugin Name: Infographic and List Builder iList
* Plugin URI: https://wordpress.org/plugins/infographic-and-list-builder-iList
* Description: Infographics & elegant Lists with charts and graphs. Build HTML, Responsive infographics & elegant Text or Image Lists quickly.
* Version: 2.0.0
* Author: QuantumCloud
* Author URI: https://www.quantumcloud.com/
* Requires at least: 3.0
* Tested up to: 4.8.0
* Domain Path: /lang/
* License: GPL2
*/

 defined('ABSPATH') or die("No direct script access!");
 
 //Custom Constants
define('QCOPD_URL1', plugin_dir_url(__FILE__));
define('QCOPD_IMG_URL1', QCOPD_URL1 . "/assets/images");
define('QCOPD_ASSETS_URL1', QCOPD_URL1 . "/assets");

define('QCOPD_DIR1', dirname(__FILE__));
define('QCOPD_INC_DIR1', QCOPD_DIR1 . "/inc");

require_once( 'qc-project-ilist-framework.php' );
require_once( 'qc-project-ilist-post-type.php' );
require_once( 'qc-project-ilist-asset.php' );
require_once( 'qc-project-ilist-ajax.php' );
require_once( 'qc-project-ilist-shortcode.php' );
require_once( 'qc-project-ilist-hook.php' );
require_once( 'qc-project-ilist-chart.php' );
require_once( 'qc-project-ilist-fa.php' );
require_once('qc-support-promo-page/class-qc-support-promo-page.php');
require_once('class-qc-free-plugin-upgrade-notice.php');


function qcld_ilist_order_index_catalog_menu_page( $menu_ord )
{

 global $submenu;

 // Enable the next line to see a specific menu and it's order positions
 //echo '<pre>'; print_r( $submenu['edit.php?post_type=ilist'] ); echo '</pre>'; exit();

 // Sort the menu according to your preferences
 //Original order was 5,11,12,13,14,15

	$arr = array();

	@$arr[] = $submenu['edit.php?post_type=ilist'][5];
	@$arr[] = $submenu['edit.php?post_type=ilist'][10];
	@$arr[] = $submenu['edit.php?post_type=ilist'][15];
	@$arr[] = $submenu['edit.php?post_type=ilist'][17];
	@$arr[] = $submenu['edit.php?post_type=ilist'][16];
 
	if( isset($submenu['edit.php?post_type=ilist'][300]) ){
		$arr[] = $submenu['edit.php?post_type=ilist'][300];
	}

 $submenu['edit.php?post_type=ilist'] = $arr;

 return $menu_ord;

}

// add the filter to wordpress
add_filter( 'custom_menu_order', 'qcld_ilist_order_index_catalog_menu_page' );
?>