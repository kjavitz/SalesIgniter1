<?php

/**
 * EmailTemplatesDescription
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6401 2009-09-24 16:12:04Z guilhermeblanco $
 */
class EmailTemplatesDescription extends Doctrine_Record {

	public function setUp(){
		$this->setAttribute(Doctrine::ATTR_COLL_KEY, 'language_id');
	}
	
	public function setTableDefinition(){
		$this->setTableName('email_templates_description');

		$this->hasColumn('email_templates_id', 'integer', 4, array(
		'type' => 'integer',
		'length' => 4,
		'unsigned' => 0,
		'primary' => false,
		'autoincrement' => false,
		));
		$this->hasColumn('email_templates_subject', 'string', 255, array(
		'type' => 'string',
		'length' => 255,
		'fixed' => false,
		'primary' => false,
		'default' => '',
		'notnull' => true,
		'autoincrement' => false,
		));
		$this->hasColumn('email_templates_content', 'string', 999, array(
		'type' => 'string',
		'length' => 999,
		'fixed' => false,
		'primary' => false,
		'default' => '',
		'notnull' => true,
		'autoincrement' => false,
		));
		$this->hasColumn('language_id', 'integer', 4, array(
		'type' => 'integer',
		'length' => 4,
		'fixed' => false,
		'primary' => false,
		'default' => '',
		'notnull' => false,
		'autoincrement' => false,
		));
	}
	
	public function cleanLanguageProcess($existsId){
		Doctrine_Query::create()
		->delete('EmailTemplatesDescription')
		->whereNotIn('language_id', $existsId)
		->execute();
	}
/*
	public function newLanguageProcess($fromLangId, $toLangId){
		$Qdescription = Doctrine_Query::create()
		->from('EmailTemplatesDescription')
		->where('language_id = ?', (int) $fromLangId)
		->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		foreach($Qdescription as $Record){
			$toTranslate = array(
				'subject' => $Record['email_templates_subject'],
				'content' => $Record['email_templates_content']
			);

			EventManager::notify('EmailTemplatesDescriptionNewLanguageProcessBeforeTranslate', $toTranslate);

			$translated = sysLanguage::translateText($toTranslate, (int) $toLangId, (int) $fromLangId);

			$newDesc = new EmailTemplatesDescription();
			$newDesc->email_templates_id = $Record['email_templates_id'];
			$newDesc->language_id = (int) $toLangId;
			$newDesc->email_templates_subject = $translated['subject'];
			$newDesc->email_templates_content = $translated['content'];

			EventManager::notify('EmailTemplatesDescriptionNewLanguageProcessBeforeSave', $newDesc);

			$newDesc->save();
		}
	}

	public function deleteLanguageProcess($langId){
		Doctrine_Query::create()
		->delete('EmailTemplatesDescription')
		->where('language_id = ?', (int) $langId)
		->execute();
	}
	*/
}