<?php
/*
	Multi Stores Extension Version 1.1
	
	I.T. Web Experts, Rental Store v2
	http://www.itwebexperts.com

	Copyright (c) 2009 I.T. Web Experts

	This script and it's source is not redistributable
*/

class Stores extends Doctrine_Record {

	public function setTableDefinition(){
		$this->setTableName('stores');
		
		$this->hasColumn('stores_id', 'integer', 4, array(
			'type'          => 'integer',
			'length'        => 4,
			'unsigned'      => 0,
			'primary'       => true,
			'notnull'       => true,
			'autoincrement' => true,
		));

/* Auto Upgrade ( Version 1.0 to 1.1 ) --BEGIN-- */
		$this->hasColumn('stores_owner', 'string', 128, array(
			'type'          => 'string',
			'length'        => 128,
			'fixed'         => false,
			'primary'       => false,
			'notnull'       => true,
			'autoincrement' => false,
		));
/* Auto Upgrade ( Version 1.0 to 1.1 ) --END-- */

		$this->hasColumn('stores_name', 'string', 128, array(
			'type'          => 'string',
			'length'        => 128,
			'fixed'         => false,
			'primary'       => false,
			'notnull'       => true,
			'autoincrement' => false,
		));
		
		$this->hasColumn('stores_email', 'string', 128, array(
			'type'          => 'string',
			'length'        => 128,
			'fixed'         => false,
			'primary'       => false,
			'notnull'       => true,
			'autoincrement' => false,
		));
		
		$this->hasColumn('stores_domain', 'string', 128, array(
			'type'          => 'string',
			'length'        => 128,
			'fixed'         => false,
			'primary'       => false,
			'notnull'       => true,
			'autoincrement' => false,
		));
		
		$this->hasColumn('stores_ssl_domain', 'string', 128, array(
			'type'          => 'string',
			'length'        => 128,
			'fixed'         => false,
			'primary'       => false,
			'notnull'       => true,
			'autoincrement' => false,
		));
		
		$this->hasColumn('stores_template', 'string', 128, array(
			'type'          => 'string',
			'length'        => 128,
			'fixed'         => false,
			'primary'       => false,
			'notnull'       => true,
			'autoincrement' => false,
		));
		
		$this->hasColumn('google_key', 'string', 255, array(
			'type'          => 'string',
			'length'        => 255,
			'fixed'         => false,
			'primary'       => false,
			'notnull'       => true,
			'autoincrement' => false,
		));
		
		$this->hasColumn('stores_zip', 'string', 10, array(
			'type'          => 'string',
			'length'        => 10,
			'fixed'         => false,
			'primary'       => false,
			'notnull'       => true,
			'autoincrement' => false,
		));		
		
		$this->hasColumn('stores_location', 'string', 100, array(
			'type'          => 'string',
			'length'        => 100,
			'fixed'         => false,
			'primary'       => false,
			'notnull'       => true,
			'autoincrement' => false,
		));

		$this->hasColumn('stores_telephone', 'string', 100, array(
				'type'          => 'string',
				'length'        => 100,
				'fixed'         => false,
				'primary'       => false,
				'notnull'       => true,
				'autoincrement' => false,
		));

		$this->hasColumn('stores_group', 'string', 200, array(
				'type'          => 'string',
				'length'        => 200,
				'fixed'         => false,
				'primary'       => false,
				'notnull'       => true,
				'autoincrement' => false,
		));

		$this->hasColumn('stores_countries', 'string', null, array(
				'type'          => 'string',
				'length'        => null,
				'primary'       => false,
				'autoincrement' => false,
		));
		$this->hasColumn('stores_info', 'string', null, array(
				'type'          => 'string',
				'length'        => null,
				'primary'       => false,
				'autoincrement' => false,
		));
		$this->hasColumn('is_default', 'integer', 1, array(
				'type' => 'integer',
				'length' => 1,
				'unsigned' => 0,
				'default' => 0,
				'primary' => false,
				'autoincrement' => false
		));

		$this->hasColumn('home_redirect_store_info', 'integer', 1, array(
				'type' => 'integer',
				'length' => 1,
				'unsigned' => 0,
				'default' => 0,
				'primary' => false,
				'autoincrement' => false
		));

		$this->hasColumn('default_currency', 'string', 3, array(
				'type' => 'string',
				'length' => 3,
				'default' => 'USD',
				'primary' => false,
				'autoincrement' => false
		));
		$this->hasColumn('default_language', 'string', 100, array(
				'type' => 'string',
				'length' => 100,
				'default' => 'en',
				'primary' => false,
				'autoincrement' => false
		));

        $this->hasColumn('commission', 'integer', 4, array(
            'type'          => 'integer',
            'length'        => 4,
            'unsigned'      => 0,
        ));

        $this->hasColumn('commission_type', 'integer', 4, array(
            'type'          => 'integer',
            'length'        => 4,
            'unsigned'      => 0,
        ));

	}
	
}