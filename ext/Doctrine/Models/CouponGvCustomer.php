<?php

/**
 * CouponGvCustomer
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6401 2009-09-24 16:12:04Z guilhermeblanco $
 */
class CouponGvCustomer extends Doctrine_Record {
	
	public function setTableDefinition(){
		$this->setTableName('coupon_gv_customer');
		
		$this->hasColumn('customer_id', 'integer', 4, array(
			'type' => 'integer',
			'length' => 4,
			'unsigned' => 0,
			'primary' => true,
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
	}
}