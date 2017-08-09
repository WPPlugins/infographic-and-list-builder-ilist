<?php 
define('OCOPD_TPL_DIR1', QCOPD_DIR1 . "/views");
define('OCOPD_TPL_URL1', QCOPD_URL1 . "/views");
//Shortcode area
add_shortcode('qcld-ilist', 'qcilist_textlist_full_shortcode');

function qcilist_textlist_full_shortcode( $atts = array() )
{
	ob_start();

	//Defaults & Set Parameters
	extract( shortcode_atts(
		array(
			'orderby' => 'title',
			'order' => 'ASC',
			'mode' => 'one',
			'list_id' => '',
			'upvote'	=>'on',
			'column' => '1',
			'style' => 'simple',
			'list_img' => 'true',
			'category' => "",
		), $atts
	));
if($column>4){
	$column = 4;
}
	
$shortcodeAtts = array(
		'orderby' => $orderby,
		'order' => $order,
		'mode' => $mode,
		'list_id' => $list_id,
		'upvote'	=> $upvote,
		'column' => $column,
		'style' => $style,
		'list_img' => $list_img,
		
		'category' => $category,
		
	);
	//print_r($shortcodeAtts);exit;
	$limit = -1;

	if( $mode == 'one' )
	{
		$limit = 1;	
	}

	//Query Parameters
	$list_args = array(
		'post_type' => 'ilist',
		'orderby' => $orderby,
		'order' => $order,
		'posts_per_page' => $limit,
	);

	if( $list_id != "" && $mode == 'one' )
	{
		$list_args = array_merge($list_args, array( 'p' => $list_id ));
	}
	
	if( $category != "" )
	{
		$taxArray = array(
			array(
				'taxonomy' => 'sl_cat',
				'field'    => 'slug',
				'terms'    => $category,
			),
		);
		
		$list_args = array_merge($list_args, array( 'tax_query' => $taxArray ));
		
	}
	
	// The Query
	$list_query = new WP_Query( $list_args );

	// The Loop
	if ( $list_query->have_posts() ) 
	{

		

		$listId = 1;

		while ( $list_query->have_posts() ) 
		{
			$list_query->the_post();
			
			
			//$lists = get_cmb_group('qcopd_list_item01');
			//Finding Meta Post type.
			$ilist_chart = get_post_meta( get_the_ID(), 'ilist_chart' );
			$show_chart_position = get_post_meta( get_the_ID(), 'show_chart_position' );
			$sl_meta_post_type = get_post_meta( get_the_ID(), 'post_type_radio_sl' );
			//Finding template Defined from user_error
			$template_code = '';
			if($sl_meta_post_type[0]=='textlist'){
				$sl_get_template = get_post_meta( get_the_ID(), 'qcld_sl_template_text' );
				$template_code = $sl_get_template[0];
			}elseif($sl_meta_post_type[0]=='imagelist'){
				$sl_get_template = get_post_meta( get_the_ID(), 'qcld_sl_template_image' );
				$template_code = $sl_get_template[0];
			}else{
				$sl_get_template = get_post_meta( get_the_ID(), 'qcld_sl_template_mix' );
				$template_code = $sl_get_template[0];
			}
			//Getting Group Field Data.
			$lists = get_post_meta( get_the_ID(), 'qcld_text_group' );
			// Check Template Exists Or Not.
			if(file_exists(OCOPD_TPL_DIR1 . "/$template_code/template.php")){
				require ( OCOPD_TPL_DIR1 . "/$template_code/template.php" );
			}else{
				//Template Not Exists.
				echo 'Oops! Template File Not Exists!';
			}
			


			$listId++;
		}	//End While

		
	}
	else
	{
		echo "<div>
				<p>No directory items was found.</p>
			</div>";
	}
	//End IF

    $content = ob_get_clean();
    return $content;
}
?>