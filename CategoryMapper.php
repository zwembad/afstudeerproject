<?php
class CategoryMapper{
	
	private $_language_id;

	public function __construct($_language_id){
		$this->_language_id = $_language_id;
	}
	
	//get categories of category with parameters parent_id, language_id
	public function getCategories($parent_id){
		$statement = "SELECT c.categories_id, categories_name, categories_image FROM categories c
		INNER JOIN categories_description cd ON c.categories_id = cd.categories_id
		WHERE parent_id = " . (int)$parent_id
		. " AND language_id = ". (int)$this->_language_id;
		
		$query = tep_db_query($statement);
		$categories = tep_db_fetch_all($query);
		foreach($categories as $row){
			$category = array($row['categories_id'], $row['categories_name'], $row['categories_image']);
			$result[] = $category;
		}
		return $result;
	}
	
	public function getProductsOfCategory($categories_id){
		$statement  = "SELECT products_price, products_reference_internal, products_name
		FROM products p
		INNER JOIN products_description pd ON p.products_id = pd.products_id
		INNER JOIN products_to_categories pc ON p.products_id = pc.products_id"
		." WHERE categories_id = ". (int)$categories_id
		." AND language_id = ". (int)$this->_language_id;
		
		$query = tep_db_query($statement);
		$products = tep_db_fetch_all($query);
		foreach($products as $row){
			$product = array($row['products_price'], $row['products_reference_internal'], $row['products_name']);
			$result[] = $product;
		}
		return $result;
	}
	
}