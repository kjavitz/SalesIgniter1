<?php

/**
 * EmailTemplatesVariables
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6401 2009-09-24 16:12:04Z guilhermeblanco $
 */
class EmailTemplatesVariables extends Doctrine_Record {

	public function setTableDefinition(){
		$this->setTableName('email_templates_variables');

		$this->hasColumn('email_templates_id', 'integer', 4, array(
		'type' => 'integer',
		'length' => 4,
		'unsigned' => 0,
		'primary' => false,
		'autoincrement' => false,
		));
		$this->hasColumn('event_variable', 'string', 32, array(
		'type' => 'string',
		'length' => 32,
		'fixed' => false,
		'primary' => false,
		'default' => '',
		'notnull' => true,
		'autoincrement' => false,
		));
		$this->hasColumn('is_conditional', 'integer', 2, array(
		'type' => 'integer',
		'length' => 2,
		'fixed' => false,
		'primary' => false,
		'default' => '',
		'notnull' => true,
		'autoincrement' => false,
		));
		$this->hasColumn('condition_check', 'string', 32, array(
		'type' => 'string',
		'length' => 4,
		'fixed' => false,
		'primary' => false,
		'default' => '',
		'notnull' => false,
		'autoincrement' => false,
		));
	}
}