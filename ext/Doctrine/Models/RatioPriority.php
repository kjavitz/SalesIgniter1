<?php

/**
 * RatioPriority
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6401 2009-09-24 16:12:04Z guilhermeblanco $
 */
class RatioPriority extends Doctrine_Record {
	
	public function setTableDefinition(){
		$this->setTableName('ratio_priority');
		
		$this->hasColumn('id', 'integer', 4, array(
		'type' => 'integer',
		'length' => 4,
		'unsigned' => 0,
		'primary' => true,
		'autoincrement' => true,
		));
		$this->hasColumn('priority', 'integer', 1, array(
		'type' => 'integer',
		'length' => 1,
		'unsigned' => 0,
		'primary' => false,
		'default' => '1',
		'notnull' => true,
		'autoincrement' => false,
		));
		$this->hasColumn('ratio', 'decimal', 3, array(
		'type' => 'decimal',
		'length' => 3,
		'unsigned' => 0,
		'primary' => false,
		'default' => '0.00',
		'notnull' => true,
		'autoincrement' => false,
		'scale' => 2,
		));
	}
}