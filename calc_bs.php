<?php
/*
  $Id: contact_us.php,v 1.42 2003/06/12 12:17:07 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  //include calculator classes
	require(DIR_WS_CLASSES. 'Borderstone.php');
	require(DIR_WS_CLASSES. 'Pool.php');
	
	require(DIR_WS_CLASSES. 'Calculator.php');
	require(DIR_WS_CLASSES. 'StoneCalculator.php');
	require(DIR_WS_CLASSES. 'CalculationStrategy.php');
	require(DIR_WS_CLASSES. 'StoneCalculationStrategy.php');
	
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CALC_BS);

  $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
    $formData = array(
        array('title' => RC_FORM_STONECATEGORY, 'value' => $_POST['stonecategory']),
        array('title' => RC_FORM_STONEMATERIAL, 'value' => $_POST['stonematerial']),
        array('title' => RC_FORM_STONETYPE, 'value' => $_POST['stonetype']),
        array('title' => RC_FORM_TILES, 'value' => $_POST['tiles']),
        
        
		array('title' => RC_FORM_POOLLAYOUT, 'value' => $_POST['poollayout']),	
        array('title' => RC_FORM_POOLSIZE_L, 'value' => $_POST['poolsize_l']),
        array('title' => RC_FORM_POOLSIZE_B, 'value' => $_POST['poolsize_b']),
        array('title' => RC_FORM_POOLSIZE_D, 'value' => $_POST['poolsize_d']),
    );
    
    $message = "";
    $message .= RC_TEXT_TITLE;
    $message .= "\r\n";
    $message .= "\r\n";
	
	//sanitize formData
	for($i=0; $i < count($formData); $i++){
		$formData[$i]['value'] = tep_db_prepare_input($formData[$i]["value"],true);
	}
    
    foreach($formData as $item)
    {	
        $message .= $item["title"]." :";
        $message .= tep_db_prepare_input($item["value"],true);
        $message .= "\r\n";
    }
    
	//new Pool with parameters: $depth, $_diameter, $_length, $_shape, $_type, $_width
	$pool = new Pool(0,$formData[7]['value'],$formData[5]['value'], $formData[4]['value'], 0, $formData[6]['value']);

	//new Borderstone with parameters: $_category, $_color, $_length, $_material, $_tiles, $_type, $_width
   $borderstone = new BorderStone($formData[0]['value'], 0, 50, $formData[1]['value'], $formData[3]['value'], $formData[2]['value'], 35);
   
   $calculationStrategy = new StoneCalculationStrategy();
   
   $array= $calculationStrategy->calculatePrice($pool, $borderstone);
 	
	$language_id = 4;
	$options_id = 168;
	$reference_internal = 'ZATX1680';
/*	
	$statement = "SELECT products_reference_internal, products_price"
		. " FROM ". TABLE_PRODUCTS
		." WHERE products_reference_internal = " . (int)$reference_internal
		." LIMIT 1";
		$query = tep_db_query($statement);
		$array1 = tep_db_fetch_array($query);
	//var_dump($propattribute);
*/	
	$statement = "SELECT options_id, products_options_values_name
	FROM products_propattributes pa
	LEFT JOIN products_propoptions_values_to_product_options pb ON pa.options_values_id = pb.products_options_values_to_products_options_id
	LEFT JOIN products_propoptions_values pc ON pc.products_options_values_id = pa.options_values_id
	INNER JOIN products p ON pa.products_id = p.products_id
	WHERE language_id =4
	AND products_reference_internal =  'ZATA1430'
	GROUP BY options_id";
		
		$query = tep_db_query($statement);
	//	echo $propAttribute['products_options_values_name'];
	
		$rows = tep_db_fetch_all($query);
	
		var_dump($rows);
   
    $subject = RC_TEXT_TITLE." > Contact Robotcleaner";
 
  }
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('contact_robotcleaner.php'));
   
?>


<?php require(DIR_WS_CUR_TEMPLATE . 'header.php'); ?>
<!-- header_eof //-->
<link rel="stylesheet" href="/templates/default/css/ajax-form-validation.css" type="text/css">
<link rel="stylesheet" href="/templates/default/css/calc_bs.css" type="text/css">
<link rel="stylesheet" href="/templates/default/css/bootstrap-switch.min.css" type="text/css">
<script src="/templates/default/js/bootstrap-switch.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

	// Place ID's of all required fields here.
	required = [];
	// If using an ID other than #email or #error then replace it here
	email = $("#email");
	errornotice = $("#error");
	// The text to show up within a field when it is incorrect
	emptyerror = "";
	emailerror = "";
	
	$("#calc_bs").submit(function(){	
		
		//Validate required fields
		for (i=0;i<required.length;i++) {
			var input = $('#'+required[i]);
			if ((input.val() == "") || (input.val() == emptyerror)) {
				input.addClass("needsfilled");
				input.val(emptyerror);
				errornotice.fadeIn(750);
			} else {
				input.removeClass("needsfilled");
			}
		}
	
	//Validate radio buttons
	radio_btns = ["stonecategory", "stonematerial", "stonetype", "poollayout"];	//name attributes of required radio groups
	for(i=0;i<radio_btns.length;i++){
		var input = $( 'input:radio[name="'+radio_btns[i]+'"]');
		var label = $('label[for="'+radio_btns[i]+'"]');
		if(input.is(":checked")){
			label.removeClass("needsfilled");
			input.removeClass("needsfilled");
		} else {
			label.addClass("needsfilled");
			input.addClass("needsfilled");
			errornotice.fadeIn(750);
		}	
	}
		
		//if any inputs on the page have the class 'needsfilled' the form will not submit
		if ($(":input").hasClass("needsfilled")) {
			
			$('html, body').animate({scrollTop:0}, 'fast');
			return false;
		} else {
	
			errornotice.hide();
			return true;
		}
	});
	
	// Clears any fields in the form when the user clicks on them
	$(":input").focus(function(){		
	   if ($(this).hasClass("needsfilled") ) {
			$(this).val("");
			$(this).removeClass("needsfilled");
	   }
	});
	
	//Hide and show radio input fields when clicked
	$('input:radio[name="stonecategory"]').change(function(){
		if($(this).val() == "1"){     							//if 'natuursteen'
			$("#material_group2").hide();
			$("#material_group1").fadeIn( "slow" );
			$("#tile_type_neus").hide();
			$("#tile_type_L").hide();
			$("#tile_type_I").show();
		}else{														
			$("#material_group1").hide();					//if 'beton'
			$("#material_group2").fadeIn( "slow" );
			$("#tile_type_neus").hide();
			$("#tile_type_L").hide();
			$("#tile_type_I").show();
		}
	});
				
	$('input:radio[name="stonematerial"]').change(function(){
		if($(this).val() == "1"){								//if 'beige graniet
			$("#tile_type_neus").hide();
			$("#tile_type_L").hide();
			$("#tile_type_I").show();
		}
		else if($(this).val() == "2"){							//if 'New Jasberg'
			$("#tile_type_neus").show();
			$("#tile_type_L").hide();
			$("#tile_type_I").hide();
		}
		else if($(this).val() == "3"){							//if 'Black Panda'
			$("#tile_type_neus").show();
			$("#tile_type_L").hide();
			$("#tile_type_I").show();
		}
		else{
			$("#tile_type_neus").hide();					//if 'Grey Mountain'
			$("#tile_type_L").hide();
			$("#tile_type_I").show();
		}
	});
				
	$('input:radio[name="poollayout"]').change(function(){
		if($(this).val() == "2"){      							//if 'rechthoekig met romeinse trap'
			$("#diameter_input-group").show();
			$("#length_input-group").show();
			$("#width_input-group").show();
			required=["poolsize_l", "poolsize_b", "poolsize_d"];    //fields length, width and diameter are required
		}
		else if($(this).val() == "4"){     //if 'rond'
			$("#diameter_input-group").show();
			$("#length_input-group").hide();
			$("#width_input-group").hide();
			required=["poolsize_d"];      					//field diameter is required
		}
		else{
			$("#length_input-group").show();
			$("#width_input-group").show();
			$("#diameter_input-group").hide();
			required=["poolsize_l", "poolsize_b"];		//fields length and width are required
		}
	});
				
	//Make switch from checkbox
	$("[name='tiles']").bootstrapSwitch();
	$.fn.bootstrapSwitch.defaults.size = 'small';
				
	//show tooltip on hover green question mark
	$("[rel=tooltip_tile]").tooltip({ placement: 'right'});
			
	
	
});	
</script>
<h3 class="section-header"><?php echo RC_TEXT_TITLE; ?></h3>


<?php
if ($messageStack->size('contact') > 0) {
	echo "<br />";
	if(strpos(strtolower($messageStack->messages[0]['params']),'success') !== false) { 
		echo "<div class=\"alert alert-success\" role=\"alert\">"; 
	}else{
		echo "<div class=\"alert alert-danger\" role=\"alert\">";
	}
	echo $messageStack->output('contact');
echo "</div>";	  
}
?>

<?php
  if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
?>
<?php echo TEXT_SUCCESS; ?>
<br /><br />
<?php echo layout_button('submit', IMAGE_BUTTON_CONTINUE, tep_href_link(FILENAME_DEFAULT, '', 'SSL'), '', ''); ?>
<?php
  } else {
?>

<p id="error"><?php echo QUANDA_ERROR_HEADING; ?>

<?php echo tep_draw_form('calc_bs', tep_href_link('calc_bs.php', 'action=send'), 'post', 'id="calc_bs"');  ?>


<div class="form-group">	
<label for="stonecategory" class="col-sm-3 control-label"><?php echo RC_FORM_STONECATEGORY; ?></label>
<div class="col-sm-9">
<?php echo tep_draw_radio_field('stonecategory', '1', '', 'class="radio_item"  id="stonecategory_1"'); ?><label class="label_item" for="stonecategory_1"> <?php echo RC_FORM_STONECATEGORY_1; ?>
	<?php echo tep_image("/t.php?src=images/categories/black_pantherbinnnhoek.jpg&amp;w=155&amp;h=155&amp;zc=2", '', '', '', 'class="img-responsive"'); ?>
</label>
	
<?php echo tep_draw_radio_field('stonecategory', '2', '', 'class="radio_item" id="stonecategory_2"'); ?> <label class="label_item" for="stonecategory_2"> <?php echo RC_FORM_STONECATEGORY_2; ?>
	<?php echo tep_image("/t.php?src=images/categories/boordsteen.JPG&amp;w=154&amp;h=142&amp;zc=2", '', '', '', 'class="img-responsive"'); ?>
</label>
</div>
</div>

<div class="form-group">	
<label for="stonematerial" class="col-sm-3 control-label"><?php echo RC_FORM_STONEMATERIAL; ?></label>
<div class="col-sm-9">
	<div id="material_group1" style="display: none;">
    <?php echo tep_draw_radio_field('stonematerial', '1', '', 'class="radio_item" id="stonematerial_1"'); ?> <label class="label_item" for="stonematerial_1"> <?php echo RC_FORM_STONEMATERIAL_1; ?>
		<?php echo tep_image("/t.php?src=images/categories/zata1410.jpg&amp;w=155&amp;h=155&amp;zc=2", '', '', '', 'class="img-responsive"'); ?>
	</label>

	<?php echo tep_draw_radio_field('stonematerial', '2', '', 'class="radio_item" id="stonematerial_2"'); ?> <label class="label_item" for="stonematerial_2"> <?php echo RC_FORM_STONEMATERIAL_2; ?>
		<?php echo tep_image("/t.php?src=images/categories/zata1200.jpg&amp;w=155&amp;h=155&amp;zc=2", '', '', '', 'class="img-responsive"'); ?>
		</label>
	<?php echo tep_draw_radio_field('stonematerial', '3', '', 'class="radio_item" id="stonematerial_3"'); ?> <label class="label_item" for="stonematerial_3"> <?php echo RC_FORM_STONEMATERIAL_3; ?>
		<?php echo tep_image("/t.php?src=images/categories/zata1330.jpg&amp;w=155&amp;h=155&amp;zc=2", '', '', '', 'class="img-responsive"'); ?>
		</label>
	<?php echo tep_draw_radio_field('stonematerial', '4', '', 'class="radio_item" id="stonematerial_4"'); ?> <label class="label_item" for="stonematerial_4"> <?php echo RC_FORM_STONEMATERIAL_4; ?>
		<?php echo tep_image("/t.php?src=images/categories/zata1150.jpg&amp;w=155&amp;h=155&amp;zc=2", '', '', '', 'class="img-responsive"'); ?>
		</label>
	</div>
	<div id="material_group2" style="display: none;">
	<?php echo tep_draw_radio_field('stonematerial', '5', '', 'class="radio_item" id="stonematerial_5"'); ?> <label class="label_item" for="stonematerial_5"> <?php echo RC_FORM_STONEMATERIAL_5; ?>
		<?php echo tep_image("/t.php?src=images/categories/boordsteen.JPG&amp;w=155&amp;h=142&amp;zc=2", '', '', '', 'class="img-responsive"'); ?>
		</label>
	<?php echo tep_draw_radio_field('stonematerial', '6', '', 'class="radio_item" id="stonematerial_6"'); ?> <label class="label_item" for="stonematerial_6"> <?php echo RC_FORM_STONEMATERIAL_6; ?>
		<?php echo tep_image("/t.php?src=images/categories/boordsteen.JPG&amp;w=155&amp;h=142&amp;zc=2", '', '', '','class="img-responsive"'); ?>
		</label>
	</div>

</div> 
</div>

<div class="form-group">	
<label for="stonetype" class="col-sm-3 control-label"><?php echo RC_FORM_STONETYPE; ?></label>
<div class="col-sm-9">
	<div class="inline" id="tile_type_neus" >
    <?php echo tep_draw_radio_field('stonetype', '1', '', 'class="radio_item" id="stonetype_1"'); ?> <label class="label_item" for="stonetype_1"> <?php echo RC_FORM_STONETYPE_1; ?>
	<?php echo tep_image("/t.php?src=images/categories/type_neus.png&amp;w=155&amp;h=73&amp;zc=2", '', '', '', 'class="img-responsive"'); ?>
	</label>
	</div>
	<div class="inline" id="tile_type_L">
	<?php echo tep_draw_radio_field('stonetype', '2', '', 'class="radio_item" id="stonetype_2"'); ?> <label class="label_item" for="stonetype_2"> <?php echo RC_FORM_STONETYPE_2; ?>
	<?php echo tep_image("/t.php?src=images/categories/type_L.png&amp;w=155&amp;h=73&amp;zc=2", '', '', '', 'class="img-responsive"'); ?>
	</label>
	</div>
	<div class="inline" id="tile_type_I">
	<?php echo tep_draw_radio_field('stonetype', '3', '', 'class="radio_item" id="stonetype_3"'); ?> <label class="label_item" for="stonetype_3"> <?php echo RC_FORM_STONETYPE_3; ?>
	<?php echo tep_image("/t.php?src=images/categories/type_I.png&amp;w=155&amp;h=73&amp;zc=2", '', '', '', 'class="img-responsive"' ); ?>
	</label>
	</div>
</div>
</div> 

<div class="clearfix"><hr /></div>

<div class="form-group">	
	<label for="tiles" class="col-sm-3 control-label"><?php echo RC_FORM_TILES; ?> <i class="green-question" rel="tooltip" title="Een rij tegels van 50 bij 50 cm naast de boordstenen" id="tooltip_tile">?</i>
	</label>
	<div class="col-sm-9">
		<?php echo tep_draw_checkbox_field('tiles', '', true, 'id=tiles data-on-text="' . RC_FORM_TILES_1 . '" data-off-text="'. RC_FORM_TILES_2 . '" checked'); ?> 
	</div>
</div>
  
<div class="clearfix"><hr /></div>
  
<div class="form-group">	
<label for="poollayout" class="col-sm-3 control-label"><?php echo RC_FORM_POOLLAYOUT; ?></label>
<div class="col-sm-9">
<?php echo tep_draw_radio_field('poollayout', '1', '', 'class="radio_item" id="poollayout_1"'); ?><label class="label_item" for="poollayout_1"> <?php echo RC_FORM_POOLLAYOUT_1; ?>
	<?php echo tep_image("/tools/winterzeilen/i/square.png", '', '155', '155', 'class="img-responsive"' ); ?>
	</label>
<?php echo tep_draw_radio_field('poollayout', '2', '', 'class="radio_item" id="poollayout_2"'); ?><label class="label_item" for="poollayout_2"> <?php echo RC_FORM_POOLLAYOUT_2; ?>
	<?php echo tep_image("/tools/winterzeilen/i/roman_right.png", '', '155', '155', 'class="img-responsive"' ); ?>
	</label>
<?php echo tep_draw_radio_field('poollayout', '3', '', 'class="radio_item" id="poollayout_3"'); ?><label class="label_item" for="poollayout_3"> <?php echo RC_FORM_POOLLAYOUT_3; ?>
	<?php echo tep_image("/tools/winterzeilen/i/ext_right.png", '', '155', '155', 'class="img-responsive"' ); ?>
	</label>
<?php echo tep_draw_radio_field('poollayout', '4', '', 'class="radio_item" id="poollayout_4"'); ?><label class="label_item" for="poollayout_4"> <?php echo RC_FORM_POOLLAYOUT_4; ?>
	<?php echo tep_image("/tools/winterzeilen/i/round.png", '', '155', '155', 'class="img-responsive"' ); ?>
	</label>
<?php echo tep_draw_radio_field('poollayout', '5', '', 'class="radio_item" id="poollayout_5"'); ?><label class="label_item" for="poollayout_5"> <?php echo RC_FORM_POOLLAYOUT_5; ?>
	<?php echo tep_image("/tools/winterzeilen/i/oval.png", '', '155', '155', 'class="img-responsive"' ); ?>
	</label>
	</div>
</div>
   
<div class="clearfix"><hr /></div>

	<label for="poolsize" class="col-sm-3 control-label"><?php echo RC_FORM_POOLSIZE; ?></label>	
	<div class="col-sm-9">
		<div class="form-group">
			<div class="input-group" id="length_input-group">
				<?php echo tep_draw_input_field('poolsize_l', '', 'id="poolsize_l" placeholder="'. RC_FORM_POOLSIZE_L .'" step="any" min="0" class="form-control"','number'); ?>
				<div class="input-group-addon">m</div>
			</div>
		</div>
		<div class="form-group">	
				<div class="input-group" id="width_input-group">
					<?php echo tep_draw_input_field('poolsize_b', '', 'id="poolsize_b" placeholder="' . RC_FORM_POOLSIZE_B .'" step="any" min="0" class="form-control"','number');?>
					<div class="input-group-addon">m</div>
				</div>
		</div>
		<div class="form-group">
				<div class="input-group" id="diameter_input-group">
					<?php echo tep_draw_input_field('poolsize_d', '', 'id="poolsize_d" placeholder="' . RC_FORM_POOLSIZE_D .'" step="any" min="0" class="form-control"','number');?>
					<div class="input-group-addon">m</div>
				</div>
				<?php echo layout_button('submit', 'Bereken', '', '', 'btn btn-primary margin-top green-button pull-right', '', 'right'); ?>
		</div>
	</div>


<div class="clearfix"><hr /></div>

<div class="panel panel-primary filterable">
	<div class="panel-heading">
		<h3 class="panel-title">Prijsofferte</h3>
	</div>
	<div class="table-responsive">
		<table class="table table-striped table-bordered" style="border-collapse:collapse">
			<thead>
				<tr>
					<th style="width: 20%"></th>
					<th style="width: 25%">Barcode</th>
					<th style="width: 10%">Aantal</th>
					<th style="width: 20%">Prijs per stuk</th>
					<th style="width: 25%">Totaal</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th scope="row">Boordsteen recht</th>
					<td></td>
					<td><?php echo $array['borderstonesStraight']; ?></td>
					<td></td>
					<td><?php echo $array['priceBorderstonesStraight']; ?></td>
				</tr>
				<tr>
					<th scope="row">Binnenhoek</th>
					<td></td>
					<td><?php echo $array['borderstonesInnerCorner']; ?></td>
					<td></td>
					<td><?php echo $array['priceBorderstonesInnerCorner']; ?></td>
				</tr>
				<tr>
					<th scope="row">Buitenhoek links</th>
					<td></td>
					<td><?php echo $array['borderstonesOuterCornerLeft']; ?></td>
					<td></td>
					<td><?php echo $array['priceBorderstonesOuterCornerLeft']; ?></td>
				</tr>
				<tr>
						<th scope="row">Buitenhoek rechts</th>
						<td></td>
						<td><?php echo $array['borderstonesOuterCornerRight']; ?></td>
						<td></td>
						<td><?php echo $array['priceBorderstonesOuterCornerRight']; ?></td>
				</tr>
				<tr>
						<th scope="row">Gebogen boordsteen</th>
						<td></td>
						<td><?php echo $array['borderstonesCurved']; ?></td>
						<td></td>
						<td><?php echo $array['priceBorderstonesCurved']; ?></td>
					</tr>
					<tr>
						<th scope="row">Tegel</th>
						<td></td>
						<td><?php echo $array['tiles']; ?></td>
						<td></td>
						<td><?php echo $array['priceTiles']; ?></td>
					</tr>
					<tr>
						<th scope="row">Voegsel</th>
						<td></td>
						<td><?php echo $array['voegsel']; ?></td>
						<td></td>
						<td><?php echo $array['priceVoegsel']; ?></td>
					</tr>
					<tr>
						<th scope="row">Transport</th>
						<td></td>
						<td><?php echo $array['transport']; ?></td>
						<td></td>
						<td><?php echo $array['priceTransport']; ?></td>
					</tr>
				<tr class="row_total">
					<th>TOTAAL</th>
					<td></td>
					<td></td>
					<td></td>
					<td><?php echo $array['priceTotal']; ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<div class="clearfix"></div>
<?php echo layout_button('submit', 'Leg in winkelwagen', '', '', 'btn btn-primary margin-top green-button pull-right', 'glyphicon glyphicon-shopping-cart', 'right'); ?>
<?php
  }
?>
</form>
<?php require(DIR_WS_CUR_TEMPLATE . 'footer.php'); ?>
