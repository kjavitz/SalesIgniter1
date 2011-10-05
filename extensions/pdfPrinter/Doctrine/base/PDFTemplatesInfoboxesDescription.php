<?php

/**
 * CategoriesDescription
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6401 2009-09-24 16:12:04Z guilhermeblanco $
 */
class PDFTemplatesInfoboxesDescription extends Doctrine_Record {

	public function setUp(){
		parent::setUp();
		
		$this->setAttribute(Doctrine_Core::ATTR_COLL_KEY, 'language_id');
	}

	public function setTableDefinition(){
		$this->setTableName('pdf_templates_infoboxes_description');
		
		$this->hasColumn('templates_infoboxes_id', 'integer', 4, array(
			'type'          => 'integer',
			'length'        => 4,
			'unsigned'      => 0,
			'primary'       => false,
			'autoincrement' => false
		));
		
		$this->hasColumn('language_id', 'integer', 4, array(
			'type'          => 'integer',
			'length'        => 4,
			'unsigned'      => 0,
			'primary'       => false,
			'default'       => '1',
			'autoincrement' => false
		));
		
		$this->hasColumn('box_heading', 'string', null, array(
			'type'          => 'string',
			'length'        => null,
			'fixed'         => false,
			'primary'       => false,
			'default'       => '',
			'notnull'       => true,
			'autoincrement' => false
		));
	}
	
	public function newLanguageProcess($fromLangId, $toLangId){
		$Qdescription = Doctrine_Query::create()
		->from('PDFTemplatesInfoboxesDescription')
		->where('language_id = ?', (int) $fromLangId)
		->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		foreach($Qdescription as $Record){
			$toTranslate = array(
				'heading' => $Record['box_heading']
			);
			
			EventManager::notify('PDFTemplatesInfoboxesDescriptionNewLanguageProcessBeforeTranslate', $toTranslate);
			
			$translated = sysLanguage::translateText($toTranslate, (int) $toLangId, (int) $fromLangId);
			
			$newDesc = new PDFTemplatesInfoboxesDescription();
			$newDesc->templates_infoboxes_id = $Record['templates_infoboxes_id'];
			$newDesc->language_id = (int) $toLangId;
			$newDesc->box_heading = $translated['heading'];
			
			EventManager::notify('PDFTemplatesInfoboxesNewLanguageProcessBeforeSave', $newDesc);
			
			$newDesc->save();
		}
	}
	
	public function cleanLanguageProcess($existsId){
		Doctrine_Query::create()
		->delete('PDFTemplatesInfoboxesDescription')
		->whereNotIn('language_id', $existsId)
		->execute();
	}

	public function deleteLanguageProcess($langId){
		Doctrine_Query::create()
		->delete('PDFTemplatesInfoboxesDescription')
		->where('language_id = ?', (int) $langId)
		->execute();
	}
}
