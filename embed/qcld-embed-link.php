<?php 
wp_head();
add_action('wp_enqueue_scripts', 'qcilist_embed_load_all_scripts');
function qcilist_embed_load_all_scripts(){
    wp_enqueue_style( 'ilist_embed_fontawesome-css', QCOPD_ASSETS_URL1 . '/css/font-awesome.min.css');
    wp_enqueue_style( 'ilist_embed_custom-css', QCOPD_ASSETS_URL1 . '/css/sl-directory-style.css');
    wp_enqueue_style( 'ilist_embed_custom-rwd-css', QCOPD_ASSETS_URL1 . '/css/sl-directory-style-rwd.css');
    wp_enqueue_style( 'ilist_custom-rwd-embed', QCOPD_URL1 . '/embed/css/embed-form.css');

    //Scripts
    

    wp_enqueue_script( 'ilist_embed_custom-script', QCOPD_ASSETS_URL1 . '/js/directory-script.js', array('jquery', 'ilist_embed_grid-packery'));
    wp_enqueue_script( 'ilist_embed_custom-embed_form', QCOPD_URL1 . '/embed/js/embed-form.js', array('jquery', 'ilist_embed_grid-packery'));
}
?>

<script type="text/javascript">
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>

<style>
    .sld-add .sld-add-btn {
        display: none;
    !important;
    }
</style>



<?php


$order = sanitize_text_field(esc_html__($_GET['order']));
$mode = sanitize_text_field(esc_html__($_GET['mode']));
$column = sanitize_text_field(esc_html__($_GET['column']));
$style = sanitize_text_field(esc_html__($_GET['style']));
$search = sanitize_text_field(esc_html__($_GET['search']));
$category = sanitize_text_field(esc_html__($_GET['category']));
$upvote = sanitize_text_field(esc_html__($_GET['upvote']));
$list_id = sanitize_text_field(esc_html__($_GET['list_id']));

echo do_shortcode('[qcld-ilist mode=' . $mode . ' column="' . $column . '" upvote="' . $upvote . '" list_id='.$list_id.'] '); 
wp_footer();
?>





