$(document).ready(
			function() {
				$('input:radio[name="material_group"]').change(function(){				
					if($(this).val() == "1"){
						$("#types2_group").hide();
						$("#types1_group").fadeIn( "slow" );
						$("#tile_type_neus").hide();
						$("#tile_type_L").hide();
						$("#tile_type_I").show();
					}else{
						$("#types1_group").hide();
						$("#types2_group").fadeIn( "slow" );
						$("#tile_type_neus").hide();
						$("#tile_type_L").hide();
						$("#tile_type_I").show();
					}
				});
				
				if($('input:radio[name="type_group1"]').is(":hidden")){
					
				}
				$('input:radio[name="type_group1"]').change(function(){
					if($(this).val() == "1"){
						$("#tile_type_neus").hide();
						$("#tile_type_L").hide();
						$("#tile_type_I").show();
					}
					else if($(this).val() == "2"){
						$("#tile_type_neus").show();
						$("#tile_type_L").hide();
						$("#tile_type_I").hide();
					}
					else if($(this).val() == "3"){
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
				
				$('input:radio[name="pool_shape_choice"]').change(function(){
					if($(this).val() == "2"){
						$("#diameter_input-group").show();
						$("#length_input-group").show();
						$("#width_input-group").show();
					}
					else if($(this).val() == "4"){
						$("#diameter_input-group").show();
						$("#length_input-group").hide();
						$("#width_input-group").hide();
					}
					else{
						$("#length_input-group").show();
						$("#width_input-group").show();
						$("#diameter_input-group").hide();
					}
				});
			$("[name='my-checkbox']").bootstrapSwitch();
				$.fn.bootstrapSwitch.defaults.size = 'small';
				
			$("[rel=tooltip_tile]").tooltip({ placement: 'right'});
			
			});