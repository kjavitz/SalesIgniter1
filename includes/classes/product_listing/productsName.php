<?php
class productListing_productsName {

	public function sortColumns(){
		$selectSortKeys = array(
								array(
									'value' => 'pd.products_sname',
									'name'  => sysLanguage::get('PRODUCT_LISTING_NAME')
								)
		);
		return $selectSortKeys;
	}

	public function show(&$productClass, &$purchaseTypesCol){
		global $cPath;

		$addedGetVar = ($cPath ? '&cPath=' . $cPath : '');

//		if ($includeBoxInfo === true){
			$products_series = '';
			if ($productClass->isInBox()){
				$products_series ='<br /><small><i>'.sprintf(
				sysLanguage::get('TEXT_BS_SERIES'),
				$productClass->getDiscNumber($productClass->getID()),
				$productClass->getTotalDiscs(),
				htmlspecialchars($productClass->getBoxName())
				) . '</i></small>';
			}
//		}
		$ratingsBar = rating_bar($productClass->getName(), $productClass->getID());

		return '<a href="' . htmlspecialchars(itw_app_link('products_id=' . $productClass->getID() . $addedGetVar, 'product', 'info')) . '">' . $productClass->getName() . '</a>' . $products_series . $ratingsBar;
	}
}
?>