<?php
/*
	Customer Custom Fields Extension Version 1
	
	I.T. Web Experts, Rental Store v2
	http://www.itwebexperts.com

	Copyright (c) 2009 I.T. Web Experts

	This script and it's source is not redistributable
*/

class CustomerCustomFieldsGroups extends Doctrine_Record {
	
	public function setUp(){
		$this->hasMany('CustomerCustomFieldsToGroups', array(
			'local'   => 'group_id',
			'foreign' => 'group_id',
			'cascade' => array('delete')
		));
	}
	
	public function setTableDefinition(){
		$this->setTableName('customer_custom_fields_groups');
		
		$this->hasColumn('group_id', 'integer', 4, array(
			'type' => 'integer',
			'length' => 4,
			'unsigned' => 0,
			'primary' => true,
			'autoincrement' => true,
		));
		
		$this->hasColumn('group_name', 'string', 64, array(
			'type' => 'string',
			'length' => 64,
			'fixed' => false,
			'primary' => false,
			'notnull' => true,
			'autoincrement' => false,
		));
		$this->hasColumn('group_legend', 'string', null, array(
			'type' => 'string',
			'length' => null,
			'fixed' => false,
			'primary' => false,
			'notnull' => true,
			'autoincrement' => false,
		));
	}
}