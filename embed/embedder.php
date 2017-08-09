<?php




/*Load Global Scripts*/
add_action('wp_enqueue_scripts', 'qcld_load_embed_scripts');

function qcld_load_embed_scripts()
{
    //FontAwesome
    wp_enqueue_style('ilist-embed-form-css', QCOPD_URL1 . '/embed/css/embed-form.css');

    //Scripts
    wp_enqueue_script('ilist-embed-form-script', QCOPD_URL1 . '/embed/js/embed-form.js', array('jquery'));

}


// Load template for embed link page url
function qcld_load_embed_link_template($template)
{
    if (is_page('embed-ilist')) {
        return dirname(__FILE__) . '/qcld-embed-link.php';
    }
    return $template;
}

add_filter('template_include', 'qcld_load_embed_link_template', 99);


// Create embed page when plugin install or activate

add_action('init', 'qcld_create_embed_page');
function qcld_create_embed_page()
{

    if (get_page_by_title('Embed iList') == NULL) {
        //post status and options
        $post = array(
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_author' => get_current_user_id(),
            'post_date' => date('Y-m-d H:i:s'),
            'post_status' => 'publish',
            'post_title' => 'Embed iList',
            'post_type' => 'page',
        );
        //insert page and save the id
        $embedPost = wp_insert_post($post, false);
        //save the id in the database
        update_option('hclpage', $embedPost);
    }
}

function ilist_embedoption_track(){
	$embed_link_button = ot_get_option('sl_enable_embed_list');

	if ($embed_link_button == 'on') {
		add_action('qcsl_after_add_btn', 'qcld1_custom_embedder');
	}
}
add_action('init', 'ilist_embedoption_track');
function qcld1_custom_embedder($shortcodeAtts)
{
    global $post;
$credit_title = ot_get_option('sl_embed_title');
$credit_link = ot_get_option('sl_embed_link');
    $pagename = $post->post_name;

    if ($pagename != 'embed-link') {
        ?>

        <a style="float:right" class="button-link-ilist js-open-modal" href="#" data-modal-id="popup_ilist"
           data-url="<?php bloginfo('url'); ?>/embed-ilist"
           data-order="<?php echo esc_html__($shortcodeAtts['order']); ?>"
           data-mode="<?php echo esc_html__($shortcodeAtts['mode']); ?>"
           data-column="<?php echo esc_html__($shortcodeAtts['column']); ?>"
           data-style="<?php echo esc_html__($shortcodeAtts['style']); ?>"
           data-search="<?php   ?>"
           data-category="<?php echo esc_html__($shortcodeAtts['category']); ?>"
           data-listid="<?php echo esc_html__($shortcodeAtts['list_id']); ?>"
           data-ctitle="<?php echo esc_html__($credit_title); ?>"
           data-clink="<?php echo esc_html__($credit_link); ?>"
           data-upvote="<?php echo esc_html__($shortcodeAtts['upvote']); ?>"> Generate Embed Code </a>
        <div id="popup_ilist" class="modal-box">
            <header>
                <a href="#" class="js-modal-close close">×</a>
                <h3>Generate Embed Code For This List</h3>
            </header>
			 <div class="modal-body">
                <div class="iframe-css">
                    <div class="iframe-main">
                        <div class="ifram-row">
                            <div class="ifram-sm">
                                <span>Width: (in '%' or 'px')</span>
                                <input style="height: 36px;" id="igwidth" name="igwidth" type="text" value="100">
                            </div>
                            <div class="ifram-sm" style="width: 70px;">
                                <span>&nbsp;</span>
                                <select name="igsizetype" class="iframe-main-select">
									
                                    <option value="%">%</option>
									<option value="px">px</option>
                                    
                                </select>
                            </div>
                            <div class="ifram-sm">
                                <span>Height: (in 'px')</span>
                                <input style="height: 36px;" id="igheight" name="igheight" type="text" value="400">
                            </div>
                            <div class="ifram-sm">
                                <span>&nbsp;</span>
                                <a class="btn icon icon-code" id="generate-igcode_ilist"
                                   onclick="">Generate & Copy</a>
                                </select>
                            </div>
                        </div>

                        <div class="ifram-row">
							<div class="ifram-lg">
								<span class="qcld-span-label">Generated Code</span>
								<br>
								<textarea id="ilist_igcode_textarea" class="ilist_igcode_textarea" name="igcode" style="width:100%; height:120px;"
										  readonly="readonly"></textarea>
								<p class="guideline">Hit "Generate & Copy" button to generate embed code. It will be copied
									to your Clipboard. You can now paste this embed code inside your website's HTML where
									you want to show the List.</p>
							</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    <?php }
}