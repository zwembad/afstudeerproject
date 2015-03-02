<?php
class BorderstoneMapper extends Mapper{

	protected const LENGTH_OPTIONS_ID = 168;
	protected const WIDTH_OPTIONS_ID = 171;
	protected const HEIGHT_OPTIONS_ID = 30;
	 
	public function __construct($_language_id){
		parent::__construct($_language_id);
	}
	

	public function getBorderstoneForReferenceInternal($reference_internal){
		
		$propAttributes = getPropAttributesForReferenceInternal($reference_internal, $language_id)
		$width = $propAttributes[WIDTH_OPTIONS_ID];
		$length = $propAttributes[LENGTH_OPTIONS_ID];
		$height = $propAttributes[HEIGHT_OPTIONS_ID];
		
		$price =  getPriceForReferenceInternal($reference_internal);
			
		Borderstone bs = new Borderstone($price, $reference_internal, $category, $color, 
			$length, $material, $tiles, $type, $width, $shape);
		return bs;
	}
}