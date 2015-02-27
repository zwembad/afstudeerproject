<?php

/*********************************************************************
 gets propAttributes, price and internal reference id from db
 *********************************************************************/
class Mapper{
 
	protected const LENGTH_OPTIONS_ID = 168;
	protected const WIDTH_OPTIONS_ID = 171;
	protected const HEIGHT_OPTIONS_ID = 30;
	protected $_language_id;
	 
	public function __construct($_language_id=4){
		$this->_language_id = $_language_id;
	}

	//returns propAttributes
	public function getPropAttributesForReferenceInternal($reference_internal, $language_id){
	$statement = "SELECT options_id, products_options_name, products_options_values_name"
		." FROM ". TABLE_PRODUCTS_PROPATTRIBUTES . " pa" 
		." LEFT JOIN " . TABLE_PRODUCTS_PROPOPTIONS_VALUES_TO_PRODUCT_OPTIONS . " pb ON"
								  . " pa.options_values_id = pb.products_options_values_to_products_options_id"
		." LEFT JOIN ". TABLE_PRODUCTS_PROPOPTIONS_VALUES . " pc ON"
								  ." pc.products_options_values_id = pa.options_values_id"
		." INNER JOIN ". TABLE_PRODUCTS ." p ON pa.products_id = p.products_id"
		." INNER JOIN products_propoptions pp ON pa.options_id = pp.products_options_id"
		." WHERE language_id = " . (int)$language_id
		." AND products_reference_internal = '" .$reference_internal . "'"
		." GROUP BY options_id"
		." ORDER BY options_id";
		
		$query = tep_db_query($statement);
		$propAttribute = tep_db_fetch_all($query);
		return $propAttributes;
	}	
	
	
	//returns price
	public function getPriceForReferenceInternal($reference_internal){		
		$statement = "SELECT products_price"
		. " FROM ". TABLE_PRODUCTS .
		." WHERE products_reference_internal = '" .$reference_internal . "'";
		$query = tep_db_query($statement);
		$priceArray = tep_db_fetch_array($query);	
		return $priceArray['products_price'];
	}
	
	 public function setLanguageId($_language_id)
    {
        $this->_language_id = $_language_id;
    }

    public function getLanguageId()
    {
        return $this->_language_id;
    }
}
?>  