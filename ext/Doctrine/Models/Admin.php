<?php

/**
 * Admin
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6401 2009-09-24 16:12:04Z guilhermeblanco $
 */
class Admin extends Doctrine_Record {
	
	public function setUp(){
		$this->hasOne('AdminGroups', array(
			'local' => 'admin_groups_id',
			'foreign' => 'admin_groups_id'
		));
	}
	
	public function preInsert($event){
		$this->admin_created = date('Y-m-d');
	}
	
	public function preUpdate($event){
		$this->admin_modified = date('Y-m-d');
	}

	public function setTableDefinition(){
		$this->setTableName('admin');

		$this->hasColumn('admin_id', 'integer', 4, array(
			'type' => 'integer',
			'length' => 4,
			'unsigned' => 0,
			'primary' => true,
			'autoincrement' => true
		));
		
		$this->hasColumn('admin_groups_id', 'integer', 4, array(
			'type' => 'integer',
			'length' => 4,
			'unsigned' => 0,
			'primary' => false,
			'notnull' => false,
			'autoincrement' => false
		));
		
		$this->hasColumn('admin_firstname', 'string', 32, array(
			'type' => 'string',
			'length' => 32,
			'fixed' => false,
			'primary' => false,
			'default' => '',
			'notnull' => true,
			'autoincrement' => false
		));
		
		$this->hasColumn('admin_lastname', 'string', 32, array(
			'type' => 'string',
			'length' => 32,
			'fixed' => false,
			'primary' => false,
			'notnull' => false,
			'autoincrement' => false
		));
		
		$this->hasColumn('admin_email_address', 'string', 96, array(
			'type' => 'string',
			'length' => 96,
			'fixed' => false,
			'primary' => false,
			'default' => '',
			'notnull' => true,
			'autoincrement' => false
		));
		
		$this->hasColumn('admin_password', 'string', 40, array(
			'type' => 'string',
			'length' => 40,
			'fixed' => false,
			'primary' => false,
			'default' => '',
			'notnull' => true,
			'autoincrement' => false
		));


		$this->hasColumn('admin_override_password', 'string', 40, array(
			'type' => 'string',
			'length' => 40,
			'fixed' => false,
			'primary' => false,
			'default' => '',
			'notnull' => true,
			'autoincrement' => false
		));
		
		$this->hasColumn('admin_created', 'timestamp', null, array(
			'type' => 'timestamp',
			'primary' => false,
			'notnull' => false,
			'autoincrement' => false
		));
		
		$this->hasColumn('admin_modified', 'timestamp', null, array(
			'type' => 'timestamp',
			'primary' => false,
			'default' => '0000-00-00 00:00:00',
			'notnull' => true,
			'autoincrement' => false
		));
		
		$this->hasColumn('admin_logdate', 'timestamp', null, array(
			'type' => 'timestamp',
			'primary' => false,
			'notnull' => false,
			'autoincrement' => false
		));
		
		$this->hasColumn('admin_lognum', 'integer', 4, array(
			'type' => 'integer',
			'length' => 4,
			'unsigned' => 0,
			'primary' => false,
			'default' => '0',
			'notnull' => true,
			'autoincrement' => false
		));

		$this->hasColumn('admin_favs_id', 'integer', 4, array(
				'type' => 'integer',
				'length' => 4,
				'unsigned' => 0,
				'primary' => false,
				'default' => '0',
				'notnull' => true,
				'autoincrement' => false
		));

		$this->hasColumn('admin_simple_admin', 'integer', 1, array(
				'type' => 'integer',
				'length' => 1,
				'unsigned' => 0,
				'primary' => false,
				'default' => '0',
				'notnull' => true,
				'autoincrement' => false
		));

		$this->hasColumn('config_home', 'string', null, array(
			'type' => 'string',
			'length' => null,
			'fixed' => false,
			'primary' => false,
			'default' => '',
			'notnull' => true,
			'autoincrement' => false
		));
		$this->hasColumn('favorites_links', 'string', null, array(
			'type' => 'string',
			'length' => null,
			'fixed' => false,
			'primary' => false,
			'default' => '',
			'notnull' => true,
			'autoincrement' => false
		));
		$this->hasColumn('favorites_names', 'string', null, array(
			'type' => 'string',
			'length' => null,
			'fixed' => false,
			'primary' => false,
			'default' => '',
			'notnull' => true,
			'autoincrement' => false
		));
	}
}