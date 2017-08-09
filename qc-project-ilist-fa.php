<?php 
function ilist_modal_fa() {
	    $icons = get_option( 'fa_icons_new' );
        if ( ! $icons ) {
         
         $data = file_get_contents( QCOPD_ASSETS_URL1 . '/data/fa-data.txt' );
		$data = explode("\n",$data);
		$icons = array();
		foreach($data as $key=>$val){
			$val = explode('=>',$val);
			$title = $val[0];
			$class = explode(',',$val[1]);
			foreach($class as $v=>$k){
				if(strlen($k)>2){
					$icons[$title][] = trim($k);
				}
			}
		}
          update_option( 'fa_icons_new', $icons );
        }
        
?>
<div class="fa-field-modal" id="fa-field-modal" style="display:none">
  <div class="fa-field-modal-close">&times;</div>
  <h1 class="fa-field-modal-title"><?php _e( 'Select Font Awesome Icon (<span style="color:red">Pro Version only</span>)', 'fa-field' ); ?></h1>

  <div class="fa-field-modal-icons">
		<form action="#">
			<fieldset>
				<input type="search" name="search" value="" id="id_search" /> <span class="loading">Loading...</span>
			</fieldset>
		</form>
	<?php if ( $icons ) : ?>

	  <?php foreach ( $icons as $head=>$iconlist ) : ?>
		<div class="qcld_ilist_fa_section" style="display:block;overflow: hidden;"><h2><?php echo $head; ?></h2>
		<?php foreach ( $iconlist as $s=>$cls ) : ?>
		<a href="https://www.quantumcloud.com/iList/" target="_blank"><div class="fa-field-modal-icon-holder" data-icon="<?php echo $cls; ?>">
		  <div class="icon">
			<i class="fa <?php echo $cls; ?>"></i>
		  </div>
		  <div class="label">
			<?php echo $cls; ?>
		  </div>
		</div></a>

	  <?php endforeach; ?>
	  </div>
	  <?php endforeach; ?>

	<?php endif; ?>
  </div>
</div>

<?php
}
add_action( 'admin_footer', 'ilist_modal_fa');