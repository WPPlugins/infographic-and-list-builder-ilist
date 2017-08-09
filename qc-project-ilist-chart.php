<?php 
function ilist_modal_chart() { 
?>

<div class="ilist-chart-field-modal" id="ilist-chart-field-modal" style="display:none">
  <div class="ilist-chart-field-modal-close">&times;</div>
  <h1 class="ilist-chart-field-modal-title"><?php _e( 'Create Chart', 'ilist-chart-field' ); ?></h1>
 <div style="position: absolute;right: 59px;">
<input type="button" value="Generate Chart" class="button button-primary button-large getallvalue" />
</div>
  <div class="ilist-chart-field-modal-icons">

		<div class="form-group">
			<label for="charttype">Select Chart Type</label>

			<select id="charttype" class="form-control">
				<option value="line">Line</option>
				<option value="bar">Bar</option>
				<optgroup label="Pro Features">
					<option  value="radar" >Radar</option>
					<option  value="polarArea" >Polar Area</option>
					<option  value="pie" >Pie</option>
					<option  value="doughnut" >Doughnut</option>
				</optgroup>
				
				
			</select>
		</div>
		<div class="form-group">
			<label for="datasettitle">Chart Title</label>
			<input type="text" id="charttitle" required/>
		</div>
		<div id="datasetcontainer" class="datasetcontainer">
			<p class="datasetheading">Dataset Area</p>
			<div class="form-group">
				<label for="datasettitle">Dataset Name</label>
				<input type="text" id="datasetname" required/>
			</div>
			<table id="datasettable" class="datasettable" cellspacing="0">
				<thead>
				<tr>
						<th class="manage-column column-cb check-column" scope="col">Label</th> 
						<th id="columnname" class="manage-column column-columnname" scope="col">Value</th>
						<th id="columnname" class="manage-column column-columnname" scope="col">Color</th>
				</tr>
				</thead>
				<tfoot>
					<tr>
						<th class="manage-column column-cb check-column" scope="col"><input type="button" id="chartaddrow" value="+ Add row" class="button button-primary button-large" /></th>
						<th class="manage-column column-columnname" scope="col"></th>
					</tr>
				</tfoot>
				<tbody>
					<tr>
						<td class="check-column" scope="row"><input type="text" name="label[]" /></td>
						<td class="column-columnname"><input type="text" name="value[]" /></td>
						<td class="column-columnname"><input type="text" name="bgcolor[]" class="color-field" /></td>
						
						<td class="column-columnname"><a href="javascript:void(0);" class="removerow">Remove</a></td>
					</tr>
				</tbody>
			</table>
			
		</div>
		<div id="configurationcontainer" class="configurationcontainer">
			<p class="datasetheading">Configuration Area</p>

			<table id="configtable" class="configtable" cellspacing="0">
				<tr>
					<td>Background Color</td>
					<td><input type="text" id="backgroundcolor" name="backgroundcolor" class="color-field" /></td>
				</tr>
				<tr>
					<td>Border Color</td>
					<td><input type="text" id="bordercolor" name="bordercolor" class="color-field" /></td>
				</tr>
				<tr>
					<td>pointer Style</td>
					<td><select name="pointerstyle" id="pointerstyle">
						<option value="circle">circle</option>
						<option value="triangle">triangle</option>
						<option value="rect">rect</option>
						<option value="rectRounded">rectRounded</option>
						<option value="rectRot">rectRot</option>
						<option value="cross">cross</option>
						<option value="crossRot">crossRot</option>
						<option value="star">star</option>
						<option value="line">line</option>
						<option value="dash">dash</option>
					</select></td>
				</tr>
				<tr id="linestyle">
					<td>Line Style</td>
					<td><select name="lstyle" id="lstyle">
						<option value="">Default</option>
						<option value="stepped">Stepped</option>
						<option value="filled">filled</option>
						<option value="dashed">Dashed</option>
					</select></td>
				</tr>				
			</table>
			
		</div>		
		<input type="button" value="Generate Chart" class="button button-primary button-large getallvalue" />
		


  </div>
</div>

<?php
}
add_action( 'admin_footer', 'ilist_modal_chart');

add_shortcode('qcld-chart', 'qcilist_textlist_full_shortcode_chart');
function qcilist_textlist_full_shortcode_chart($atts = array()){
	extract( shortcode_atts(
		array(
			'label' => 'january,February,March,April',
			'value' => '80,-30,20,-50',
			'type' => 'line',
			'title' => 'Demo Title',
			'datasetname'	=>'Demo',
			'backgroundcolor' => '',
			'bgcolor' => '',
			'bordercolor' => '',
			'pointerstyle' => 'circle',
			'linestyle' => ""
		), $atts
	));
	$label = explode(',',$label);
	$label = '"'.implode('","',$label).'"';
	
	
	
?>

	<canvas id="myChart" ></canvas>

<script>
var ctx = document.getElementById("myChart");

var myChart = new Chart(ctx, {
    type: "<?php echo $type; ?>",
    data: {
        labels: [<?php echo $label; ?>],
        datasets: [{
            label: '<?php echo $datasetname ?>',
            data: [<?php echo $value; ?>],
			<?php 
			if($bgcolor!='' and $type!='line' and $type!='radar'){
			?>
			backgroundColor: [<?php echo $bgcolor ?>],
			<?php
			}else{
			?>
			backgroundColor: '<?php echo $backgroundcolor ?>',
			<?php
			}
			?>
			
            <?php 
			if($bordercolor!=''){
			?>
			borderColor: '<?php echo $bordercolor ?>',
			<?php
			}
			?>
            pointRadius: 8,
			pointHoverRadius: 11,
            //borderWidth: 3,
			<?php 
			if($linestyle=='filled'){
			?>
			fill: true,
			<?php
			}elseif($linestyle=='stepped'){
			?>
			steppedLine: true,
			fill: false,
			<?php 
			}elseif($linestyle=='dashed'){
			?>
			borderDash: [5, 5],
			fill: false,
			<?php
			}else{
			?>
			fill: false,
			<?php
			}
			?>

			
        }]
    },
    options: {
		elements: {
					point: {
						pointStyle: '<?php echo $pointerstyle ?>'
					}
				},
				title: {
                        display: true,
                        text: '<?php echo $title; ?>'
                    },
		tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
			scale: {
              ticks: {
                beginAtZero: true
              }
            }

    }
});
</script>
<?php
	
}





