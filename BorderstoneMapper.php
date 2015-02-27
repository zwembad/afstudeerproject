<?php
class BorderstoneMapper extends Mapper{
	 
	public function __construct($_language_id){
		parent::__construct($_language_id);
	}
	
	public function getBorderstoneForReferenceInternal($reference_internal){
		
		$price = Mapper::getPriceForReferenceInternal($reference_internal);
		$length = Mapper::getLengthForReferenceInternal($reference_internal, $language_id);
		$width = Mapper::getWidthForReferenceInternal($reference_internal, $language_id);
		
		Borderstone bs = new Borderstone($price, $reference_internal, $category, $color, 
			$length, $material, $tiles, $type, $width, $shape);
		return bs;
	}
}