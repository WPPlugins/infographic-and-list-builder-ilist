jQuery(document).ready(function($)
{
		
			
	$('#qcld_sl_template_text').on ("click", function(){
		$.post(
			ajaxurl,
			{
				action : 'show_ilist_templates',
				list_type: 'textlist'
			},
			function(data){
				$('#wpwrap').append(data);
			}
		)
	})
	$('#qcld_sl_template_mix').on ("click", function(){
		$('#ajax-modal').show();
		$.post(
			ajaxurl,
			{
				action : 'show_ilist_templates',
				list_type: 'infographic'
				
			},
			function(data){
				
				$('#wpwrap').append(data);
				
			}
		)
	})
	$('#qcld_sl_template_image').on ("click", function(){
		
		$.post(
			ajaxurl,
			{
				action : 'show_ilist_templates',
				list_type: 'graphiclist'
				
			},
			function(data){
				
				$('#wpwrap').append(data);
				
			}
		)
	})
	
	
	$(document).on( 'click', '.modal-content-template .close', function(){
        $(this).parent().parent().remove();
    })
	

	
	$(document).on( 'click', '.ilist_list_elem', function(){
        var data = $(this).attr('data');
		var gettype = $('.modal-content-template').attr('data');
		var getid= '';
		if(gettype=='graphiclist'){
			getid = 'qcld_sl_template_image';
		}else if(gettype=='infographic'){
			getid = 'qcld_sl_template_mix';
		}else{
			getid = 'qcld_sl_template_text';
		}
		$('#'+getid).val(data);
		$(this).parent().parent().parent().parent().remove();
    })
	
	
	var iDiv = document.createElement('div');
	iDiv.id = 'ilistimagecontainer1';
	iDiv.className = 'ilistimagecontainer1';
	
	var url = $('#ilist_path').attr('data-path');
	var html = '<a href="https://www.quantumcloud.com/iList" target="_blank"><img style="width: 780px;max-width:100%;height:auto;" src="'+url+'/js/ilist-pro.jpg" /></a>'
	iDiv.innerHTML = html;
	if(document.getElementsByClassName('cmb2-id-qcld-text-group')[0]){
		document.getElementsByClassName('cmb2-id-qcld-text-group')[0].appendChild(iDiv);
	}
	
});


