<?php
class productListing_productsPriceUsed {

	public function sortColumns(){
		$selectSortKeys = array(
								array(
									'value' => 'p.price_used',
									'name'  => sysLanguage::get('PRODUCT_LISTING_PRICE_USED')
								)

		);
		return $selectSortKeys;
	}

	public function show(&$productClass, &$purchaseTypesCol){
		$purchaseTypeUsed = $productClass->getPurchaseType('used', true);
		if ($purchaseTypeUsed->getCurrentStock() > 0){
			$buyNowButton = htmlBase::newElement('button')
			->setText(sysLanguage::get('TEXT_BUTTON_BUY_NOW'))
			->setHref(itw_app_link(tep_get_all_get_params(array('action', 'products_id')) . 'action=buy_used_product&products_id=' . $productClass->getID()), true);
			$purchaseTypesCol = 'used';
			if($productClass->isNotAvailable()){
				$purchaseTypesCol = '';
				$buyNowButton->disable();
				$buyNowButton->setText(sysLanguage::get('TEXT_AVAILABLE').': '. strftime(sysLanguage::getDateFormat('short'), strtotime($productClass->getAvailableDate())));
			}
			EventManager::notify('ProductListingModuleShowBeforeShow', 'used', $productClass, &$buyNowButton);

			return $purchaseTypeUsed->displayPrice() . '<br />' . $buyNowButton->draw();
		}
		return false;
	}
}
?>