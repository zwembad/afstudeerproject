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
	require(DIR_WS_CLASSES. 'Product.php');
	require(DIR_WS_CLASSES. 'Borderstone.php');
	require(DIR_WS_CLASSES. 'Pool.php');
	
	require(DIR_WS_CLASSES. 'Calculator.php');
	require(DIR_WS_CLASSES. 'StoneCalculator.php');
	require(DIR_WS_CLASSES. 'CalculationStrategy.php');
	require(DIR_WS_CLASSES. 'StoneCalculationStrategy.php');
	
	require(DIR_WS_CLASSES. 'CategoryMapper.php');
	require(DIR_WS_CLASSES. 'PropAttributeMapper.php');
	
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CALC_BS);
  $categoryMapper = new CategoryMapper(4);
  $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
    $formData = array(
        CALC_BS_FORM_STONECATEGORY => $_POST['stonecategory'],
        CALC_BS_FORM_STONEMATERIAL => $_POST['stonematerial'],
        CALC_BS_FORM_STONETYPE => $_POST['stonetype'],
        CALC_BS_FORM_TILES => $_POST['tiles'],
        
        
		CALC_BS_FORM_POOLLAYOUT => $_POST['poollayout'],	
        CALC_BS_FORM_POOLSIZE_L => tep_db_prepare_input($_POST['poolsize_l'],true),
        CALC_BS_FORM_POOLSIZE_B => tep_db_prepare_input($_POST['poolsize_b'],true),
        CALC_BS_FORM_POOLSIZE_D => tep_db_prepare_input($_POST['poolsize_d'],true),
    );
	
	//new Pool with parameters: $depth, $_diameter, $_length, $_shape, $_type, $_width
	$pool = new Pool(0,$formData[CALC_BS_FORM_POOLSIZE_D],$formData[CALC_BS_FORM_POOLSIZE_L], 
								$formData[CALC_BS_FORM_POOLLAYOUT], 0, $formData[CALC_BS_FORM_POOLSIZE_B]);
	
	//new Borderstone with parameters: $_category, $_color, $_length, $_material, $_tiles, $_type, $_width
 //  $borderstone = new BorderStone($formData[0]['value'], 0, 50, $formData[1]['value'], $formData[3]['value'], $formData[2]['value'], 35);
   
   
	//$products = $categoryMapper->getProductsOfCategory($formData[CALC_BS_FORM_STONEMATERIAL]);
	$products= $categoryMapper->getProductsOfCategory(785);
	$calculationStrategy = new StoneCalculationStrategy();
	$calculator = new Calculator($pool, $products, $calculationStrategy);
	$array = $calculator->calculatePrice();
	
   
    $subject = CALC_BS_TEXT_TITLE." > Contact Robotcleaner";
 
	}
	
	$categories = $categoryMapper->getCategories(33);
	
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
		for (var i=0; i < $('input:radio[name="stonecategory"]').length; i++){
			
		}	
	});
	
	$(document).on("click",'input:radio[name="stonecategory"]', function () {
		var index = $(this).index()/2;
		$('#material_group'+index).siblings().hide();
		$('#material_group'+index).show();
	});

	$('input:radio[name="stonematerial"]').change(function(){
		if($(this).val() == "Beige graniet"){
			$("#tile_type_neus").hide();
			$("#tile_type_L").hide();
			$("#tile_type_I").show();
		}
		else if($(this).val() == "New Jasberg"){
			$("#tile_type_neus").show();
			$("#tile_type_L").hide();
			$("#tile_type_I").show();
		}
		else if($(this).val() == "Black Panda"){							
			$("#tile_type_neus").show();
			$("#tile_type_L").hide();
			$("#tile_type_I").show();
		}
		else{
			$("#tile_type_neus").hide();
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
<h3 class="section-header"><?php echo CALC_BS_TEXT_TITLE; ?></h3>


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
<label for="stonecategory" class="col-sm-3 control-label"><?php echo CALC_BS_FORM_STONECATEGORY; ?></label>
<div class="col-sm-9">

<?php foreach($categories as $category){
		echo tep_draw_radio_field('stonecategory', $category[1], '', 'class="radio_item"  id="'.$category[1].'"'); ?><label class="label_item" for="<?php echo $category[1]; ?>"> <?php echo $category[1]; ?>
	<?php echo tep_image('/t.php?src=images/categories/'. $category[2] .'&amp;w=155&amp;h=155&amp;zc=2', '', '', '', 'class="img-responsive"'); ?>
</label>
<?php }?>
</div>
</div>

<div class="form-group">	
<label for="stonematerial" class="col-sm-3 control-label"><?php echo CALC_BS_FORM_STONEMATERIAL; ?></label>
<div class="col-sm-9">
<?php	for($i=0; $i< count($categories); $i++){ 
	$category = $categories[$i];
	$materialgroups[] = $categoryMapper->getCategories($category[0]);
	}
	foreach($materialgroups as $nr => $group){
	?>	
	<div id="material_group<?php echo $nr; ?>" style="display:none">
		<?php 
			foreach($group as $material){ 
			
			if(!is_null($categoryMapper->getCategories($material[0]))) { //material has a subcategory e.g. neus
				$subcategories = $categoryMapper->getCategories($material[0]);
				foreach($subcategories as $sub){ ?>
					<?php echo tep_draw_radio_field('stonematerial', $sub[0], '', 'class="radio_item" id="'.$sub[1].'"'); ?> <label class="label_item" for="<?php echo $sub[1]; ?>"> <?php echo $sub[1]; ?>
					<?php echo tep_image('/t.php?src=images/categories/'. $sub[2]. '&amp;w=155&amp;h=155&amp;zc=2', '', '', '', 'class="img-responsive"'); ?>
					</label>
	<?php  }
			continue;}
			echo tep_draw_radio_field('stonematerial', $material[0], '', 'class="radio_item" id="'.$material[1].'"'); ?> <label class="label_item" for="<?php echo $material[1]; ?>"> <?php echo $material[1]; ?>
		<?php echo tep_image('/t.php?src=images/categories/'. $material[2]. '&amp;w=155&amp;h=155&amp;zc=2', '', '', '', 'class="img-responsive"'); ?>
		</label>
	<?php } ?>
	</div>
	<?php } ?>
</div> 
</div>

<div class="form-group">	
<label for="stonetype" class="col-sm-3 control-label"><?php echo CALC_BS_FORM_STONETYPE; ?></label>
<div class="col-sm-9">
	<div class="inline" id="tile_type_neus" >
    <?php echo tep_draw_radio_field('stonetype', '1', '', 'class="radio_item" id="stonetype_1"'); ?> <label class="label_item" for="stonetype_1"> <?php echo CALC_BS_FORM_STONETYPE_1; ?>
	<?php echo tep_image("/t.php?src=images/categories/type_neus.png&amp;w=155&amp;h=73&amp;zc=2", '', '', '', 'class="img-responsive"'); ?>
	</label>
	</div>
	<div class="inline" id="tile_type_L">
	<?php echo tep_draw_radio_field('stonetype', '2', '', 'class="radio_item" id="stonetype_2"'); ?> <label class="label_item" for="stonetype_2"> <?php echo CALC_BS_FORM_STONETYPE_2; ?>
	<?php echo tep_image("/t.php?src=images/categories/type_L.png&amp;w=155&amp;h=73&amp;zc=2", '', '', '', 'class="img-responsive"'); ?>
	</label>
	</div>
	<div class="inline" id="tile_type_I">
	<?php echo tep_draw_radio_field('stonetype', '3', '', 'class="radio_item" id="stonetype_3"'); ?> <label class="label_item" for="stonetype_3"> <?php echo CALC_BS_FORM_STONETYPE_3; ?>
	<?php echo tep_image("/t.php?src=images/categories/type_I.png&amp;w=155&amp;h=73&amp;zc=2", '', '', '', 'class="img-responsive"' ); ?>
	</label>
	</div>
</div>
</div> 

<div class="clearfix"><hr /></div>

<div class="form-group">	
	<label for="tiles" class="col-sm-3 control-label"><?php echo CALC_BS_FORM_TILES; ?> <i class="green-question" rel="tooltip" title="Een rij tegels van 50 bij 50 cm naast de boordstenen" id="tooltip_tile">?</i>
	</label>
	<div class="col-sm-9">
		<?php echo tep_draw_checkbox_field('tiles', '', true, 'id=tiles data-on-text="' . CALC_BS_FORM_TILES_1 . '" data-off-text="'. CALC_BS_FORM_TILES_2 . '" checked'); ?> 
	</div>
</div>
  
<div class="clearfix"><hr /></div>
  
<div class="form-group">	
<label for="poollayout" class="col-sm-3 control-label"><?php echo CALC_BS_FORM_POOLLAYOUT; ?></label>
<div class="col-sm-9">
<?php echo tep_draw_radio_field('poollayout', '1', '', 'class="radio_item" id="poollayout_1"'); ?><label class="label_item" for="poollayout_1"> <?php echo CALC_BS_FORM_POOLLAYOUT_1; ?>
	<?php echo tep_image("/tools/winterzeilen/i/square.png", '', '155', '155', 'class="img-responsive"' ); ?>
	</label>
<?php echo tep_draw_radio_field('poollayout', '2', '', 'class="radio_item" id="poollayout_2"'); ?><label class="label_item" for="poollayout_2"> <?php echo CALC_BS_FORM_POOLLAYOUT_2; ?>
	<?php echo tep_image("/tools/winterzeilen/i/roman_right.png", '', '155', '155', 'class="img-responsive"' ); ?>
	</label>
<?php echo tep_draw_radio_field('poollayout', '3', '', 'class="radio_item" id="poollayout_3"'); ?><label class="label_item" for="poollayout_3"> <?php echo CALC_BS_FORM_POOLLAYOUT_3; ?>
	<?php echo tep_image("/tools/winterzeilen/i/ext_right.png", '', '155', '155', 'class="img-responsive"' ); ?>
	</label>
<?php echo tep_draw_radio_field('poollayout', '4', '', 'class="radio_item" id="poollayout_4"'); ?><label class="label_item" for="poollayout_4"> <?php echo CALC_BS_FORM_POOLLAYOUT_4; ?>
	<?php echo tep_image("/tools/winterzeilen/i/round.png", '', '155', '155', 'class="img-responsive"' ); ?>
	</label>
<?php echo tep_draw_radio_field('poollayout', '5', '', 'class="radio_item" id="poollayout_5"'); ?><label class="label_item" for="poollayout_5"> <?php echo CALC_BS_FORM_POOLLAYOUT_5; ?>
	<?php echo tep_image("/tools/winterzeilen/i/oval.png", '', '155', '155', 'class="img-responsive"' ); ?>
	</label>
	</div>
</div>
   
<div class="clearfix"><hr /></div>

	<label for="poolsize" class="col-sm-3 control-label"><?php echo CALC_BS_FORM_POOLSIZE; ?></label>	
	<div class="col-sm-9">
		<div class="form-group">
			<div class="input-group" id="length_input-group">
				<?php echo tep_draw_input_field('poolsize_l', '', 'id="poolsize_l" placeholder="'. CALC_BS_FORM_POOLSIZE_L .'" step="any" min="0" class="form-control"','number'); ?>
				<div class="input-group-addon">m</div>
			</div>
		</div>
		<div class="form-group">	
				<div class="input-group" id="width_input-group">
					<?php echo tep_draw_input_field('poolsize_b', '', 'id="poolsize_b" placeholder="' . CALC_BS_FORM_POOLSIZE_B .'" step="any" min="0" class="form-control"','number');?>
					<div class="input-group-addon">m</div>
				</div>
		</div>
		<div class="form-group">
				<div class="input-group" id="diameter_input-group">
					<?php echo tep_draw_input_field('poolsize_d', '', 'id="poolsize_d" placeholder="' . CALC_BS_FORM_POOLSIZE_D .'" step="any" min="0" class="form-control"','number');?>
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
					<td><?php echo $array ['refBorderstonesStraight']; ?></td>
					<td><?php echo $array['borderstonesStraight']; ?></td>
					<td><?php echo $array['unitPriceBorderstonesStraight']; ?></td>
					<td><?php echo number_format($array['priceStraight'],2);?></td>
				</tr>
				<tr>
					<th scope="row">Binnenhoek</th>
					<td><?php echo $array['refBorderstonesInnerCorner']; ?></td>
					<td><?php echo $array['borderstonesInnerCorner']; ?></td>
					<td><?php echo $array['unitPriceBorderstonesInnerCorner']; ?></td>
					<td><?php echo number_format($array['priceInnerCorner'],2);?></td>
				</tr>
				<tr>
					<th scope="row">Buitenhoek links</th>
					<td><?php echo $array['refBorderstonesOuterCornerLeft']; ?></td>
					<td><?php echo $array['borderstonesOuterCornerLeft']; ?></td>
					<td><?php echo $array['unitPriceBorderstonesOuterCornerLeft']; ?></td>
					<td><?php echo number_format($array['priceOuterCornerLeft'],2); ?></td>
				</tr>
				<tr>
						<th scope="row">Buitenhoek rechts</th>
						<td><?php echo $array['refBorderstonesOuterCornerRight']; ?></td>
						<td><?php echo $array['borderstonesOuterCornerRight']; ?></td>
						<td><?php echo $array['unitPriceBorderstonesOuterCornerRight']; ?></td>
						<td><?php echo number_format($array['priceOuterCornerRight'],2); ?></td>
				</tr>
				<tr>
						<th scope="row">Gebogen boordsteen</th>
						<td><?php echo $array['refBorderstonesCurved']; ?></td>
						<td><?php echo $array['borderstonesCurved']; ?></td>
						<td><?php echo $array['unitPriceBorderstonesCurved']; ?></td>
						<td><?php echo number_format($array['priceCurved'],2); ?></td>
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
						<td>ZZZE8010</td>
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
