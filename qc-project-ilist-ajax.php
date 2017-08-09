<?php

add_action('wp_head', 'qcilist_ajax_ajaxurl');

function qcilist_ajax_ajaxurl() {

   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

//Doing ajax action stuff

function ilist_upvote_ajax_action_stuff()
{


	//Get posted items
	$action = sanitize_text_field(esc_html__(trim($_POST['action'])));
	$post_id = sanitize_text_field(esc_html__(trim($_POST['post_id'])));
	$meta_title = sanitize_text_field(esc_html__(trim($_POST['meta_title'])));
	$meta_link = sanitize_text_field(esc_html__(trim($_POST['meta_link'])));
	$meta_desc = sanitize_text_field(esc_html__(trim($_POST['meta_desc'])));
	$meta_idd = sanitize_text_field(esc_html__(trim($_POST['meta_id'])));
	$li_id = sanitize_text_field(esc_html__(trim($_POST['li_id'])));

	//Check wpdb directly, for all matching meta items
	global $wpdb;

	$results = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key = 'qcld_text_group'" );

	//Defaults
	$votes = 0;

	$data['votes'] = 0;
	$data['vote_status'] = 'failed';
	
	$exists = in_array("$li_id", $_COOKIE['voted_li']);
	
	
	
	//If li-id not exists in the cookie, then prceed to vote
	if( !$exists )
	{
		
		foreach( $results as $ke => $value )
		{
			
			$item = $value;

			$meta_id = $value->meta_id;
			//Unserializing meta value
			$unserializedarray = unserialize($value->meta_value);
			//creating new array for update value
			$new_sl_array = array();
			
			foreach($unserializedarray as $k=>$unserialized){ 
				
				
				
				//Matching for meta value key , what need to be update.
				if( trim($unserialized['qcld_counter']) == trim($meta_idd))
				{
					
					$metaId = $meta_id;

					//Defaults for current iteration
					$upvote_count = 0;
					$new_array = array();
					$flag = 0;

					//Check if there already a set value (previous)
					if (array_key_exists('sl_thumbs_up', $unserialized))
					{
						$upvote_count = (int)$unserialized['sl_thumbs_up'];
						$flag = 1;
					}
					$tflag=0;
					if (!array_key_exists('sl_thumbs_up_user', $unserialized)){
						$unserialized['sl_thumbs_up_user']= '';
						$tflag = 1;
					}
					//User information saving array.
					$userinfo = array();
					foreach( $unserialized as $skey => $svalue )
					{
						
						if($flag)
						{
							if( $skey == 'sl_thumbs_up')
							{
								$new_array[$skey] =  $upvote_count + 1;
							}
							else
							{
								$new_array[$skey] = $svalue;
							}
							
						}	// End of Flag
						else
						{
							$new_array[$skey] = $svalue;	
						}
					}	//End of Foreach Loop

					if( !$flag )
					{
						$new_array['sl_thumbs_up'] = $upvote_count + 1;	
					}
					//Collection user info
					$userinfo[] = array('ip'=>$_SERVER['REMOTE_ADDR'],'user_agent'=>$_SERVER['HTTP_USER_AGENT'],'time'=>date('Y-m-d H:i:s'));
					
					if($tflag==1){
						$getnewvalue = $userinfo;
					}else{
						$getthumbsupusers = unserialize($unserialized['sl_thumbs_up_user']);
						$getnewvalue = array_merge($getthumbsupusers,$userinfo);
					}
					//User info assign to meta value key.
					$new_array['sl_thumbs_up_user'] = serialize($getnewvalue);
					
					
					
					
					$votes = (int)$new_array['sl_thumbs_up'];
					$new_sl_array[$k] = $new_array;

				}else{
					$new_sl_array[$k] = $unserialized;
				}
			}	//End of Foreach Loop
				
					
					//New Updated value in Array
					$updated_value = serialize($new_sl_array);
					//wp update query
					$wpdb->update( 
						$wpdb->postmeta, 
						array( 
							'meta_value' => $updated_value,
						), 
						array( 'meta_id' => $meta_id )
					);
					
					$voted_li = array("$li_id");
					
					$total = 0;
					$total = count($_COOKIE['voted_li']);
					$total = $total+1;
					//Creating cookie..
					setcookie("voted_li[$total]",$li_id, time() + (86400 * 30), "/");

					$data['vote_status'] = 'success';
					$data['votes'] = $votes;

		}	//End of Foreach Loop
	}	//End of cookie checking.

	$data['cookies'] = $_COOKIE['voted_li'];
	
	echo json_encode($data);


	die(); // stop executing script
}

//Implementing the ajax action for frontend users
add_action( 'wp_ajax_qcld_upvote_action', 'ilist_upvote_ajax_action_stuff' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_qcld_upvote_action', 'ilist_upvote_ajax_action_stuff' ); // ajax for not logged in users

//all template
function ilist_show_ilist_templates(){
$list_type = sanitize_text_field(esc_html__(trim($_POST['list_type'])));
$dir = dirname(__FILE__).'/views';
$templatearray = array();

if($list_type=='textlist'){
	//creating templates array
	$templatearray['simple'] = array(
			'simple-list-one'          => esc_html__('Simple List Template One'),
			'simple-list-two'          => esc_html__('Simple List Template Two'),
			'simple-list-three'          => esc_html__('Simple List Template Three'),
			'simple-list-four'          => esc_html__('Simple List Template Four'),
			'infographic-template-five'   => esc_html__('Simple List Template Five'),
			'simple-list-six'   => esc_html__('Simple List Template Six'),
	);
	$templatearray['elegant'] = array(
			'premium-info-01' => esc_html__( 'Premium Info 01', 'cmb2' ),
			'premium-info-02'   => esc_html__( 'Premium Info 02', 'cmb2' ),
			'premium-info-03'   => esc_html__( 'Premium Info 03', 'cmb2' ),
			'premium-info-04'   => esc_html__( 'Premium Info 04', 'cmb2' ),
			'premium-info-05'   => esc_html__( 'Premium Info 05', 'cmb2' ),
			'premium-info-06'   => esc_html__( 'Premium Info 06', 'cmb2' ),
			'premium-info-07'   => esc_html__( 'Premium Info 07', 'cmb2' ),
	);
}elseif($list_type=='graphiclist'){
	//for graphic list//
	$templatearray['simple'] = array(
			'image-template-one' => esc_html__( 'Image Template One', 'cmb2' ),
			'image-template-two'   => esc_html__( 'Image Template Two', 'cmb2' ),
			'image-template-three'   => esc_html__( 'Image Template Three', 'cmb2' ),
			'image-template-four'   => esc_html__( 'Image Template Four', 'cmb2' ),
			'image-template-five'   => esc_html__( 'Image Template Five', 'cmb2' ),
	);
	$templatearray['elegant'] = array(
			'premium-graphic-style-01' => esc_html__( 'Premium Style 01', 'cmb2' ),
			'premium-graphic-style-02' => esc_html__( 'Premium Style 01', 'cmb2' ),
	);
}else{
	$templatearray['simple'] = array(
		'infographic-template-one'   => esc_html__('Infographic Template One'),
		'infographic-template-two'          => esc_html__('Infographic Template Two'),
		'infographic-template-three'          => esc_html__('Infographic Template Three'),
		'infographic-template-four'   => esc_html__('Infographic Template Four'),
		'infographic-template-five'   => esc_html__('Infographic Template Five'),
		'infographic-template-six'   => esc_html__('Infographic Template Six'),
		'infographic-template-seven'   => esc_html__('Infographic Template Seven'),
		'infographic-template-eight'   => esc_html__('Infographic Template Eight'),
		'infographic-template-nine'   => esc_html__('Infographic Template Nine'),
		'infographic-template-ten'   => esc_html__('Infographic Template Ten'),
		'infographic-template-eleven'   => esc_html__('Infographic Template Eleven'),
		'infographic-template-twelve'   => esc_html__('Infographic Template Twelve'),
		'infographic-template-thirteen'   => esc_html__('Infographic Template Thirteen'),
		'infographic-template-fourteen'   => esc_html__('Infographic Template Fourteen'),
	);
	$templatearray['elegant'] = array(
		'chocolate-style-01' => esc_html__( 'Chocolate Style 01', 'cmb2' ),
		'chocolate-style-02'   => esc_html__( 'Chocolate Style 02', 'cmb2' ),
		'origami-style-04'   => esc_html__( 'Origami Style 04', 'cmb2' ),
		
		'origami-style-06'   => esc_html__( 'Origami Style 06', 'cmb2' ),
		'origami-style-08'   => esc_html__( 'Origami Style 08', 'cmb2' ),
		'origami-style-09'   => esc_html__( 'Origami Style 09', 'cmb2' ),
		
		'premium-style-01'   => esc_html__( 'Premium Style 01', 'cmb2' ),
		'premium-style-02'   => esc_html__( 'Premium Style 02', 'cmb2' ),
		'premium-style-04'   => esc_html__( 'Premium Style 04', 'cmb2' ),
		'premium-style-05'   => esc_html__( 'Premium Style 05', 'cmb2' ),
		'premium-style-06'   => esc_html__( 'Premium Style 06', 'cmb2' ),
		'premium-style-07'   => esc_html__( 'Premium Style 07', 'cmb2' ),
		'premium-style-08'   => esc_html__( 'Premium Style 08', 'cmb2' ),
		'premium-style-09'   => esc_html__( 'Premium Style 09', 'cmb2' ),
		'premium-style-10'   => esc_html__( 'Premium Style 10', 'cmb2' ),
		'premium-style-11'   => esc_html__( 'Premium Style 11', 'cmb2' ),
		'premium-style-12'   => esc_html__( 'Premium Style 12', 'cmb2' ),
		'premium-style-13'   => esc_html__( 'Premium Style 13', 'cmb2' ),
		'premium-style-14'   => esc_html__( 'Premium Style 14', 'cmb2' ),
		'premium-style-15'   => esc_html__( 'Premium Style 15', 'cmb2' ),
		'premium-style-16'   => esc_html__( 'Premium Style 16', 'cmb2' ),
		'premium-style-17'   => esc_html__( 'Premium Style 16', 'cmb2' ),
		'premium-style-18'   => esc_html__( 'Premium Style 16', 'cmb2' ),
		'premium-style-19'   => esc_html__( 'Premium Style 16', 'cmb2' ),
	);
			
}





?>
	<div id="ilist-modal" class="ilistmodaltemplate">

		<!-- Modal content -->
		<div class="modal-content-template" data="<?php echo $list_type; ?>">
			<span class="close">×</span>
			<h3><?php _e( 'Simple Template' , 'iList' ); ?></h3>
			<hr/>
			<div class="qcld_ilist_template_selection">
			<?php foreach($templatearray as $key=>$val) : ?>
				
				<?php 
					if($key != 'simple'){
						echo '<h2 style="margin: 2em 0;"><span style="color:red">Pro Templates (Need Pro version)</span></h2><hr />';
					}
				?>
				
				
				<?php if($key=='simple') : ?>
					<div class="ilist_masonry">
					<?php foreach($val as $k=>$v) : ?>
						
							<div class="ilist_list_elem" data="<?php echo $k; ?>">
								<img style="width:150px" src="<?php echo plugins_url( 'screenshots/'.$k.'.jpg', __FILE__ ); ?>" />
							</div>
						
					<?php endforeach ?>
					</div>
				<?php else : ?>
					<div class="ilist_masonry">
					<?php foreach($val as $k=>$v) : ?>
						
							<div class="ilist_list_elem_pro" data="<?php echo $k; ?>">
								<a href="https://www.quantumcloud.com/iList/" target="_blank"><img style="width:150px" src="<?php echo plugins_url( 'alltemplate/'.$k.'/screenshot.jpg', __FILE__ ); ?>" />
							</div>
						
					<?php endforeach ?>
					</div>
				<?php endif ?>
					
			<?php endforeach ?>
				
			</div>
		</div>

	</div>
<?php
	die();
}

add_action( 'wp_ajax_show_ilist_templates', 'ilist_show_ilist_templates' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_show_ilist_templates', 'ilist_show_ilist_templates' ); // 

function render_shortcode_modal() {

	?>

	<div id="ilist-modal" class="ilist_shortcode_modal">

		<!-- Modal content -->
		<div class="ilist-modal-content">
			<span class="close">×</span>
			<h3><img src="<?php echo plugins_url( 'assets/images/1.png', __FILE__ ) ?>" alt="iList - Free"> <?php _e( ' - Shortcode Maker' , 'iList' ); ?></h3>
			<hr/>
			<div class="sm_shortcode_list">

				<?php
				echo '<div class="ilist_single_field_shortcode">';
				echo '<label style="width: 200px;display: inline-block;">Select Post </label><select style="width: 225px;" id="ilist_post_select_shortcode"><option value="">Please Select One</option>';
				$ilist = new WP_Query( array( 'post_type' => 'ilist', 'posts_per_page' => -1, 'order' => 'ASC') );
				if( $ilist->have_posts()){
					while( $ilist->have_posts() ){
						$ilist->the_post();
						echo '<option value="'.get_the_ID().'">' . get_the_title() . '</option>';
					}
				}
				echo '</select>';
				echo '</div>';
				?>

				<div class="ilist_single_field_shortcode">
					<label style="width: 200px;display: inline-block;">Column</label><select style="width: 225px;" id="ilist_column_shortcode">
						<option value="1">Column 1</option>
						<option value="2">Column 2</option>
						<option value="3">Column 3</option>
						<option value="4">Column 4</option>

					</select>
				</div>

				<div class="ilist_single_field_shortcode">
					<label style="width: 200px;display: inline-block;margin-top: 22px;">Upvote</label>
					<div class="switchilist demo3">
						<input class="upvote_switcher" name="ckbox" value="on" type="checkbox">
						<label><i></i></label>
					</div>
					<div style="clear:both"></div>
					<!--<select style="width: 225px;" id="ilist_upvote_shortcode">
						<option value="on">On</option>
						<option value="off">Off</option>
					</select>-->
				</div>

				<div class="ilist_single_field_shortcode">
					<label style="width: 200px;display: inline-block;"></label><input type="button" id="add_shortcode" value="Add Shortcode" />
				</div>
			</div>
		</div>

	</div>
	<?php
	exit;
}
add_action( 'wp_ajax_show_shortcodes', 'render_shortcode_modal');

?>