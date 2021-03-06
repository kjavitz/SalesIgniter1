<?php

/**
 * Currencies
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6401 2009-09-24 16:12:04Z guilhermeblanco $
 */
class CurrenciesTable extends Doctrine_Record {
	
	public function setTableDefinition(){
		$this->setTableName('currencies');
		
		$this->hasColumn('currencies_id', 'integer', 4, array(
		'type' => 'integer',
		'length' => 4,
		'unsigned' => 0,
		'primary' => true,
		'autoincrement' => true,
		));
		$this->hasColumn('title', 'string', 32, array(
		'type' => 'string',
		'length' => 32,
		'fixed' => false,
		'primary' => false,
		'default' => '',
		'notnull' => true,
		'autoincrement' => false,
		));
		$this->hasColumn('code', 'string', 3, array(
		'type' => 'string',
		'length' => 3,
		'fixed' => true,
		'primary' => false,
		'default' => '',
		'notnull' => true,
		'autoincrement' => false,
		));
		$this->hasColumn('symbol_left', 'string', 12, array(
		'type' => 'string',
		'length' => 12,
		'fixed' => false,
		'primary' => false,
		'notnull' => false,
		'autoincrement' => false,
		));
		$this->hasColumn('symbol_right', 'string', 12, array(
		'type' => 'string',
		'length' => 12,
		'fixed' => false,
		'primary' => false,
		'notnull' => false,
		'autoincrement' => false,
		));
		$this->hasColumn('decimal_point', 'string', 1, array(
		'type' => 'string',
		'length' => 1,
		'fixed' => true,
		'primary' => false,
		'notnull' => false,
		'autoincrement' => false,
		));
		$this->hasColumn('thousands_point', 'string', 1, array(
		'type' => 'string',
		'length' => 1,
		'fixed' => true,
		'primary' => false,
		'notnull' => false,
		'autoincrement' => false,
		));
		$this->hasColumn('decimal_places', 'string', 1, array(
		'type' => 'string',
		'length' => 1,
		'fixed' => true,
		'primary' => false,
		'notnull' => false,
		'autoincrement' => false,
		));
		$this->hasColumn('value', 'float', 13, array(
		'type' => 'float',
		'length' => 13,
		'unsigned' => 0,
		'primary' => false,
		'notnull' => false,
		'autoincrement' => false,
		));
		$this->hasColumn('last_updated', 'timestamp', null, array(
		'type' => 'timestamp',
		'primary' => false,
		'notnull' => false,
		'autoincrement' => false,
		));
	}
}