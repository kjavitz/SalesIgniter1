<?php
/*
	Product Purchase Type: Stream

	I.T. Web Experts, Rental Store v2
	http://www.itwebexperts.com

	Copyright (c) 2009 I.T. Web Experts

	This script and it's source is not redistributable
*/

/**
 * Purchase Stream Purchase Type
 * @package ProductPurchaseTypes
 */
class PurchaseType_stream extends PurchaseTypeAbstract {
	public $typeLong = 'stream';
	public $typeName;
	public $typeShow;

	public function __construct($ProductCls, $forceEnable = false){

		$this->typeName = sysLanguage::get('PURCHASE_TYPE_STREAM_NAME');
		$this->typeShow = sysLanguage::get('PURCHASE_TYPE_STREAM_SHOW');

		$productInfo = $ProductCls->productInfo;
		$this->enabled = ($forceEnable === true ? true : (in_array($this->typeLong, $productInfo['typeArr'])));

		if ($this->enabled === true){
			$this->productInfo = array(
				'id'      => $productInfo['products_id'],
				'price'   => $productInfo['products_price_stream'],
				'taxRate' => $productInfo['taxRate']
			);

			EventManager::notify('PurchaseTypeAfterSetup', &$this->productInfo);

			$this->inventoryCls = false;
		}
	}

	private function showViewType(){
		$viewTypeHtml = htmlBase::newElement('span')
			->css(array(
				'font-size' => '.8em',
				'font-style' => 'italic'
			))
			->html(' - View Type: Stream');

		return '<br />' . $viewTypeHtml->draw();
	}

	public function orderAfterProductName(&$orderedProduct){
		return $this->showViewType();
	}

	public function orderAfterEditProductName(&$orderedProduct){
		return $this->showViewType();
	}

	public function checkoutAfterProductName(&$cartProduct){
		return $this->showViewType();
	}

	public function shoppingCartAfterProductName(&$cartProduct){
		return $this->showViewType();
	}

	public function processRemoveFromCart(){
		return null;
	}

	public function updateStock($orderId, $orderProductId, &$cartProduct){
		return false;
	}

	public function processAddToOrder(&$pInfo){
		$this->processAddToCart($pInfo);
	}

	public function processAddToCart(&$pInfo){
		$pInfo['price'] = $this->productInfo['price'];
		$pInfo['final_price'] = $this->productInfo['price'];
	}

	public function onInsertOrderedProduct($cartProduct, $orderId, &$orderedProduct, &$products_ordered){
		$Qstreams = Doctrine_Query::create()
		->from('ProductsStreams')
		->where('products_id = ?', (int)$cartProduct->getIdString())
		->andWhere('is_preview = ?', 0)
		->execute();

		if ($Qstreams->count() > 0){
			foreach($Qstreams->toArray() as $sInfo){
				$Stream = new OrdersProductsStream();
				$Stream->orders_id = $orderId;
				$Stream->stream_id = $sInfo['stream_id'];
				$Stream->stream_maxdays = sysConfig::get('EXTENSION_STREAMPRODUCTS_MAX_DAYS');
				$Stream->stream_count = '0';
				
				$orderedProduct->OrdersProductsStream->add($Stream);
			}
			$orderedProduct->save();
		}
	}

	public function hasInventory(){
		return true;
	}

	public function canUseSpecial(){
		return false;
	}

	public function getPurchaseHtml($key){
		$return = null;

		$headerInfo = htmlBase::newElement('a')
		->setHref(itw_app_link('appExt=infoPages&dialog=true', 'show_page', 'help_stream'))
		->attr('onclick', 'popupWindow(this.href, \'400\', \'400\');return false;')
		->html(tep_image(DIR_WS_TEMPLATES . 'images/icon_help.png'));

		switch($key){
			case 'product_info':
				$headerInfo->addClass('infoPopupIcon');
				$button = htmlBase::newElement('button')
				->setType('submit')
				->setName('buy_' . $this->typeLong . '_product')
				->setText(sysLanguage::get('TEXT_BUTTON_BUY'));

				$pClass = new product($this->productInfo['id']);
				if ($pClass->isNotAvailable() ){
					$button->disable();
					$button->setText(sysLanguage::get('TEXT_AVAILABLE').': '. strftime(sysLanguage::getDateFormat('short'), strtotime($pClass->getAvailableDate())));
				}

				$content = htmlBase::newElement('span')
				->css(array(
					'font-size' => '1.5em',
					'font-weight' => 'bold'
				))
				->html($this->displayPrice());

				$return = array(
					'form_action'   => itw_app_link(tep_get_all_get_params(array('action'))),
					'purchase_type' => $this->typeLong,
					'allowQty'      => false,
					'header'        => $headerInfo->draw() . sysLanguage::get('BUY_STREAM'),
					'content'       => $content->draw(),
					'button'        => $button
				);
				break;
			case 'product_listing_row':
				$button = htmlBase::newElement('button')
				->setText(sysLanguage::get('TEXT_BUTTON_BUY_NOW'))
				->setHref(itw_app_link(tep_get_all_get_params(array('action', 'products_id')) . 'action=buy_' . $this->typeLong . '_product&products_id=' . $this->productInfo['id']), true);

				$return = '<tr>' .
					'<td class="main">' . $headerInfo->draw() . '</td>' .
					'<td class="main">' . $this->typeShow . ':</td>' .
					'<td class="main">' . $this->displayPrice() . '</td>' .
					'<td class="main" style="font-size:.7em;">' . $button->draw() . '</td>' .
				'</tr>';
				break;
		}
		return $return;
	}
}
?>