<?php
class OrderShippingZonereservation extends OrderShippingModuleBase
{

	private $methods = array();
	private $type = '';

	private $quotes;

	public function __construct() {
		global $ShoppingCart, $App;
		/*
		 * Default title and description for modules that are not yet installed
		 */
		$this->setTitle('Reservation Shipping');
		$this->setDescription('Google Maps Zone Based Shipping');

		$this->init('zonereservation');
		$this->type = $this->getConfigData('MODULE_ORDER_SHIPPING_ZONE_RESERVATION_TYPE');
		if($this->isInstalled() == false){
			$this->setEnabled(false);
		}
		if($App->getEnv() == 'catalog'){

			if(isset($_GET['app']) && $_GET['app'] != 'checkout'){
				$this->setEnabled(true);
			}else{
				$this->setEnabled(false);
			}
			if($this->type == 'Order' && Session::exists('onlyReservations') && (isset($_GET['app']) && $_GET['app'] == 'checkout')){
				$this->setEnabled(true);
			}
			if(sysConfig::get('EXTENSION_PAY_PER_RENTALS_SHOW_SHIPPING') == 'False' && Session::exists('onlyReservations')){
				$this->setEnabled(true);
			}
		}
		if(sysConfig::get('EXTENSION_PAY_PER_RENTALS_SHOW_SHIPPING_ON_CALENDAR_IF_ORDER') == 'True' && (isset($_GET['app']) && $_GET['app'] == 'checkout')){
			$this->setEnabled(false);
		}


		/*if (isset($_GET['app']) && $_GET['app'] == 'checkout' && (!Session::exists('onlyReservations') || Session::get('onlyReservations') == false)){
			$this->setEnabled(false);
		} */
		if(class_exists('ModulesShippingZoneReservationMethods')){

			$Qmethods = Doctrine_Query::create()
				->from('ModulesShippingZoneReservationMethods m')
				->leftJoin('m.ModulesShippingZoneReservationMethodsDescription md')
				->orderBy('sort_order');
			try{
			$Qmethods = $Qmethods->execute();
			if ($Qmethods){
				foreach($Qmethods as $mInfo){
					$this->methods[$mInfo['method_id']] = array(
						'status' => $mInfo['method_status'],
						'text' => $mInfo['ModulesShippingZoneReservationMethodsDescription'][Session::get('languages_id')]['method_text'],
						'details' => $mInfo['ModulesShippingZoneReservationMethodsDescription'][Session::get('languages_id')]['method_details'],
						'cost' => $mInfo['method_cost'],
						'days_before' => $mInfo['method_days_before'],
						'days_after' => $mInfo['method_days_after'],
						'sort_order' => $mInfo['sort_order'],
						'weight_rates' => $mInfo['weight_rates'],
						'min_rental_number' => $mInfo['min_rental_number'],
						'min_rental_type' => $mInfo['min_rental_type'],
						'default' => $mInfo['method_default'],
						'zipcode' => $mInfo['method_zipcode'],
						'zipcodesArr' => explode("\n", $mInfo['method_zipcode']),
						'free_delivery_over' => $mInfo['free_delivery_over'],
						'zone' => $mInfo['method_zone']
					);
					foreach(sysLanguage::getLanguages() as $lInfo){
						if (isset($mInfo['ModulesShippingZoneReservationMethodsDescription'][$lInfo['id']]['method_text'])){
							$this->methods[$mInfo['method_id']][$lInfo['id']]['text'] = $mInfo['ModulesShippingZoneReservationMethodsDescription'][$lInfo['id']]['method_text'];
						}
						if (isset($mInfo['ModulesShippingZoneReservationMethodsDescription'][$lInfo['id']]['method_details'])){
							$this->methods[$mInfo['method_id']][$lInfo['id']]['details'] = $mInfo['ModulesShippingZoneReservationMethodsDescription'][$lInfo['id']]['method_details'];
						}
					}
				}
			}
			}catch(Doctrine_Connection_Exception $e){

			}
		}
	}

	public function getNumBoxes(&$shipping_weight, &$shipping_num_boxes){
		$boxWeight = sysConfig::get('SHIPPING_BOX_WEIGHT');
		$boxPadding = sysConfig::get('SHIPPING_BOX_PADDING');
		$boxMaxWeight = sysConfig::get('SHIPPING_MAX_WEIGHT');


		if ($boxWeight >= $shipping_weight * $boxPadding / 100) {
			$shipping_weight = $shipping_weight + $boxWeight;
		} else {
			$shipping_weight = $shipping_weight + ($shipping_weight * $boxPadding / 100);
		}

		if ($shipping_weight > $boxMaxWeight) { // Split into many boxes
			$shipping_num_boxes = ceil($shipping_weight / $boxMaxWeight);
			$shipping_weight = $shipping_weight / $shipping_num_boxes;
		}
	}

	public function getType(){
		return $this->type;
	}

	public function getMethods() {
		return $this->methods;
	}
	
	public function quote($method = '', $shipping_weight_prod = -1, $totalPrice = -1){
		global $order;
			$this->quotes = array(
				'id' => $this->getCode(),
				'module' => $this->getTitle(),
				'methods' => array()
			);

			$shipping_num_boxes_prod = 1;
			$this->getNumBoxes($shipping_weight_prod, $shipping_num_boxes_prod);//adding boxes weight

		if(sysConfig::get('EXTENSION_PAY_PER_RENTALS_CHECK_GOOGLE_ZONES_BEFORE') == 'True'){
			$billingAddress = $this->getBillingAddress();
			$countryName = tep_get_country_name($billingAddress['entry_country_id']);
			$point = array(
				'entry_street_address' => $billingAddress['entry_street_address'],
				'entry_city'           => $billingAddress['entry_city'],
				'entry_postcode'       => $billingAddress['entry_postcode'],
				'entry_country_name'   => $countryName,
				'entry_state'          => $billingAddress['entry_state']
			);
			$coordinates = getPPRGoogleCoordinates($point);
			//print_r($coordinates);
			//echo 'ccooooo';
			$shipMethodsIn = getShippingMethods($coordinates['lng'], $coordinates['lat'], $this->methods);
			$shippingMethodsIds = array();
			for($i=0; $i<sizeof($shipMethodsIn); $i++){
				$shippingMethodsIds[] = $shipMethodsIn[$i]['id'];
			}
		}
			foreach($this->methods as $methodId => $mInfo){
				if(Session::exists('current_store_id') && sysConfig::get('EXTENSION_PAY_PER_RENTALS_USE_ZIPCODES_SHIPPING') == 'True' && Session::exists('zipClient'.Session::get('current_store_id')) === true){
					if(!in_array((int)Session::get('zipClient'.Session::get('current_store_id')), $mInfo['zipcodesArr']) || Session::get('zipClient'.Session::get('current_store_id')) == ''){
						continue;
					}
				}
				if(sysConfig::get('EXTENSION_PAY_PER_RENTALS_CHECK_GOOGLE_ZONES_BEFORE') == 'True' && !in_array((int)$methodId, $shippingMethodsIds)){
					continue;
				}
				if ($mInfo['status'] == 'True' && ($method == 'method' . $methodId || $method == '')){

					$shippingCost =  $mInfo['cost'];
					$tableRates = explode(',', $mInfo['weight_rates']);
					foreach($tableRates as $rate){
						if(!empty($rate)){
							$rInfo = explode(':', $rate);
							if ($shipping_weight_prod <= $rInfo[0]) {
								$shippingCost = $rInfo[1];
								break;
							}
						}
					}

					if($this->type == 'Order' && (isset($_GET['app']) && $_GET['app'] != 'checkout') && sysConfig::get('EXTENSION_PAY_PER_RENTALS_SHOW_SHIPPING_ON_CALENDAR_IF_ORDER') == 'False'){
						$shippingCost = 0;
					}
					if($mInfo['free_delivery_over'] <= $totalPrice && $mInfo['free_delivery_over'] != -1 ){
						$shippingCost = 0;
					}
					$showCost = $shippingCost;
					$this->quotes['methods'][] = array(
						'id' => 'method' . $methodId,
						'title' => $mInfo['text'],
						'cost'    => $shippingCost,
						'showCost' => $showCost,
						'default' => $mInfo['default'],
						'details' => $mInfo['details'],
						'days_before' => $mInfo['days_before'],
						'days_after' => $mInfo['days_after'],
						'free_delivery_over' => $mInfo['free_delivery_over'],
						'min_rental_number'    => $mInfo['min_rental_number'],
						'min_rental_type'    => $mInfo['min_rental_type'],
						'zone' => $mInfo['zone']
					);
				}
			}

			$classId = $this->getTaxClass();
			if ($classId > 0){
				$deliveryAddress = $this->getDeliveryAddress();
				$this->quotes['tax'] = tep_get_tax_rate($classId, $deliveryAddress['country_id'], $deliveryAddress['zone_id']);
			}

			return $this->quotes;
	}
	
	public function getZonesMenu($name, $value = 0){
		$selectBox = htmlBase::newElement('selectbox')
			->setName($name);

		$selectBox->addOption('0', 'Everywhere');
		$QGoogleZones = Doctrine_Query::create()
			->from('GoogleZones')
			->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		if ($QGoogleZones){
			foreach($QGoogleZones as $zInfo){
				$selectBox->addOption($zInfo['google_zones_id'], $zInfo['google_zones_name']);
			}
		}

		$selectBox->selectOptionByValue($value);
		return $selectBox->draw();
	}

	public function getTaxClass(){
		return 0;
	}
}
?>