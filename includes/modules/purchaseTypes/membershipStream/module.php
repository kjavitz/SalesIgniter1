<?php
/*
	Product Purchase Type: Member Stream

	I.T. Web Experts, Rental Store v2
	http://www.itwebexperts.com

	Copyright (c) 2009 I.T. Web Experts

	This script and it's source is not redistributable
*/

/**
 * Rental Membership Stream Purchase Type
 * @package ProductPurchaseTypes
 */
class PurchaseType_MembershipStream extends PurchaseTypeAbstract
{

	public function __construct($ProductCls, $forceEnable = false) {
		$this->setTitle('Membership Stream');
		$this->setDescription('Membership Based Stream Products Which Mimic Sites Like netflix.com');

		$this->init('membershipStream', $ProductCls, $forceEnable);

		if ($this->isInstalled() === true){
			if ($this->isEnabled() === true){
			}
		}
	}

	public function processRemoveFromCart() {
		return null;
	}

	public function processAddToOrder(&$pInfo) {
		$this->processAddToCart($pInfo);
	}

	public function processAddToCart(&$pInfo) {
		$pInfo['price'] = $this->productInfo['price'];
		$pInfo['final_price'] = $this->productInfo['price'];
	}

	public function hasInventory() {
		return true;
	}

	public function canUseSpecial() {
		return false;
	}

	public function updateStock($orderId, $orderProductId, &$cartProduct) {
		return false;
	}

	public function getPurchaseHtml($key) {
		$return = null;
		switch($key){
			case 'product_info':
				$button = htmlBase::newElement('button')
					->setType('submit')
					->setName('stream_product')
					->setText(sysLanguage::get('TEXT_BUTTON_VIEW_STREAM'));

				$return = array(
					'form_action' => itw_app_link(tep_get_all_get_params(array('action'))),
					'purchase_type' => $this->typeLong,
					'allowQty' => false,
					'header' => $this->typeShow,
					'content' => '',
					'button' => $button
				);
				break;
		}
		return $return;
	}
}

?>