<?php

/**
 * SearchQueries
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6401 2009-09-24 16:12:04Z guilhermeblanco $
 */
class SearchQueries extends Doctrine_Record {

	public function setTableDefinition(){
		$this->setTableName('search_queries');

		$this->hasColumn('search_id', 'integer', 4, array(
		'type' => 'integer',
		'length' => 4,
		'unsigned' => 0,
		'primary' => true,
		'autoincrement' => true,
		));
		$this->hasColumn('search_text', 'string', null, array(
		'type' => 'string',
		'fixed' => false,
		'primary' => false,
		'notnull' => false,
		'autoincrement' => false,
		));
	}
}