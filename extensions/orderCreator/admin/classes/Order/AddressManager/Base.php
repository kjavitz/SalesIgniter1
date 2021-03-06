<?php
require(dirname(__FILE__) . '/Address.php');

class OrderCreatorAddressManager extends OrderAddressManager implements Serializable {

	public function __construct($addressArray = null){
		$this->addressHeadings['customer'] = sysLanguage::get('TEXT_ADDRESS_CUSTOMER');

		if(sysConfig::get('EXTENSION_ORDER_CREATOR_SHOW_BILLING_ADDRESS') == 'True'){
			$this->addressHeadings['billing'] = sysLanguage::get('TEXT_ADDRESS_BILLING');
		}
		if(sysConfig::get('EXTENSION_ORDER_CREATOR_SHOW_DELIVERY_ADDRESS') == 'True'){
			$this->addressHeadings['delivery'] = sysLanguage::get('TEXT_ADDRESS_DELIVERY');
		}

		if (sysConfig::exists('EXTENSION_PAY_PER_RENTALS_CHOOSE_PICKUP') && sysConfig::get('EXTENSION_PAY_PER_RENTALS_CHOOSE_PICKUP') == 'True'){
			$this->addressHeadings['pickup'] = sysLanguage::get('TEXT_ADDRESS_PICKUP');
		}

		if (is_null($addressArray) === false){
			foreach($addressArray as $type => $aInfo){
				if (isset($this->addressHeadings[$type])){
					$this->addresses[$type] = new OrderCreatorAddress($aInfo);
				}
			}
		}else{
			foreach($this->addressHeadings as $type => $heading){
				$this->addresses[$type] = new OrderCreatorAddress(array(
					'address_type' => $type
				));
			}
		}
	}

	public function serialize(){
		$data = array(
			'orderId' => $this->orderId,
			'addressHeadings' => $this->addressHeadings,
			'addresses' => $this->addresses
		);
		return serialize($data);
	}

	public function unserialize($data){
		$data = unserialize($data);
		foreach($data as $key => $dInfo){
			$this->$key = $dInfo;
		}
	}
	
	public function addAddressObj($addressObj){
		$this->addresses[$addressObj->getAddressType()] = $addressObj;
	}
	
	public function updateFromPost(){
		if (!isset($_POST['address']['billing'])){
			$_POST['address']['billing'] = $_POST['address']['customer'];
		}
		if (!isset($_POST['address']['delivery'])){
			$_POST['address']['delivery'] = $_POST['address']['customer'];
		}

		foreach($_POST['address'] as $type => $aInfo){
			if(isset($this->addresses[$type])){
				$this->addresses[$type]->setName($aInfo['entry_name']);
				$this->addresses[$type]->setFiscalCode($aInfo['entry_cif']);
				$this->addresses[$type]->setVATNumber($aInfo['entry_vat']);
				$this->addresses[$type]->setCompany($aInfo['entry_company']);
				$this->addresses[$type]->setStreetAddress($aInfo['entry_street_address']);
				$this->addresses[$type]->setSuburb($aInfo['entry_suburb']);
				$this->addresses[$type]->setCity($aInfo['entry_city']);
				$this->addresses[$type]->setPostcode($aInfo['entry_postcode']);
				$this->addresses[$type]->setState($aInfo['entry_state']);
				$this->addresses[$type]->setCountry($aInfo['entry_country']);
			}
		}
	}
	
	public function addAllToCollection(&$CollectionObj){
		$CollectionObj->clear();
		foreach($this->addresses as $type => $addressObj){
			$Address = new OrdersAddresses();
			$Address->address_type = $type;
			$Address->entry_format_id = $addressObj->getFormatId();
			$Address->entry_name = $addressObj->getName();
			if(sysConfig::get('ACCOUNT_COMPANY') == 'true'){
				$Address->entry_company = $addressObj->getCompany();
			}
			$Address->entry_street_address = $addressObj->getStreetAddress();
			$Address->entry_suburb = $addressObj->getSuburb();
			$Address->entry_city = $addressObj->getCity();
			$Address->entry_postcode = $addressObj->getPostcode();
			$Address->entry_state = $addressObj->getState();
			$Address->entry_country = $addressObj->getCountry();
			if(sysConfig::get('ACCOUNT_FISCAL_CODE') == 'true'){
				$Address->entry_cif = $addressObj->getCIF();
			}
			if(sysConfig::get('ACCOUNT_VAT_NUMBER') == 'true'){
				$Address->entry_vat = $addressObj->getVAT();
			}
			$CollectionObj->add($Address);
		}
	}

	public function saveAll(){
		$OrdersAddresses = Doctrine_Core::getTable('OrdersAddresses');
		foreach($this->addresses as $type => $addressObj){
			if (is_null($this->orderId) === true){
				$Address = $OrdersAddresses->create();
			}else{
				$Address = $OrdersAddresses->find($addressObj->getId());
				if (!$Address){
					$Address = $OrdersAddresses->create();
					$Address->orders_id = $this->orderId;
					$Address->address_type = $addressObj->getType();
				}
			}

			$Address->entry_format_id = $addressObj->getFormatId();
			$Address->entry_name = $addressObj->getName();

			if(sysConfig::get('ACCOUNT_COMPANY') == 'true'){
				$Address->entry_company = $addressObj->getCompany();
			}

			$Address->entry_street_address = $addressObj->getStreetAddress();
			$Address->entry_suburb = $addressObj->getSuburb();
			$Address->entry_city = $addressObj->getCity();
			$Address->entry_postcode = $addressObj->getPostcode();
			$Address->entry_state = $addressObj->getState();
			$Address->entry_country = $addressObj->getCountry();
			if(sysConfig::get('ACCOUNT_FISCAL_CODE') == 'true'){
				$Address->entry_cif = $addressObj->getCIF();
			}
			if(sysConfig::get('ACCOUNT_VAT_NUMBER') == 'true'){
				$Address->entry_vat = $addressObj->getVAT();
			}
			$Address->save();
		}
	}

	public function hasPickup(){
		$has = false;
		foreach($this->addressHeadings as $type => $heading){
			if($type == 'pickup'){
				$has = true;
				break;
			}
		}
		return $has;
	}

	public function editPickup(){
		global $Editor;
		$addressesTable = htmlBase::newElement('table')
				->setCellPadding(2)
				->setCellSpacing(0)
				->addClass('addressTablePickup')
				->css('width', '100%');

		$addressesRow = array();
		foreach($this->addressHeadings as $type => $heading){
			if($type == 'pickup'){
				$addressObj = $this->addresses[$type];
				$addressTable = htmlBase::newElement('table')
						->setCellPadding(2)
						->setCellSpacing(0)
						->css('width', '100%');

				$addressTable->addBodyRow(array(
					'columns' => array(
						array(
							'addCls' => 'main',
							'valign' => 'top',
							'text' => '<b>' . $heading . '</b>'
						)
					)
				));

				$customerId = '';
				if ($Editor->getCustomerId() > 0){
					$customerId = htmlBase::newElement('input')
							->setType('hidden')
							->setName('customers_id')
							->val((int) $Editor->getCustomerId())
							->draw();
				}

				$addressTable->addBodyRow(array(
					'columns' => array(
						array(
							'addCls' => 'main ' . $type . 'Address',
							'valign' => 'top',
							'text' => $this->editAddress($addressObj) . $customerId
						)
					)
				));

				$addressesRow[] = array(
					'valign' => 'top',
					'text' => $addressTable
				);
			}
		}
		$addressesTable->addBodyRow(array(
			'columns' => $addressesRow
		));

		return $addressesTable->draw();
	}

	public function editShipping(){
		global $Editor;
		$addressesTable = htmlBase::newElement('table')
				->setCellPadding(2)
				->setCellSpacing(0)
				->addClass('addressTableShipping')
				->css('width', '100%');

		$addressesRow = array();
		foreach($this->addressHeadings as $type => $heading){
			if($type == 'delivery'){
				$addressObj = $this->addresses[$type];
				$addressTable = htmlBase::newElement('table')
						->setCellPadding(2)
						->setCellSpacing(0)
						->css('width', '100%');

				$addressTable->addBodyRow(array(
					'columns' => array(
						array(
							'addCls' => 'main',
							'valign' => 'top',
							'text' => '<b>' . $heading . '</b>'
						)
					)
				));

				$customerId = '';
				if ($Editor->getCustomerId() > 0){
					$customerId = htmlBase::newElement('input')
							->setType('hidden')
							->setName('customers_id')
							->val((int) $Editor->getCustomerId())
							->draw();
				}

				$addressTable->addBodyRow(array(
					'columns' => array(
						array(
							'addCls' => 'main ' . $type . 'Address',
							'valign' => 'top',
							'text' => $this->editAddress($addressObj) . $customerId
						)
					)
				));

				$addressesRow[] = array(
					'valign' => 'top',
					'text' => $addressTable
				);
			}
		}
		$addressesTable->addBodyRow(array(
			'columns' => $addressesRow
		));

		return $addressesTable->draw();
	}

	public function editBilling(){
		global $Editor;
		$addressesTable = htmlBase::newElement('table')
				->setCellPadding(2)
				->setCellSpacing(0)
				->addClass('addressTableBilling')
				->css('width', '100%');

		$addressesRow = array();
		foreach($this->addressHeadings as $type => $heading){
			if($type == 'billing'){
				$addressObj = $this->addresses[$type];
				$addressTable = htmlBase::newElement('table')
						->setCellPadding(2)
						->setCellSpacing(0)
						->css('width', '100%');

				$addressTable->addBodyRow(array(
					'columns' => array(
						array(
							'addCls' => 'main',
							'valign' => 'top',
							'text' => '<b>' . $heading . '</b>'
						)
					)
				));

				$customerId = '';
				if ($Editor->getCustomerId() > 0){
					$customerId = htmlBase::newElement('input')
							->setType('hidden')
							->setName('customers_id')
							->val((int) $Editor->getCustomerId())
							->draw();
				}

				$addressTable->addBodyRow(array(
					'columns' => array(
						array(
							'addCls' => 'main ' . $type . 'Address',
							'valign' => 'top',
							'text' => $this->editAddress($addressObj) . $customerId
						)
					)
				));

				$addressesRow[] = array(
					'valign' => 'top',
					'text' => $addressTable
				);
			}
		}
		$addressesTable->addBodyRow(array(
			'columns' => $addressesRow
		));

		return $addressesTable->draw();
	}

	public function editCustomer(){
		global $Editor;
		$addressesTable = htmlBase::newElement('table')
				->setCellPadding(2)
				->setCellSpacing(0)
				->addClass('addressTableCustomer')
				->css('width', '100%');

		$addressesRow = array();
		foreach($this->addressHeadings as $type => $heading){
			if($type == 'customer'){
				$addressObj = $this->addresses[$type];
				$addressTable = htmlBase::newElement('table')
						->setCellPadding(2)
						->setCellSpacing(0)
						->css('width', '100%');

				$addressTable->addBodyRow(array(
					'columns' => array(
						array(
							'addCls' => 'main',
							'valign' => 'top',
							'text' => '<b>' . $heading . '</b>'
						)
					)
				));

				$customerId = '';
				if ($Editor->getCustomerId() > 0){
					$customerId = htmlBase::newElement('input')
							->setType('hidden')
							->setName('customers_id')
							->val((int) $Editor->getCustomerId())
							->draw();
				}

				$addressTable->addBodyRow(array(
					'columns' => array(
						array(
							'addCls' => 'main ' . $type . 'Address',
							'valign' => 'top',
							'text' => $this->editAddress($addressObj) . $customerId
						)
					)
				));

				$addressesRow[] = array(
					'valign' => 'top',
					'text' => $addressTable
				);
			}
		}
		$addressesTable->addBodyRow(array(
			'columns' => $addressesRow
		));

		return $addressesTable->draw();
	}

	public function editAll(){
		global $Editor;
		$addressesTable = htmlBase::newElement('table')
				->setCellPadding(2)
				->setCellSpacing(0)
				->addClass('addressTable')
				->css('width', '100%');

		$addressesRow = array();
		foreach($this->addressHeadings as $type => $heading){
			$addressObj = $this->addresses[$type];
			$addressTable = htmlBase::newElement('table')
					->setCellPadding(2)
					->setCellSpacing(0)
					->css('width', '100%');

			$addressTable->addBodyRow(array(
				'columns' => array(
					array(
						'addCls' => 'main',
						'valign' => 'top',
						'text' => '<b>' . $heading . '</b>'
					)
				)
			));

			$customerId = '';
			if ($Editor->getCustomerId() > 0){
				$customerId = htmlBase::newElement('input')
				->setType('hidden')
				->setName('customers_id')
				->val((int) $Editor->getCustomerId())
				->draw();
			}
			
			$addressTable->addBodyRow(array(
				'columns' => array(
					array(
						'addCls' => 'main ' . $type . 'Address',
						'valign' => 'top',
						'text' => $this->editAddress($addressObj) . $customerId
					)
				)
			));

			$addressesRow[] = array(
				'valign' => 'top',
				'text' => $addressTable
			);
		}
		$addressesTable->addBodyRow(array(
			'columns' => $addressesRow
		));

		return $addressesTable->draw();
	}

	public function editAddress($type){
		$htmlTable = htmlBase::newElement('table')
				->setCellPadding(2)
				->setCellSpacing(0);

		$Address = null;
		if (is_object($type) === true){
			$Address = $type;
		}elseif (array_key_exists($type, $this->addresses) === true){
			$Address = $this->addresses[$type];
		}

		if (is_null($Address) === true){
			$aType = 'customer';
		}else{
			$aType = $Address->getAddressType();
		}

		$nameInput = htmlBase::newElement('input')->setName('address[' . $aType . '][entry_name]');
		$companyInput = htmlBase::newElement('input')->setName('address[' . $aType . '][entry_company]');
		$addressInput = htmlBase::newElement('input')->setName('address[' . $aType . '][entry_street_address]');
		$suburbInput = htmlBase::newElement('input')->setName('address[' . $aType . '][entry_suburb]');
		$cityInput = htmlBase::newElement('input')->setName('address[' . $aType . '][entry_city]');
		$cifInput = htmlBase::newElement('input')->setName('address[' . $aType . '][entry_cif]');
		$vatInput = htmlBase::newElement('input')->setName('address[' . $aType . '][entry_vat]');

		$postcodeInput = htmlBase::newElement('input')->setName('address[' . $aType . '][entry_postcode]');

		$countryInput = htmlBase::newElement('selectbox')
		->addClass('country')
		->attr('data-address_type', $aType)
		->setName('address[' . $aType . '][entry_country]')
		->css(array('width' => '150px'));
		
		$Qcountries = Doctrine_Query::create()
		->select('countries_name, countries_iso_code_2')
		->from('Countries')
		->orderBy('countries_name')
		->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

		foreach($Qcountries as $cInfo){
            $iso = array();
            $iso['iso_code'] = $cInfo['countries_iso_code_2'];
            $countryInput->addOption($cInfo['countries_name'], $cInfo['countries_name'],false, $iso);

        }
		if (is_null($Address) === false){
			$Qcountry = Doctrine_Query::create()
					->select('countries_id')
					->from('Countries')
					->where('countries_name = ?', $Address->getCountry())
					->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
			if ($Qcountry){
				$addressCountryId = $Qcountry[0]['countries_id'];

				$stateInput = htmlBase::newElement('selectbox')->setName('address[' . $aType . '][entry_state]')->css(array('width' => '150px'));

				$Qzones = Doctrine_Query::create()
						->select('zone_name, zone_code')
						->from('Zones')
						->where('zone_country_id = ?', $addressCountryId)
						->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
				foreach($Qzones as $zInfo){
                    $iso = array();
                    $iso['iso_code'] = $zInfo['zone_name'];
                    $stateInput->addOption($zInfo['zone_name'], $zInfo['zone_name'],false,$iso);
                }
			}
		}

		if (!isset($stateInput)){
			$stateInput = htmlBase::newElement('input')->setName('address[' . $aType . '][entry_state]');
		}

		if (is_null($Address) === false){
			$nameInput->val($Address->getName());
			if(sysConfig::get('ACCOUNT_COMPANY') == 'true'){
				$companyInput->val($Address->getCompany());
			}

			$addressInput->val($Address->getStreetAddress());
			$suburbInput->val($Address->getSuburb());
			$cityInput->val($Address->getCity());
			if(sysConfig::get('ACCOUNT_FISCAL_CODE') == 'true'){
				$cifInput->val($Address->getCIF());
			}
			if(sysConfig::get('ACCOUNT_VAT_NUMBER') == 'true'){
				$vatInput->val($Address->getVAT());
			}

			$postcodeInput->val($Address->getPostcode());

			if ($stateInput->isType('select')){
				$stateInput->selectOptionByValue($Address->getState());
			}else{
				$stateInput->val($Address->getState());
			}
			if($Address->getCountry() != ''){
				$countryInput->selectOptionByValue($Address->getCountry());
			}else{
				$countryInput->selectOptionByValue(tep_get_country_name(sysConfig::get('ONEPAGE_DEFAULT_COUNTRY')));
			}
		}

		$htmlTable->addBodyRow(array(
			'columns' => array(
				array('text' => sysLanguage::get('ENTRY_NAME')),
				array('text' => $nameInput)
			)
		));

		if(sysConfig::get('ACCOUNT_COMPANY') == 'true'){
			$htmlTable->addBodyRow(array(
				'columns' => array(
					array('text' => sysLanguage::get('ENTRY_COMPANY')),
					array('text' => $companyInput)
				)
			));
		}
		if(sysConfig::get('ACCOUNT_FISCAL_CODE') == 'true'){
			$htmlTable->addBodyRow(array(
					'columns' => array(
						array('text' => sysLanguage::get('ENTRY_CIF')),
						array('text' => $cifInput)
					)
			));
		}
		if(sysConfig::get('ACCOUNT_VAT_NUMBER') == 'true'){
			$htmlTable->addBodyRow(array(
					'columns' => array(
						array('text' => sysLanguage::get('ENTRY_VAT')),
						array('text' => $vatInput)
					)
			));
		}

		$htmlTable->addBodyRow(array(
			'columns' => array(
				array('text' => sysLanguage::get('ENTRY_STREET_ADDRESS')),
				array('text' => $addressInput)
			)
		));

		$htmlTable->addBodyRow(array(
			'columns' => array(
				array('text' => sysLanguage::get('ENTRY_SUBURB')),
				array('text' => $suburbInput)
			)
		));

		$htmlTable->addBodyRow(array(
			'columns' => array(
				array('text' => sysLanguage::get('ENTRY_CITY')),
				array('text' => $cityInput)
			)
		));

		$htmlTable->addBodyRow(array(
			'columns' => array(
				array('text' => sysLanguage::get('ENTRY_POST_CODE')),
				array('text' => $postcodeInput)
			)
		));

		$htmlTable->addBodyRow(array(
			'columns' => array(
				array('text' => sysLanguage::get('ENTRY_STATE')),
				array('addCls' => 'stateCol', 'text' => $stateInput)
			)
		));

		$htmlTable->addBodyRow(array(
			'columns' => array(
				array('text' => sysLanguage::get('ENTRY_COUNTRY')),
				array('text' => $countryInput)
			)
		));
		

		EventManager::notify('OrderEditorAddressOnEdit', $htmlTable);

		return $htmlTable->draw();
	}
	
	public function getCopyToButtons(){
		$buttons = '';
		foreach($this->addressHeadings as $addressType => $addressHeading){
			if ($addressType == 'customer') continue;
			
			$buttons .= htmlBase::newElement('button')
			->addClass('addressCopyButton')
			->attr('data-copy_from', 'customer')
			->attr('data-copy_to', $addressType)
			->setText(substr($addressHeading, 0, strpos($addressHeading, ' ')))
			->draw();
		}
		
		return sysLanguage::get('TEXT_COPY_TO') . $buttons;
	}
}
?>