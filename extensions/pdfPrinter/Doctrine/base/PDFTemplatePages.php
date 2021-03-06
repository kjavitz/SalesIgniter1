<?php

/**
 * AdminApplicationsPermissions
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6401 2009-09-24 16:12:04Z guilhermeblanco $
 */
class PDFTemplatePages extends Doctrine_Record {
	
	public function setTableDefinition(){
		$this->setTableName('pdf_template_pages');
		
		$this->hasColumn('id', 'integer', 4, array(
		'type' => 'integer',
		'length' => 4,
		'unsigned' => 0,
		'primary' => true,
		'autoincrement' => true,
		));
		$this->hasColumn('application', 'string', 64, array(
		'type' => 'string',
		'length' => 64,
		'primary' => false,
		'default' => '',
		'notnull' => true,
		));
		$this->hasColumn('page', 'string', 64, array(
		'type' => 'string',
		'length' => 64,
		'primary' => false,
		'default' => '',
		'notnull' => true,
		));
		$this->hasColumn('extension', 'string', 64, array(
		'type' => 'string',
		'length' => 64,
		'primary' => false,
		'default' => '',
		'notnull' => false,
		));
		$this->hasColumn('layout_id', 'string', null, array(
		'type' => 'string',
		'length' => null,
		'primary' => false,
		'notnull' => false,
		));
	}
}