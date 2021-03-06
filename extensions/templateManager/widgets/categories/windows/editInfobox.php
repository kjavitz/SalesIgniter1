<?php
$lID = (int)Session::get('languages_id');

$widgetIdHtml = htmlBase::newElement('input')
->setName('widgetId')
->setValue(isset($WidgetSettings->widgetId)?$WidgetSettings->widgetId:'');

$checkedshowSubcategory = '';
if (isset($WidgetSettings->showSubcategory) && $WidgetSettings->showSubcategory == 'showSubcategory'){
	$checkedshowSubcategory = 'checked="checked"';
}

$checkedshowAlways = '';
if (isset($WidgetSettings->showAlways) && $WidgetSettings->showAlways == 'showAlways'){
	$checkedshowAlways = 'checked="checked"';
}

function getCategoryTree($parentId, $namePrefix = '', &$categoriesTree){
	global $lID, $allGetParams, $cInfo;
	$Qcategories = Doctrine_Query::create()
			->select('c.*, cd.categories_name')
			->from('Categories c')
			->leftJoin('c.CategoriesDescription cd')
			->where('cd.language_id = ?', $lID)
			->andWhere('c.parent_id = ?', $parentId)
			->orderBy('c.sort_order, cd.categories_name');

	EventManager::notify('CategoryListingQueryBeforeExecute', &$Qcategories);

	$Result = $Qcategories->execute();
	if ($Result->count() > 0){
		foreach($Result->toArray(true) as $Category){
			if ($Category['parent_id'] > 0){
				//$namePrefix .= '&nbsp;';
			}

			$categoriesTree[] = array(
				'categoryId'           => $Category['categories_id'],
				'categoryName'         => $namePrefix . $Category['CategoriesDescription'][Session::get('languages_id')]['categories_name'],
			);

			getCategoryTree($Category['categories_id'], '&nbsp;&nbsp;&nbsp;' . $namePrefix, &$categoriesTree);
		}
	}
}

$categoryTreeList[] = array(
	'categoryId'           => '0',
	'categoryName'         => 'Root'
);
getCategoryTree(0,'',&$categoryTreeList);

$selectedCategory = isset($WidgetSettings->selected_category)?$WidgetSettings->selected_category:'';
$defaultExpandedCategory = isset($WidgetSettings->default_expanded_category)?$WidgetSettings->default_expanded_category:'';

$categoryTree = htmlBase::newElement('selectbox')
		->setName('selected_category')
		->setId('selectedCategory')
		->setLabel('Parent Category')//sysLanguage::get('TEXT_SELECT_PARENT_CATEGORY')
		->setLabelPosition('before');

$categoryTreeDefaultExpanded = htmlBase::newElement('selectbox')
	->setName('default_expanded_category')
	->setId('defaultExpandedCategory')
	->setLabel('Expand this category when not on a category page')//sysLanguage::get('TEXT_SELECT_PARENT_CATEGORY')
	->setLabelPosition('before');

$categoryTree->addOption('', sysLanguage::get('INFOBOX_CATEGORIES_SELECT_PARENT_CATEGORY_OPTION'));
$categoryTreeDefaultExpanded->addOption('', sysLanguage::get('INFOBOX_CATEGORIES_SELECT_PARENT_CATEGORY_OPTION'));

foreach($categoryTreeList as $category){
	$categoryTree->addOption($category['categoryId'], $category['categoryName']);
	$categoryTreeDefaultExpanded->addOption($category['categoryId'], $category['categoryName']);
}
if(isset($selectedCategory)){
	$categoryTree->selectOptionByValue($selectedCategory);
}

if(isset($defaultExpandedCategory)){
	$categoryTreeDefaultExpanded->selectOptionByValue($defaultExpandedCategory);
}

$WidgetSettingsTable->addBodyRow(array(
		'columns' => array(
			array('text' => 'Widget ID'),
			array('text' => $widgetIdHtml->draw())
		)
	));

/*$WidgetSettingsTable->addBodyRow(array(
		'columns' => array(
			array('text' => 'Show only subcategories'),
			array('text' => '<input type="checkbox" name="showSubcategory" value="showSubcategory" '.$checkedshowSubcategory.'>')
		)
	));

$WidgetSettingsTable->addBodyRow(array(
		'columns' => array(
			array('text' => 'Show always'),
			array('text' => '<input type="checkbox" name="checkedshowAlways" value="checkedshowAlways" '.$checkedshowAlways.'>')
		)
	));
*/
$WidgetSettingsTable->addBodyRow(array(
	'columns' => array(
		array('text' => 'Show current category\'s sub categories'),
		array('text' => '<input type="checkbox" name="showCurrentSubcategory" value="showCurrentSubcategory" '.$checkedshowCurrentSubcategory.'>')
	)
));
$WidgetSettingsTable->addBodyRow(array(
                                      'columns' => array(
	                                      array('colspan' => 2, 'text' => $categoryTree->draw())
                                      )
                                 ));
$WidgetSettingsTable->addBodyRow(array(
                                      'columns' => array(
	                                      array('colspan' => 2, 'text' => $categoryTreeDefaultExpanded->draw())
                                      )
                                 ));
$checkedCats = array();
if(isset($WidgetSettings->excludedCategories)){
	$checkedCats = explode(';',$WidgetSettings->excludedCategories);
}

$categoriesList = tep_get_category_tree_list('0', $checkedCats);

$WidgetSettingsTable->addBodyRow(array(
		'columns' => array(
			array('text' => 'Exclude from infobox'),
			array('text' => $categoriesList)
		)
	));

?>