<?php

/*********************************************************************
 gets propAttributes, price and internal reference id from db
 *********************************************************************/
class PropAttributeMapper{

	private $_language_id;

	public function __construct($_language_id){
		$this->setLanguageId($_language_id);
	}

	//returns array with key options_id and value options value
	public function getPropAttributesForRefInternal($reference_internal){
	$statement = "SELECT products_options_name, products_options_values_name"
		." FROM ". TABLE_PRODUCTS_PROPATTRIBUTES . " pa"
		." LEFT JOIN ". TABLE_PRODUCTS_PROPOPTIONS_VALUES . " pc ON"
								  ." pc.products_options_values_id = pa.options_values_id"
		." INNER JOIN ". TABLE_PRODUCTS ." p ON pa.products_id = p.products_id"
		." INNER JOIN products_propoptions pp ON pa.options_id = pp.products_options_id"
		." WHERE pc.language_id = " . (int)$this->_language_id
		." AND products_reference_internal = '" .$reference_internal . "'"
		." GROUP BY options_id";
		
		$query = tep_db_query($statement);
		$result = array();
		$rows = tep_db_fetch_all($query);
		foreach($rows as $row){
				$result[$row['products_options_name']] = $row['products_options_values_name'];
		}
		return $result;
	}	
	
	//returns price
	public function getPriceForRefInternal($reference_internal){		
		$statement = "SELECT products_price"
		. " FROM ". TABLE_PRODUCTS
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