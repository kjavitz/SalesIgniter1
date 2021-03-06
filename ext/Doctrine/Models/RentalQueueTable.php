<?php

/**
 * RentalQueue
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6401 2009-09-24 16:12:04Z guilhermeblanco $
 */
class RentalQueueTable extends Doctrine_Record {
	
	public function setUp(){
		$this->setUpParent();
	}
	
	public function setUpParent(){
		$Customers = Doctrine_Core::getTable('Customers')->getRecordInstance();
		$Products = Doctrine_Core::getTable('Products')->getRecordInstance();
		
		$Customers->hasMany('RentalQueueTable', array(
			'local' => 'customers_id',
			'foreign' => 'customers_id',
			'cascade' => array('delete')
		));
		
		$Products->hasMany('RentalQueueTable', array(
			'local' => 'products_id',
			'foreign' => 'products_id',
			'cascade' => array('delete')
		));
	}
	
	public function preInsert($event){
		$this->date_added = date('Y-m-d h:i:s');
	}
	
	public function setTableDefinition(){
		$this->setTableName('rental_queue');
		
		$this->hasColumn('customers_queue_id', 'integer', 4, array(
		'type' => 'integer',
		'length' => 4,
		'unsigned' => 0,
		'primary' => true,
		'autoincrement' => true,
		));
		$this->hasColumn('customers_id', 'integer', 4, array(
		'type' => 'integer',
		'length' => 4,
		'unsigned' => 0,
		'primary' => false,
		'default' => '0',
		'notnull' => true,
		'autoincrement' => false,
		));
		$this->hasColumn('products_id', 'string', null, array(
		'type' => 'string',
		'fixed' => false,
		'primary' => false,
		'notnull' => true,
		'autoincrement' => false,
		));
		$this->hasColumn('priority', 'integer', 4, array(
		'type' => 'integer',
		'length' => 4,
		'unsigned' => 0,
		'primary' => false,
		'default' => '1',
		'notnull' => true,
		'autoincrement' => false,
		));
		$this->hasColumn('date_added', 'timestamp', null, array(
		'type' => 'timestamp',
		'primary' => false,
		'notnull' => false,
		'autoincrement' => false,
		));
		$this->hasColumn('status', 'enum', 1, array(
		'type' => 'enum',
		'length' => 1,
		'fixed' => false,
		'values' =>
		array(
		0 => 'L',
		1 => 'S',
		2 => 'A',
		),
		'primary' => false,
		'default' => 'S',
		'notnull' => true,
		'autoincrement' => false,
		));
	}
}