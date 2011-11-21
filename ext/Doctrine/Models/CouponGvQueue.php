<?php

/**
 * CouponGvQueue
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6401 2009-09-24 16:12:04Z guilhermeblanco $
 */
class CouponGvQueue extends Doctrine_Record {
	
	public function setUp(){
		$this->setUpParent();
		
		$this->hasOne('Customers', array(
			'local' => 'customer_id',
			'foreign' => 'customers_id'
		));
		
		$this->hasOne('Orders', array(
			'local' => 'order_id',
			'foreign' => 'orders_id'
		));
	}
	
	public function setUpParent(){
		$Customers = Doctrine_Core::getTable('Customers')->getRecordInstance();
		$Orders = Doctrine_Core::getTable('Orders')->getRecordInstance();
		
		$Customers->hasMany('CouponGvQueue', array(
			'local' => 'customers_id',
			'foreign' => 'customer_id',
			'cascade' => array('delete')
		));
		
		$Orders->hasOne('CouponGvQueue', array(
			'local' => 'orders_id',
			'foreign' => 'order_id',
			'cascade' => array('delete')
		));
	}
	
	public function preInsert($event){
		$this->date_created = date('Y-m-d');
	}

	public function setTableDefinition(){
		$this->setTableName('coupon_gv_queue');
		
		$this->hasColumn('unique_id', 'integer', 4, array(
			'type' => 'integer',
			'length' => 4,
			'unsigned' => 0,
			'primary' => true,
			'autoincrement' => true,
		));
		$this->hasColumn('customer_id', 'integer', 4, array(
			'type' => 'integer',
			'length' => 4,
			'unsigned' => 0,
			'primary' => false,
			'default' => '0',
			'notnull' => true,
			'autoincrement' => false,
		));
		$this->hasColumn('order_id', 'integer', 4, array(
			'type' => 'integer',
			'length' => 4,
			'unsigned' => 0,
			'primary' => false,
			'default' => '0',
			'notnull' => true,
			'autoincrement' => false,
		));
		$this->hasColumn('amount', 'decimal', 8, array(
			'type' => 'decimal',
			'length' => 8,
			'unsigned' => 0,
			'primary' => false,
			'default' => '0.0000',
			'notnull' => true,
			'autoincrement' => false,
			'scale' => 4,
		));
		$this->hasColumn('date_created', 'timestamp', null, array(
			'type' => 'timestamp',
			'primary' => false,
			'default' => '0000-00-00 00:00:00',
			'notnull' => true,
			'autoincrement' => false,
		));
		$this->hasColumn('ipaddr', 'string', 32, array(
			'type' => 'string',
			'length' => 32,
			'fixed' => false,
			'primary' => false,
			'default' => '',
			'notnull' => true,
			'autoincrement' => false,
		));
		$this->hasColumn('release_flag', 'string', 1, array(
			'type' => 'string',
			'length' => 1,
			'fixed' => true,
			'primary' => false,
			'default' => 'N',
			'notnull' => true,
			'autoincrement' => false,
		));
	}
}