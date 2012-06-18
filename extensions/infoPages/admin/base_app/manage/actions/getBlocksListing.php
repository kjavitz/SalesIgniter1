<?php
	$Qpages = Doctrine_Query::create()
	->select('p.*, pd.pages_title')
	->from('Pages p')
	->leftJoin('p.PagesDescription pd')
	->where('pd.language_id = ?', Session::get('languages_id'))
	->andWhere('p.page_type = ?', 'block')
	->orderBy('p.sort_order, pd.pages_title');
	
	$tableGrid = htmlBase::newElement('newGrid')
	->usePagination(false)
	->setPageLimit((isset($_GET['limit']) ? (int)$_GET['limit'] : 25))
	->setCurrentPage((isset($_GET['page']) ? (int)$_GET['page'] : 0))
	->setQuery($Qpages);

	$tableGrid->addHeaderRow(array(
		'columns' => array(
			array('text' => sysLanguage::get('TABLE_HEADING_PAGES_KEY')),
			array('text' => sysLanguage::get('TABLE_HEADING_PAGES')),
			array('text' => sysLanguage::get('TABLE_HEADING_STATUS')),
			array('text' => sysLanguage::get('TABLE_HEADING_ACTION'))
		)
	));

	$pages = $tableGrid->getResults();
	if ($pages){
		$editButton = htmlBase::newElement('button')
        ->addClass('editButton ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only')
        ->setText('Edit')
		->css(array(
			'font-size' => '.8em'
		));
		
		$deleteButton = htmlBase::newElement('button')
		->addClass('deleteButton ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only')
		->usePreset('delete')
		->css(array(
			'font-size' => '.8em'
		));
		
		foreach($pages as $page){
			$pageId = $page['pages_id'];
			
			$statusIcon = htmlBase::newElement('icon');
			if ($page['status'] == '1') {
				$statusIcon->setType('circleCheck')->setTooltip('Click to disable')
				->setHref(itw_app_link('appExt=infoPages&action=setflag&flag=0&pID=' . $pageId));
			} else {
				$statusIcon->setType('circleClose')->setTooltip('Click to enable')
				->setHref(itw_app_link('appExt=infoPages&action=setflag&flag=1&pID=' . $pageId));
			}

			$editButton->setHref(itw_app_link('appExt=infoPages&pID=' . $pageId, 'manage', 'newContentBlock'));
			$deleteButton->attr('data-page_id', $pageId);
			
			$tableGrid->addBodyRow(array(
				'columns' => array(
					array('text' => $page['page_key']),
					array('text' => $page['PagesDescription'][Session::get('languages_id')]['pages_title']),
					array('text' => $statusIcon->draw(), 'align' => 'center'),
					array('text' => $editButton->draw() . $deleteButton->draw(), 'align' => 'right')
				)
			));
		}
	}
	EventManager::attachActionResponse($tableGrid->draw(), 'html');
?>