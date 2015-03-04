<?php
/*
  $Id: contact_us.php,v 1.42 2003/06/12 12:17:07 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
require(DIR_WS_CLASSES. 'Pool.php');
require(DIR_WS_CLASSES. 'Product.php');
require(DIR_WS_CLASSES. 'Heatpump.php');
require(DIR_WS_CLASSES. 'Calculator.php');
require(DIR_WS_CLASSES. 'CalculationStrategy.php');
require(DIR_WS_CLASSES. 'HeatpumpCalculationStrategy.php');

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CALC_WP);

$error = false;
if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
    $formData = array(
        array('title' => CALC_WP_FORM_SURFACEHEATINGPERC, 'value' => tep_db_prepare_input($_POST['surfaceheating'],true)), //inputfield
        array('title' => CALC_WP_FORM_POOLVOLUME, 'value' => tep_db_prepare_input($_POST['poolvolume'],true)), //inputfield
        array('title' => CALC_WP_FORM_BUILTINPERC, 'value' => tep_db_prepare_input($_POST['built_in'],true)), //inputfield
        array('title' => CALC_WP_FORM_WATERTEMP, 'value' => tep_db_prepare_input($_POST['watertemp'],true)), //inputfield
        array('title' => CALC_WP_FORM_SOLARHEATING, 'value' => $_POST['solarheating']), //switch
        array('title' => CALC_WP_FORM_SWIMPERIOD, 'value' => $_POST['swimperiod']), //dropdown
        array('title' => CALC_WP_FORM_HEATPUMP, 'value' => $_POST['heatpump']), //dropdown
        array('title' => CALC_WP_FORM_POOLROOF, 'value' => $_POST['poolroof']), //dropdown
    );
    
    $pool = new Pool(0, 0, 0, 0, 0, 0, $formData[1]['value'], $formData[7]['value'], $formData[2]['value']);
    $heatpump = new Heatpump(0, 0, 5, 2, $formData[5]['value'], $formData[3]['value'], $formData[0]['value']);
    $calculationStrategy = new HeatpumpCalculationStrategy();
    $calculator = new Calculator($pool, $heatpump, $calculationStrategy);
    $array = $calculator->calculatePrice();
}

$breadcrumb->add(NAVBAR_TITLE, tep_href_link('calc_wp.php'));

require(DIR_WS_CUR_TEMPLATE . 'header.php'); ?>
<!-- header_eof //-->
<link rel="stylesheet" href="/templates/default/css/ajax-form-validation.css" type="text/css">
<link rel="stylesheet" href="/templates/default/css/calc_bs.css" type="text/css">
<link rel="stylesheet" href="/templates/default/css/bootstrap-switch.min.css" type="text/css">
<script src="/templates/default/js/bootstrap-switch.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

	// Place ID's of all required fields here.
	required = ['poolvolume', 'watertemp', 'built_in', 'surfaceheating'];
	// If using an ID other than #email or #error then replace it here
	email = $("#email");
	errornotice = $("#error");
	// The text to show up within a field when it is incorrect
	emptyerror = "";
	emailerror = "";

	
$("#calc_wp").submit(function() {	
		
		//Validate required fields
		for (i=0;i<required.length;i++) {
			var input = $('#'+required[i]);
			if ((input.val() === "") || (input.val() === emptyerror)) {
				input.addClass("needsfilled");
				input.val(emptyerror);
				errornotice.fadeIn(750);
			} else {
				input.removeClass("needsfilled");
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

    $("#calc_button").click(function(){
        if(document.getElementById('poollayout').value === 1){
            var lengte = document.getElementById('poolsize_l').value;
            var breedte = document.getElementById('poolsize_b').value;
            var diepte = document.getElementById('poolsize_de').value;
            var num = lengte * breedte * diepte;
            $("#poolvolume").val(num);
        }
        else if(document.getElementById('poollayout').value === 4){
            var diameter = document.getElementById('poolsize_d').value;
            var diepte = document.getElementById('poolsize_de').value;
            var straal = diameter/2;
            document.getElementById('poolvolume').value = (straal * straal * 3.14 * diepte);
        }
        else if(document.getElementById('poollayout').value === 5){
            var lengte = document.getElementById('poolsize_l').value;
            var breedte = document.getElementById('poolsize_b').value;
            var diepte = document.getElementById('poolsize_de').value;
            document.getElementById('poolvolume').value = (((3.14  *(breedte * breedte)/4) + (lengte - breedte) * breedte)*diepte);
        }
        //$("#showDropdown").hide();
        $("#dropdown").hide();
        $("#diameter_input-group").hide();
        $("#length_input-group2").hide();
        $("#width_input-group").hide();
        $("#diepte_input-group").hide();
        $("#calc_button-group").hide();
    });

    $("#showDropdown").click(function(){
        $("#dropdown").show();
        $("#diameter_input-group").hide();
        $("#length_input-group2").hide();
        $("#width_input-group").hide();
        $("#diepte_input-group").hide();
        //$("#calc_button-group").show();
    });

    $('select').change(function(){
        if($(this).val() === "1"){      	//if 'rechthoekig'
            $("#diameter_input-group").hide();
            $("#length_input-group2").show();
            $("#width_input-group").show();
            $("#diepte_input-group").show();
            $("#calc_button-group").show();
        }
        else if($(this).val() === "2"){     //if 'rond'
            $("#length_input-group2").hide();
            $("#width_input-group").hide();
            $("#diepte_input-group").show();
            $("#diameter_input-group").show();
            $("#calc_button-group").show();

        }
        else if($(this).val() === "3"){     //if 'ovaal'
            $("#diameter_input-group").hide();
            $("#length_input-group2").show();
            $("#width_input-group").show();
            $("#diepte_input-group").show();
            $("#calc_button-group").show();
        }
    });


	
	// Clears any fields in the form when the user clicks on them
	$(":input").focus(function(){		
	   if ($(this).hasClass("needsfilled") ) {
			$(this).val("");
			$(this).removeClass("needsfilled");
	   }
	});
		
	//Make switch from checkbox
	$("[name='solarheating']").bootstrapSwitch();
	$.fn.bootstrapSwitch.defaults.size = 'small';
				
	//show tooltip on hover green question mark
	$("[rel=tooltip_tile]").tooltip({ placement: 'right'});
			
	 
	$('.infobox').readmore({
		speed: '75',
		maxHeight: 85,
		moreLink: '<a class="pull-right" href="#">Lees meer..</a><div class="clearfix"></div>',
		lessLink: '<a class="pull-right" href="#">Sluiten..</a><div class="clearfix"></div>'
		});	
});	
</script>
<h3 class="section-header"><?php echo CALC_WP_TEXT_TITLE; ?></h3>


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
    echo TEXT_SUCCESS;
?>
<br /><br />
<?php echo layout_button('submit', IMAGE_BUTTON_CONTINUE, tep_href_link(FILENAME_DEFAULT, '', 'SSL'), '', ''); ?>
<?php
} else {
?>
<p id="error"><?php echo QUANDA_ERROR_HEADING; ?></p>
<div class="alert alert-info" role="alert">
   <div class="infobox">
	  <b>Veronderstellingen</b>
		<ul style="margin-left: 2.5em; text-indent: -1.0em">
		  <li>U heeft nachttarief, en u verwarmt het zwembad 's nachts.</li>
		  <li>Week-end tarief = nachttarief</li>
		  <li>Zwembad heeft bubbel-afdekzeil</li>
		  <li>Bovengronds zwembad verliest dubbel zoveel warmte als ingebouwd</li>
		  <li>Overkapt zwembad verliest de helft aan warmte van een niet-overkapt</li>
		  <li>1,16 kWh verwarmt 1m<sup>3</sup> water 1 graad in 1 uur</li>
		  <li>Het programma maakt berekeningen zodat het zwembad in minder dan 16 uur per dag verwarmd is</li>
		  <li>Bij de berekening van de COP werden de gemiddelde temperaturen in Ukkel gebruikt</li>
		  <li>Bij de berekening van de toe te voegen warmte werden watertemperaturen verondersteld in Belgie zonder verwarming</li>
		  <li>Prijs 1 kWh dag: 0,18 euro</li>
		  <li>Prijs 1 kWh nacht: 0,12 euro</li>
		  <li>COP warmtepomp bij 26 graden: 5,0 euro/kWh</li>
		  <li>COP warmtepomp bij 5 graden: 2,0 euro/kWH</li>
		</ul>
	</div>
</div>

<div class="clearfix"></div>

<?php echo tep_draw_form('calc_wp', tep_href_link('calc_wp.php', 'action=send'), 'post', 'id="calc_wp"');  ?>

<label for="watertemp" class="col-sm-3 control-label"><?php echo CALC_WP_FORM_POOL; ?></label>
<div class="col-sm-9">
	<div class="form-group">
		<div class="input-group" id="length_input-group">
			<?php echo tep_draw_input_field('watertemp', '', 'id="watertemp" placeholder="'. CALC_WP_FORM_WATERTEMP .'" step="any" min="0" class="form-control"','number'); ?>
			<div class="input-group-addon"><?php echo "&deg;C"; ?></div>
		</div>
	</div>
	<div class="form-group">
			<div class="input-group" id="length_input-group">
			<?php echo tep_draw_input_field('built_in', '', 'id="built_in" placeholder="'. CALC_WP_FORM_BUILTINPERC .'" step="any" min="0" class="form-control"','number'); ?>
			<div class="input-group-addon">%</div>
		</div>
	</div>
        <div class="form-group">
		<div class="input-group" id="length_input-group">
				<?php echo tep_draw_input_field('poolvolume', '', 'id="poolvolume" placeholder="'. CALC_WP_FORM_POOLVOLUME .'" step="any" min="0" class="form-control"','number'); ?>
				<div class="input-group-addon"><?php echo "m<sup>3</sup>"; ?></div>
		</div>
	</div>
    <div class="form-group">
        <div class="input-group">
            <button id="showDropdown" value="" type="button" class="btn btn-primary margin-top green-button pull-left"><?php echo CALC_WP_FORM_CALC_VOLUME; ?></button>
        </div>
    </div>
    <div class="clearfix"></div>
        <div class="form-group" id="dropdown" style="display: none">
              <div class="dropdown">
                  <select id="poollayout" name="poollayout" class="form-control">
                      <option value="" disabled selected><?php echo CALC_WP_FORM_POOLLAYOUT; ?></option>
                      <option value="1"><?php echo CALC_WP_FORM_POOLLAYOUT_1; ?></option>
                      <option value="2"><?php echo CALC_WP_FORM_POOLLAYOUT_2; ?></option>
                      <option value="3"><?php echo CALC_WP_FORM_POOLLAYOUT_3; ?></option>
                  </select>
              </div>
          </div>

      <div class="form-group">
          <div class="input-group" id="length_input-group2" style="display: none">
              <?php echo tep_draw_input_field('poolsize_l', '', 'id="poolsize_l" placeholder="'. CALC_WP_FORM_POOLSIZE_L .'" step="any" min="0" class="form-control"','number'); ?>
              <div class="input-group-addon">m</div>
          </div>
      </div>
      <div class="form-group">
          <div class="input-group" id="width_input-group" style="display: none">
              <?php echo tep_draw_input_field('poolsize_b', '', 'id="poolsize_b" placeholder="' . CALC_WP_FORM_POOLSIZE_B .'" step="any" min="0" class="form-control"','number');?>
              <div class="input-group-addon">m</div>
          </div>
      </div>
      <div class="form-group">
          <div class="input-group" id="diameter_input-group" style="display: none">
              <?php echo tep_draw_input_field('poolsize_d', '', 'id="poolsize_d" placeholder="' . CALC_WP_FORM_POOLSIZE_D .'" step="any" min="0" class="form-control"','number');?>
              <div class="input-group-addon">m</div>
          </div>
      </div>
      <div class="form-group">
          <div class="input-group" id="diepte_input-group" style="display: none">
              <?php echo tep_draw_input_field('poolsize_de', '', 'id="poolsize_de" placeholder="' . CALC_WP_FORM_POOLSIZE_De .'" step="any" min="0" class="form-control"','number');?>
              <div class="input-group-addon">m</div>
          </div>
      </div>
    <div class="form-group">
        <div class="input-group" id="calc_button-group" style="display: none">
            <button id="calc_button" value="" type="button" class="btn btn-primary margin-top green-button pull-left">Bereken</button>
        </div>
    </div>
</div>
<div class="clearfix"><hr /></div>
<label for="poolroof" class="col-sm-3 control-label"><?php echo CALC_WP_FORM_POOLROOF; ?></label>
<div class="col-sm-9">
    <select name="poolroof" class="form-control">
        <option value=<?php echo strtolower(CALC_WP_FORM_POOLROOF_1); ?>><?php echo CALC_WP_FORM_POOLROOF_1; ?></option>
        <option value=<?php echo strtolower(CALC_WP_FORM_POOLROOF_2); ?>><?php echo CALC_WP_FORM_POOLROOF_2; ?></option>
        <option value=<?php echo strtolower(CALC_WP_FORM_POOLROOF_3); ?>><?php echo CALC_WP_FORM_POOLROOF_3; ?></option>
        <option value=<?php echo strtolower(CALC_WP_FORM_POOLROOF_4); ?>><?php echo CALC_WP_FORM_POOLROOF_4; ?></option>
        <option value=<?php echo strtolower(CALC_WP_FORM_POOLROOF_5); ?>><?php echo CALC_WP_FORM_POOLROOF_5; ?></option>
    </select>
</div>
<div class="clearfix"></div>
<div class="clearfix"><hr /></div>

<label for="swimperiod" class="col-sm-3 control-label"><?php echo CALC_WP_FORM_SWIMPERIOD; ?></label>
<div class="col-sm-9">
    <select name="swimperiod" class="form-control">
        <option value="1"><?php echo CALC_WP_FORM_SWIMPERIOD_1; ?></option>
        <option value="2"><?php echo CALC_WP_FORM_SWIMPERIOD_2; ?></option>
        <option value="3"><?php echo CALC_WP_FORM_SWIMPERIOD_3; ?></option>
        <option value="4"><?php echo CALC_WP_FORM_SWIMPERIOD_4; ?></option>
    </select>
</div>

<div class="clearfix"><hr /></div>

<div class="form-group">	
	<label for="solarheating" class="col-sm-3 control-label"><?php echo CALC_WP_FORM_SOLARHEATING; ?>
	</label>
	<div class="col-sm-9">
		<?php echo tep_draw_checkbox_field('solarheating', '', true, 'id=solarheating data-on-text="' . CALC_WP_FORM_SOLARHEATING_1 . '" data-off-text="'. CALC_WP_FORM_SOLARHEATING_2 . '" checked'); ?> 
	</div>
</div>
<div class="clearfix"><hr /></div>
<div class="form-group">
	<label for="surfaceheating" class="col-sm-3 control-label"><?php echo CALC_WP_FORM_SURFACEHEATINGPERC; ?></label>
	<div class="col-sm-9">
		<div class="input-group" id="length_input-group">
			<?php echo tep_draw_input_field('surfaceheating', '', 'id="surfaceheating" step="any" min="0" class="form-control"','number'); ?>
			<div class="input-group-addon"><?php echo "%"; ?></div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div class="clearfix"><hr /></div>

<div class="form-group">
    <label for="heatpump" class="col-sm-3 control-label"><?php echo CALC_WP_FORM_HEATPUMP; ?></label>
    <div class="col-sm-9">
        <div class="dropdown">
            <select name="heatpump" class="form-control">
                <option value="1"><?php echo CALC_WP_FORM_HEATPUMP_1; ?></option>
                <option value="2"><?php echo CALC_WP_FORM_HEATPUMP_2; ?></option>
            </select>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<?php echo layout_button('submit', 'Bereken', '', '', 'btn btn-primary margin-top green-button pull-right', '', 'right'); ?>	
<div class="clearfix"></div>
<div class="clearfix"><hr /></div>
<?php if($array['kw']) {
    echo "<p>Wij raden een warmtepomp van minstens "; 
    echo round($array['kw'], 2);
    echo " kW aan.</p>";
} ?>
<div class="panel panel-primary filterable">
    <div class="panel-heading">
        <h3 class="panel-title">Onze aanbevolen warmtepompen <br><small><font color="white">zomerpompen en all-seasons pompen</font></small></h3>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered" style="border-collapse:collapse">
            <thead>
                <tr>
                    <th style="width: 26%"></th>
                    <th style="width: 14%">Barcode</th>
                    <th style="width: 48%">Beschrijving</th>
                    <th style="width: 12%">Prijs (&#8364;)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row"><?php echo CALC_WP_FORM_SUMMERPUMP; ?></th>
                    <td><?php echo $array['summerpump'][0]; ?></td>
                    <td><?php echo $array['summerpump'][1]; ?></td>
                    <td><?php echo $array['summerpump'][2]; if($array['kw']) {echo ",00";} ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php echo CALC_WP_FORM_SUMMERPUMP_ALTERNATIVE; ?></th>
                    <td><?php echo $array['summerpump_alternative'][0]; ?></td>
                    <td><?php echo $array['summerpump_alternative'][1]; ?></td>
                    <td><?php echo $array['summerpump_alternative'][2]; if($array['kw']) {echo ",00";} ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php echo CALC_WP_FORM_ALLSEASONSPUMP; ?></th>
                    <td><?php echo $array['allseasonspump'][0]; ?></td>
                    <td><?php echo $array['allseasonspump'][1]; ?></td>
                    <td><?php echo $array['allseasonspump'][2]; if($array['kw']) {echo ",00";} ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php echo CALC_WP_FORM_ALLSEASONSPUMP_ALTERNATIVE; ?></th>
                    <td><?php echo $array['allseasonspump_alternative'][0]; ?></td>
                    <td><?php echo $array['allseasonspump_alternative'][1]; ?></td>
                    <td><?php echo $array['allseasonspump_alternative'][2]; if($array['kw']) {echo ",00";} ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div><!--
<div class="panel panel-primary filterable">
    <div class="panel-heading">
        <h3 class="panel-title">Elektriciteitskosten zwembadverwarming <br><small><font color="white">met verwarming op nachttarief</font></small></h3>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered" style="border-collapse:collapse">
            <thead>
                <tr>
                    <th style="width: 50%">Verwarming</th>
                    <th style="width: 50%">Euro/jaar</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row"><?php //echo CALC_WP_FORM_SWIMPERIOD_4; ?></th>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"><?php //echo CALC_WP_FORM_SWIMPERIOD_3; ?></th>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"><?php //echo CALC_WP_FORM_SWIMPERIOD_2; ?></th>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"><?php //echo CALC_WP_FORM_SWIMPERIOD_1; ?></th>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>!-->
<div class="clearfix"></div>
<?php echo layout_button('submit', 'Leg in winkelwagen', '', '', 'btn btn-primary margin-top green-button pull-right', 'glyphicon glyphicon-shopping-cart', 'right'); ?>
<?php
}
?>
</form>
<?php require(DIR_WS_CUR_TEMPLATE . 'footer.php'); ?>