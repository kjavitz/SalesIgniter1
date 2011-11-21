<?php

/**
 * OrdersProducts
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6401 2009-09-24 16:12:04Z guilhermeblanco $
 */
class OrdersProducts extends Doctrine_Record {

	public function setUp(){
		$this->setUpParent();
		
		$this->hasOne('Orders', array(
			'local' => 'orders_id',
			'foreign' => 'orders_id'
		));
		
		$this->hasOne('Products', array(
			'local' => 'products_id',
			'foreign' => 'products_id'
		));

		$this->hasOne('ProductsInventoryBarcodes', array(
			'local' => 'barcode_id',
			'foreign' => 'barcode_id'
		));
		
		$this->hasOne('ProductsInventoryQuantity', array(
			'local' => 'quantity_id',
			'foreign' => 'quantity_id'
		));
	}

	public function setUpParent(){
		$Products = Doctrine::getTable('Products')->getRecordInstance();

		$Products->hasMany('OrdersProducts', array(
			'local' => 'products_id',
			'foreign' => 'products_id'
		));
	}

	public function setTableDefinition(){
		$this->setTableName('orders_products');
		$this->hasColumn('orders_products_id', 'integer', 4, array(
		'type' => 'integer',
		'length' => 4,
		'unsigned' => 0,
		'primary' => true,
		'autoincrement' => true,
		));
		$this->hasColumn('orders_id', 'integer', 4, array(
		'type' => 'integer',
		'length' => 4,
		'unsigned' => 0,
		'primary' => false,
		'default' => '0',
		'notnull' => true,
		'autoincrement' => false,
		));
		$this->hasColumn('products_id', 'integer', 4, array(
		'type' => 'integer',
		'length' => 4,
		'unsigned' => 0,
		'primary' => false,
		'default' => '0',
		'notnull' => true,
		'autoincrement' => false,
		));
		$this->hasColumn('products_model', 'string', 32, array(
		'type' => 'string',
		'length' => 32,
		'fixed' => false,
		'primary' => false,
		'notnull' => false,
		'autoincrement' => false,
		));
		$this->hasColumn('products_name', 'string', 64, array(
		'type' => 'string',
		'length' => 64,
		'fixed' => false,
		'primary' => false,
		'default' => '',
		'notnull' => true,
		'autoincrement' => false,
		));
		$this->hasColumn('products_price', 'decimal', 15, array(
		'type' => 'decimal',
		'length' => 15,
		'unsigned' => 0,
		'primary' => false,
		'default' => '0.0000',
		'notnull' => true,
		'autoincrement' => false,
		'scale' => 4,
		));
		$this->hasColumn('final_price', 'decimal', 15, array(
		'type' => 'decimal',
		'length' => 15,
		'unsigned' => 0,
		'primary' => false,
		'default' => '0.0000',
		'notnull' => true,
		'autoincrement' => false,
		'scale' => 4,
		));
		$this->hasColumn('products_tax', 'decimal', 7, array(
		'type' => 'decimal',
		'length' => 7,
		'unsigned' => 0,
		'primary' => false,
		'default' => '0.0000',
		'notnull' => true,
		'autoincrement' => false,
		'scale' => 4,
		));
		$this->hasColumn('products_quantity', 'integer', 4, array(
		'type' => 'integer',
		'length' => 4,
		'unsigned' => 0,
		'primary' => false,
		'default' => '0',
		'notnull' => true,
		'autoincrement' => false,
		));
		$this->hasColumn('products_date_available', 'timestamp', null, array(
		'type' => 'timestamp',
		'primary' => false,
		'default' => '0000-00-00 00:00:00',
		'notnull' => true,
		'autoincrement' => false,
		));
		$this->hasColumn('purchase_type', 'string', 12, array(
		'type' => 'string',
		'length' => 12,
		'fixed' => false,
		'primary' => false,
		'notnull' => true,
		'autoincrement' => false,
		));
		$this->hasColumn('barcode_id', 'integer', 4, array(
		'type' => 'integer',
		'length' => 4,
		'unsigned' => 0,
		'primary' => false,
		'notnull' => false,
		'autoincrement' => false,
		));
		$this->hasColumn('quantity_id', 'integer', 4, array(
		'type' => 'integer',
		'length' => 4,
		'unsigned' => 0,
		'primary' => false,
		'notnull' => false,
		'autoincrement' => false,
		));
	}
}