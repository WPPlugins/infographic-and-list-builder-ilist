<?php
/*
* QuantumCloud Promo + Support Page
* Revised On: 06-01-2017
*/

/*******************************
 * Add Ajax Object at the head part
 *******************************/
add_action('wp_head', 'qc_process_support_form_ajax_header');

if( !function_exists('qc_process_support_form_ajax_header') )
{
	function qc_process_support_form_ajax_header() 
	{

	   echo '<script type="text/javascript">
	           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
	         </script>';

	} //End of qc_process_support_form_ajax_header

} //End of function_exists

/*******************************
 * Handle Ajex Request for Form Processing
 *******************************/
add_action( 'wp_ajax_process_qc_promo_form', 'process_qc_promo_form' );

if( !function_exists('process_qc_promo_form') )
{
	function process_qc_promo_form()
	{
		
		$data['status'] = 'failed';
		$data['message'] = __('Problem in processing your form submission request! Apologies for the inconveniences.<br> 
Please email to <span style="color:#22A0C9;font-weight:bold !important;font-size:14px "> quantumcloud@gmail.com </span> with any feedback. We will get back to you right away!', 'quantumcloud');

		$name = trim(sanitize_text_field($_POST['post_name']));
		$email = trim(sanitize_email($_POST['post_email']));
		$subject = trim(sanitize_text_field($_POST['post_subject']));
		$message = trim(sanitize_text_field($_POST['post_message']));
		$plugin_name = trim(sanitize_text_field($_POST['post_plugin_name']));

		if( $name == "" || $email == "" || $subject == "" || $message == "" )
		{
			$data['message'] = 'Please fill up all the requried form fields.';
		}
		else if ( filter_var($email, FILTER_VALIDATE_EMAIL) === false ) 
		{
			$data['message'] = 'Invalid email address.';
		}
		else
		{

			//build email body

			$bodyContent = "";
				
			$bodyContent .= "<p><strong>Support Request Details:</strong></p><hr>";

			$bodyContent .= "<p>Name : ".$name."</p>";
			$bodyContent .= "<p>Email : ".$email."</p>";
			$bodyContent .= "<p>Subject : ".$subject."</p>";
			$bodyContent .= "<p>Message : ".$message."</p>";

			$bodyContent .= "<p>Sent Via the Plugin: ".$plugin_name."</p>";

			$bodyContent .="<p></p><p>Mail sent from: <strong>".get_bloginfo('name')."</strong>, URL: [".get_bloginfo('url')."].</p>";
			$bodyContent .="<p>Mail Generated on: " . date("F j, Y, g:i a") . "</p>";			
			
			$toEmail = "quantumcloud@gmail.com"; //Receivers email address
			//$toEmail = "qc.kadir@gmail.com"; //Receivers email address

			//Extract Domain
			$url = get_site_url();
			$url = parse_url($url);
			$domain = $url['host'];
			

			$fakeFromEmailAddress = "wordpress@" . $domain;
			
			$to = $toEmail;
			$body = $bodyContent;
			$headers = array();
			$headers[] = 'Content-Type: text/html; charset=UTF-8';
			$headers[] = 'From: '.$name.' <'.$fakeFromEmailAddress.'>';
			$headers[] = 'Reply-To: '.$name.' <'.$email.'>';

			$finalSubject = "From Plugin Support Page: " . $subject;
			
			$result = wp_mail( $to, $finalSubject, $body, $headers );

			if( $result )
			{
				$data['status'] = 'success';
				$data['message'] = __('Your email was sent successfully. Thanks!', 'quantumcloud');
			}

		}

		ob_clean();

		
		echo json_encode($data);
	
		die();
	}
}





/*******************************
 * Main Class to Display Support
 * form and the promo pages
 *******************************/
if( !class_exists('QcSupportAndPromoPage') ){


	class QcSupportAndPromoPage{
	
		public $plugin_menu_slug = "";
		public $plugin_slug = "sld"; //Should be unique, like: qcsld_p123
		public $promo_page_title = 'More WordPress Goodies for You!';
		public $promo_menu_title = 'Support';
		public $plugin_name = '';
		
		public $page_slug = "";
		
		public $relative_folder_url;
		
		//public $relative_folder_url = plugin_dir_url( __FILE__ );
		
		function __construct( $plugin_slug = null )
		{
		
			if(!function_exists('wp_get_current_user')) {
				include(ABSPATH . "wp-includes/pluggable.php"); 
			}
			
			$this->page_slug = 'qcpro-promo-page-' . $plugin_slug;
			$this->relative_folder_url = plugin_dir_url( __FILE__ );
			
			add_action('admin_enqueue_scripts', array(&$this, 'include_promo_page_scripts'));
			
			//add_action( 'wp_ajax_process_qc_promo_form', array(&$this,'process_qc_promo_form') );
			
		} //End of Constructor
		
		function include_promo_page_scripts( $hook )
		{                                 
		   
		   wp_enqueue_script( 'jquery' );
		   wp_enqueue_script( 'jquery-ui-core');
		   wp_enqueue_script( 'jquery-ui-tabs' );
		   wp_enqueue_script( 'jquery-custom-form-processor', $this->relative_folder_url . '/js/support-form-script.js',  array('jquery', 'jquery-ui-core','jquery-ui-tabs') );
		   
		}
		
		function show_promo_page()
		{
		
			if( $this->plugin_menu_slug == "" ){
			   return;
			}
			
			add_action( 'admin_menu', array(&$this, 'show_promo_page_callback_func') );
			
		  
		} //End of function show_promo_page
		
		/*******************************
		 * Callback function to add the menu
		 *******************************/
		function show_promo_page_callback_func()
		{
			add_submenu_page(
				$this->plugin_menu_slug,
				$this->promo_page_title,
				$this->promo_menu_title,
				'manage_options',
				$this->page_slug,
				array(&$this, 'qcpromo_support_page_callback_func' )
			);
		} //show_promo_page_callback_func
		
		/*******************************
		 * Callback function to show the HTML
		 *******************************/
		function qcpromo_support_page_callback_func()
		{
			
			?>
				<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
				<link href="<?php echo $this->relative_folder_url; ?>/css/font-awesome.min.css" rel="stylesheet" type="text/css">
				<link href="<?php echo $this->relative_folder_url; ?>/css/style.css" rel="stylesheet" type="text/css">
				<link href="<?php echo $this->relative_folder_url; ?>/css/responsive.css" rel="stylesheet" type="text/css">
				
				<div class="qc-support-page-wrapper">
					<div id="tabs" class="main-container">
						<div class="tab-header">
							<div class="container-wrapper">
								<div class="logo-left">
									<a href="https://www.quantumcloud.com" target="_blank" title="QuantumCloud">
										<img src="<?php echo $this->relative_folder_url; ?>/images/logo.png"  alt="QuantumClous"/>
									</a>
								</div>
								<div class="tab tab-link">
									<ul class="tabs">
										<li class="current">
											<a href="#tab-one">
												<i class="fa fa-wrench"></i>
												Support
											</a>
										</li>
										<li>
											<a href="#tab-two">
												<i class="fa fa-wordpress"></i>
												More Plugins
											</a>
										</li>
										<li>
											<a href="#tab-three">
												<i class="fa fa-cog"></i>
												Wordpress Services
											</a>
										</li>
									</ul> <!-- / tabs -->
								</div> <!-- / tab -->
							   </div>
							</div><!---tab header---->
							<div id="tab-one" class="tab-content-main">
								<div class="container-wrapper">
									<div class="tab-item">
									<div class="contact-support-left">
										<div class="title-part">
											<h2><span>Contact Us</span>For Support</h2>
										</div>
										<div class="support-form">
											<form class="form" id="qc-support-form" method="POST">

											  <input name="plugin_name" id="plugin_name" type="hidden" value="<?php echo ( $this->plugin_name != "" ) ? $this->plugin_name : "not-set-via-instance"; ?>"/>

											  <div class="name">
												<i class="fa fa-user"></i>
												<input name="name" type="text" class="form-control-input" placeholder="Name" id="name" />
											  </div>
											  
											  <div class="email">
												<i class="fa fa-envelope"></i>
												<input name="email" type="text" class="form-control-input" id="email" placeholder="Email" />
											  </div>
											  
											   <div class="subject">
											   <i class="fa fa-envelope"></i>
												<input name="subject" type="text" class="form-control-input" id="subject" placeholder="Subject" />
											  </div>
											  
											  <div class="message">
											  <i class="fa fa-comment"></i>
												<textarea id="message" class="form-control-input message-control" name="message" placeholder="Message"></textarea>
											  </div>

											  <div id="support-form-result" class="support-form-result">
											  	<div id="support-form-loading" class="support-form-loading"></div>
											  	<div id="support-form-status" class="support-form-status output-success">
											  		
											  	</div>
											  </div>
											  
											  
											  <div class="submit">
												<input type="submit" value="Send Message" class="button-blue" id="qcpg-query-submit-btn"/>
												<div class="ease"></div>
											  </div>
											</form>
										</div>
									</div>
									
									<div class="feature-plugin-right">
										<div class="title-part">
											<h2>
												<span>Featured</span>Plugins
											</h2>
										</div>
										<div class="bottom-feature-plugin">
											<div class="feature feature-plugin-01">
												<div class="icon-box-x18">
													<img src="<?php echo $this->relative_folder_url; ?>/images/i-list-logo.jpg" alt="sld"/>
												</div>
												<h3>
													<a href="https://www.quantumcloud.com/products/iList/" target="_blank">
														INFOGRAPHICS AND LIST BUILDER ILIST
													</a>
												</h3>
												<p>
													Infographics &amp; elegant Lists are now easy to create with iList.
												</p>
											</div>
											<div class="feature feature-plugin-02">
												<div class="icon-box-x18">
													<img src="<?php echo $this->relative_folder_url; ?>/images/jarvis.jpg" alt="sld"/>
												</div>
												<h3>
													<a href="https://www.quantumcloud.com/products/woocommerce-shop-assistant-jarvis/" target="_blank">
														WOOCOMMERCE SHOP ASSISTANT JARVIS
													</a>
												</h3>
												<p>
													WooCommerce shops now have JARVIS - unique shop assistant to increase sales!..
												</p>
											</div>
											<div class="feature feature-plugin-03">
												<div class="icon-box-x18">
													<img src="<?php echo $this->relative_folder_url; ?>/images/simple-link-directory-thumb.jpg" alt="sld"/>
												</div>
												<h3>
													<a href="https://www.quantumcloud.com/products/simple-link-directory/" target="_blank">
														SIMPLE LINK DIRECTORY
													</a>
												</h3>
												<p>
													Directory plugin with a unique approach to curate topic focused website link lists.
												</p>
											</div>
											<div class="feature feature-plugin-04">
												<div class="icon-box-x18">
													<img src="<?php echo $this->relative_folder_url; ?>/images/portfolio-x.jpg" alt="sld"/>
												</div>
												<h3>
													<a href="https://www.quantumcloud.com/products/portfolio-x/" target="_blank">
														PORTFOLIO X
													</a>
												</h3>
												<p>
													Portfolio X is an advanced portfolio manager with streamlined workflow and unique designs...
												</p>
											</div>
											<div class="clear"></div>
											<div class="button-plugin">
												<a href="https://www.quantumcloud.com/products/" class="feature-button button-blue" target="_blank">See All Plugins</a>
											</div>
										</div>
									</div>
									<div class="clear"></div>
								</div>
								</div>
							</div><!---tab-content-main---->
							
							<div id="tab-two" class="tab-content-main">
								<div class="container-wrapper">
									
							<!--Services:Start-->
							<div class="tlist-wrapper-x18">
								<div class="tlist-holder-x18">
									<div class="bottom-row-x18">
														
										<div class="single-item-x18">					
											<div class="icon-box-x18">
												<img src="<?php echo $this->relative_folder_url; ?>/images/simple-link-directory-thumb.jpg" alt="sld"/>
											</div>
											<div class="content-box-x18">
												<h2>
													<a href="https://www.quantumcloud.com/products/iList/" target="_blank">
														INFOGRAPHICS AND LIST BUILDER ILIST
													</a>
												</h2>
												<p>
													Infographics &amp; elegant Lists are now easy to create with iList. Build HTML, Responsive infographics &amp; simple Text or Image Lists quickly.
												</p>
												<p>
													<a href="https://www.quantumcloud.com/products/iList/" target="_blank">Download Now
													</a>
												</p>
											</div>
											
										</div>
										<div class="single-item-x18">					
											<div class="icon-box-x18">
												<img src="<?php echo $this->relative_folder_url; ?>/images/i-list-logo.jpg" alt="sld"/>
											</div>
											<div class="content-box-x18">
												<h2>
													<a href="https://www.quantumcloud.com/products/woocommerce-shop-assistant-jarvis/" target="_blank">
														WOOCOMMERCE SHOP ASSISTANT JARVIS
													</a>
												</h2>
												<p>
													WooCommerce shops now have JARVIS - unique shop assistant to increase sales! PopUp Message, Recently Viewed, Advanced Search in 1 window!
												</p>
												<p>
													<a href="https://www.quantumcloud.com/products/woocommerce-shop-assistant-jarvis/" target="_blank">
														Download Now
													</a>
												</p>
											</div>
										</div>
										<div class="single-item-x18">					
											<div class="icon-box-x18">
												<img src="<?php echo $this->relative_folder_url; ?>/images/portfolio-x.jpg" alt="sld"/>
											</div>
											<div class="content-box-x18">
												<h2>
													<a href="https://www.quantumcloud.com/products/simple-link-directory/" target="_blank">
														SIMPLE LINK DIRECTORY
													</a>
												</h2>
												<p>
													Directory plugin with a unique approach to curate topic focused website link lists. Curate gorgeous Link Resources, Partners, Vendors Directories.
												</p>
												<p>
													<a href="https://www.quantumcloud.com/products/simple-link-directory/" target="_blank">
														Download Now
													</a>
												</p>
											</div>
											
											
										</div>
										<div class="single-item-x18">					
											<div class="icon-box-x18">
												<img src="<?php echo $this->relative_folder_url; ?>/images/jarvis.jpg" alt="sld"/>
											</div>
											<div class="content-box-x18">
												<h2>
													<a href="https://www.quantumcloud.com/products/portfolio-x/" target="_blank">
														PORTFOLIO X
													</a>
												</h2>
												<p>
													Portfolio X is an advanced portfolio manager with streamlined workflow and unique designs to showcase your works. Slideshow and Widgets included.
												</p>
												<p>
													<a href="https://www.quantumcloud.com/products/portfolio-x/" target="_blank">	Download Now
													</a>
												</p>
											</div>
										</div>
										<div class="single-item-x18">					
											<div class="icon-box-x18">
												<img src="<?php echo $this->relative_folder_url; ?>/images/slider-hero-icon.jpg" alt="sld"/>
											</div>
											<div class="content-box-x18">
												<h2>
													<a href="https://www.quantumcloud.com/products/slider-hero/" target="_blank">
														Slider Hero
													</a>
												</h2>
												<p>
													Slider Hero is a unique slider plugin that allows you to create Hero sliders with great Javascript animation effects.
												</p>
												<p>
													<a href="https://www.quantumcloud.com/products/slider-hero/" target="_blank">	Download Now
													</a>
												</p>
											</div>
										</div>
										<div class="single-item-x18">					
											<div class="icon-box-x18">
												<img src="<?php echo $this->relative_folder_url; ?>/images/expressshop-icon.jpg" alt="sld"/>
											</div>
											<div class="content-box-x18">
												<h2>
													<a href="https://www.quantumcloud.com/products/express-shop/" target="_blank">
														Express Shop
													</a>
												</h2>
												<p>
													Express Shop is a WooCommerce addon to show all products in one page. User can add products to cart and go to checkout. 
													Filtering and search integrated in single page.
												</p>
												<p>
													<a href="https://www.quantumcloud.com/products/express-shop/" target="_blank">	Download Now
													</a>
												</p>
											</div>
										</div>
										
										<div class="single-item-x18">					
											<div class="icon-box-x18">
												<img src="<?php echo $this->relative_folder_url; ?>/images/knowledge-base.jpg" alt="sld"/>
											</div>
											<div class="content-box-x18">
												<h2>
													<a href="https://www.quantumcloud.com/products/knowledgebase-helpdesk/" target="_blank">
														KnowledgeBase HelpDesk
													</a>
												</h2>
												<p>
													KnowledgeBase HelpDesk is an advanced Knowledgebase plugin with helpdesk glossary and FAQ features all in one. KnowledgeBase HelpDesk is extremely simple and easy to use.
												</p>
												<p>
													<a href="https://www.quantumcloud.com/products/knowledgebase-helpdesk/" target="_blank">
														Download Now
													</a>
												</p>
											</div>
										</div>
										
										<div class="single-item-x18">					
											<div class="icon-box-x18">
												<img src="<?php echo $this->relative_folder_url; ?>/images/wootab.jpg" alt="sld"/>
											</div>
											<div class="content-box-x18">
												<h2>
													<a href="https://www.quantumcloud.com/products/woo-tabbed-category-product-listing/" target="_blank">
														Woo Tabbed Category Product Listing
													</a>
												</h2>
												<p>
													WooCommerce plugin that allows you to showcase your products category wise in tabbed format. Woo Tabbed Category Product Listing is a simple woocommerce plugin that allows you to dynaimically load your products in tabs based on your product categories using short code - simple and easy.
												</p>
												<p>
													<a href="https://www.quantumcloud.com/products/woo-tabbed-category-product-listing/" target="_blank">
														Download Now
													</a>
												</p>
											</div>
										</div>
										
										<div class="single-item-x18">					
											<div class="icon-box-x18">
												<img src="<?php echo $this->relative_folder_url; ?>/images/seo.jpg" alt="sld"/>
											</div>
											<div class="content-box-x18">
												<h2>
													<a href="https://www.quantumcloud.com/products/seo-help" target="_blank">
														SEO HELP
													</a>
												</h2>
												<p>
													SEO Help provides helpful hints to generate LinkBait titles. Increase your Click Through Rate or CTR. Write better contents with SEO tips.
												</p>
												<p>
													<a href="https://www.quantumcloud.com/products/seo-help" target="_blank">
														Download Now
													</a>
												</p>
											</div>
										</div>
										
										<div class="single-item-x18">					
											<div class="icon-box-x18">
												<img src="<?php echo $this->relative_folder_url; ?>/images/ichart-300x300.jpg" alt="sld"/>
											</div>
											<div class="content-box-x18">
												<h2>
													<a href="https://www.quantumcloud.com/products/ichart/" target="_blank">
														iChart - Easy Charts and Graphs
													</a>
												</h2>
												<p>
													Charts and graphs are now easy to build and add to any WordPress page with just a few clicks and shortcode generator. iChart is a chartjs implementation to add beautiful graphs &amp;  to your site easily - directly from WordPress Visual editor.
												</p>
												<p>
													<a href="https://www.quantumcloud.com/products/ichart/" target="_blank">
														Download Now
													</a>
												</p>
											</div>
										</div>
										
										<div class="single-item-x18">				
											<div class="icon-box-x18">
												<img src="<?php echo $this->relative_folder_url; ?>/images/PageSpeed-Friendly-Analytics-Tracking-1-300x300.jpg" alt="sld"/>
											</div>
											<div class="content-box-x18">
												<h2>
													<a href="https://www.quantumcloud.com/products/analytics-tracking/" target="_blank">
														PageSpeed Friendly Analytics Tracking
													</a>
												</h2>
												<p>
													QuantumCloud PageSpeed Friendly Analytics Tracking for Google does the simple job of adding tracking code to your WordPress website in all pages.
												</p>
												<p>
													<a href="https://www.quantumcloud.com/products/analytics-tracking/" target="_blank">
														Download Now
													</a>
												</p>
											</div>
										</div>
										
										<div class="single-item-x18">				
											<div class="icon-box-x18">
												<img src="<?php echo $this->relative_folder_url; ?>/images/Comment-Link-Remove-300x300.jpg" alt="sld"/>
											</div>
											<div class="content-box-x18">
												<h2>
													<a href="https://www.quantumcloud.com/products/comment-link-remove/" target="_blank">
														Comment Link Remove
													</a>
												</h2>
												<p>
													All in one solution to fight comment spammers. Tired of deleting useless spammy comments from your WordPress blog posts? Comment Link Remove WordPress plugin removes author link and any other links from the user comments.
												</p>
												<p>
													<a href="https://www.quantumcloud.com/products/comment-link-remove/" target="_blank">
														Download Now
													</a>
												</p>
											</div>
										</div>

									</div>
									
								</div>		
							</div>
							<!--Plugins:End-->
								</div>
							</div><!---tab-content-main---->
							
							<div id="tab-three" class="tab-content-main">
								<div class="container-wrapper">
									<div class="button-plugin service-button-quote">
										<a href="https://www.quantumcloud.com/request-quote/" class="feature-button button-blue" target="_blank">
											Request a Quote
										</a>
									</div>
									<div class="clear"></div>
											<!--Plugins:Start-->
									<div class="tlist-wrapper-x18">
										<div class="tlist-holder-x18">
											<div class="bottom-row-x18">
												<div class="single-item-x18">					
													
													<div class="content-box-x18 service-heading">
														<a href="https://www.quantumcloud.com/services/" target="_blank">
															<h2>WordPress Related Services</h2>
														</a>
														<p>
															Creative and custom web design to maximize user experience through responsive, eye catching, user-friendly and interactive functionality.
														</p>
														
													</div>
												</div>
												<div class="single-item-x18">					
													 
													<div class="content-box-x18">
														<a href="https://www.quantumcloud.com/services/" target="_blank">
															<h2>
																Custom WordPress Theme Design
															</h2>
														</a>
														<p>
															Your business is unique. Your website deserves to look and behave uniquely too. Stand out from the crowd. When you order our Custom WordPress Theme Design Service, we create a unique design based on your preferences.
														</p>
														
													</div>
												</div>
												<div class="single-item-x18">					
													 
													<div class="content-box-x18">
														<a href="https://www.quantumcloud.com/services/" target="_blank">
															<h2>
																Custom WordPress Plugin Development
															</h2>
														</a>
														<p>
															WordPress Plugin repository has close to 100 thousand free plugins. And more are available as commercial plugins. But the problem is, they do not always do exactly what you need for your own website.
														</p>
													   
													</div>
												</div>
												<div class="single-item-x18">					
													 
													<div class="content-box-x18">
														<a href="https://www.quantumcloud.com/services/" target="_blank">
															<h2>
																Conversion to WordPress Website
															</h2>
														</a>
														<p>
															If you currently have a Static website or if your current website is based on another CMS that is difficult to use or does not meet your requirements, we can help. 
														</p>
													   
													</div>
												</div>
												<div class="single-item-x18">					
													 
													<div class="content-box-x18">
														<a href="https://www.quantumcloud.com/services/" target="_blank">
															<h2>
																WordPress Theme Customization
															</h2>
														</a>
														<p>
															Liked or bought a Premium WordPress theme and want to customize it for your website? Our Premium WordPress Theme Customization Service can help. We will customize your chosen WordPress theme.
														</p>
													   
													</div>
												</div>
												<div class="single-item-x18">					
													
													<div class="content-box-x18">
														<a href="https://www.quantumcloud.com/services/" target="_blank">
															<h2>
																WordPress Website Maintenance Service
															</h2>
														</a>
														<p>
															Integration with 3rd party APIs or creating an API service for your own systems - we can do both. SOAP, JSON, XML or any REST API based work - you can count on us. We have worked with APIs from Expedia.
														</p>
														
													</div>
												</div>
												
						
											</div>
											
										</div>		
									</div>
									<!--Services:End-->
									
									<div class="tlist-wrapper-x18">
										<div class="tlist-holder-x18">
											<div class="bottom-row-x18">
												<div class="single-item-x18">					
													<div class="icon-box-service-main">
														<div class="icon-box-service">
														   <img src="<?php echo $this->relative_folder_url; ?>/images/icon-pack/service-08.png" alt="sld"/>
														</div>
													</div>
													<div class="content-box-x18 service-heading">
														<a href="https://www.quantumcloud.com/services/" target="_blank">
															<h2>
																Other Services
															</h2>
														</a>
														<p>
															Creative and custom web design to maximize user experience through responsive, eye catching, user-friendly and interactive functionality.
														</p>
														<p></p>
													</div>
												</div>
												<div class="single-item-x18">					
													 <div class="icon-box-service-main">
														<div class="icon-box-service">
														   <img src="<?php echo $this->relative_folder_url; ?>/images/icon-pack/service-02.png" alt="sld"/>
														</div>
													</div>
													<div class="content-box-x18">
														<a href="https://www.quantumcloud.com/services/" target="_blank">
															<h2>
																Advanced Web Development
															</h2>
														</a>
														<p>
															Whether you need a SAAS web application, CRM or custom extension to your favorite CMS like WordPress we can create elegant, custom web applications specific to your own and unique requirements.
														</p>
														<p></p>
													</div>
												</div>
												<div class="single-item-x18">					
													 <div class="icon-box-service-main">
														<div class="icon-box-service">
														   <img src="<?php echo $this->relative_folder_url; ?>/images/icon-pack/service-07.png" alt="sld"/>
														</div>
													</div>
													<div class="content-box-x18">
														<a href="https://www.quantumcloud.com/services/" target="_blank">
															<h2>
																Responsive, Mobile Friendly UI/UX
															</h2>
														</a>
														<p>We create websites that work equally well on all devices in current market and all devices that may come in future. We use Responsive Web Design (RWD) technique to future proof your website. </p>
														<p></p>
													</div>
												</div>
												<div class="single-item-x18">					
													 <div class="icon-box-service-main">
														<div class="icon-box-service">
														   <img src="<?php echo $this->relative_folder_url; ?>/images/icon-pack/service-04.png" alt="sld"/>
														</div>
													</div>
													<div class="content-box-x18">
														<a href="https://www.quantumcloud.com/services/" target="_blank">
															<h2>
																Mobile App Development
															</h2>
														</a>
														<p>
															Combining native and hybrid mobile app development expertise, we offer iOS and Android mobile development under one budget. We specialize in cross-platform and hybrid mobile apps.
														</p>
														<p></p>
													</div>
												</div>
												<div class="single-item-x18">					
													 <div class="icon-box-service-main">
														<div class="icon-box-service">
														   <img src="<?php echo $this->relative_folder_url; ?>/images/icon-pack/service-06.png" alt="sld"/>
														</div>
													</div>
													<div class="content-box-x18">
														<a href="https://www.quantumcloud.com/services/" target="_blank">
															<h2>
																Custom Website Design
															</h2>
														</a>
														<p>
															Our dedicated team of web designers are solutions-driven and client-focused. Our creative process starts by listening and responding to your business needs and budgetary parameters. Each design is a unique creative that has been carefully crafted to snugly fit your company's scope,
														</p>
														<p></p>
													</div>
												</div>
												<div class="single-item-x18">					
													<div class="icon-box-service-main">
														<div class="icon-box-service">
														   <img src="<?php echo $this->relative_folder_url; ?>/images/icon-pack/service-05.png" alt="sld"/>
														</div>
													</div>
													<div class="content-box-x18">
														<a href="https://www.quantumcloud.com/services/" target="_blank">
															<h2>
																Branding Solution
															</h2>
														</a>
														<p>
															A complete Corporate Identity includes professional logo design, letterhead, business cards along with your website. Our creative team is up to provide you with a complete branding solution so you can have a consistent presence across all medias.
														</p>
														<p></p>
													</div>
												</div>

												<div class="button-plugin">
													<a href="https://www.quantumcloud.com/request-quote/" class="feature-button button-blue" target="_blank">
														Request a Quote
													</a>
												</div>
						
											</div>
											
										</div>		
									</div>
								</div>
							</div><!---tab-content-main---->
					 </div>       
				  
					<script type="text/javascript">
						jQuery(document).ready(function($) {
						
						 var myAnimations = {
						 show: { effect: "slideDown", duration: 1000 }

						  };
						  $("#tabs").tabs(myAnimations);

						});
					</script>
					
				</div>
				
			<?php
		} //End of qcpromo_support_page_callback_function
		
		
	
	} //End of the class QcSupportAndPromoPage


} //End of class_exists


/*
* Create Instance, set instance variables and then call appropriate worker.
*/

//Supply Unique Promo Page Slug as the constructor parameter of the class QcSupportAndPromoPage. ex: sld-page-2124a to the constructor

//Please create an unique instance for your use, example: $instance_sldf2

$instance_ilistf_suport = new QcSupportAndPromoPage('ilist-free-page-123za');

if( is_admin() )
{
	$instance_ilistf_suport->plugin_menu_slug = "edit.php?post_type=ilist";
	$instance_ilistf_suport->plugin_name = "Infographic and List Builder iList";
	$instance_ilistf_suport->show_promo_page();
}
