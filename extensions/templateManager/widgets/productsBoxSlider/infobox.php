<?php
/*
	I.T. Web Experts, Rental Store v2
	http://www.itwebexperts.com

	Copyright (c) 2009 I.T. Web Experts

	This script and it's source is not redistributable
*/

class InfoBoxProductsBoxSlider extends InfoBoxAbstract {

	public function __construct(){
		global $App;
		$this->init('productsBoxSlider');
		$this->firstAdded = false;
		$this->buildStylesheetMultiple = false;
		$this->buildJavascriptMultiple = true;
	}

	public function getAllSubCategories($categoriesId, &$categoriesArray){
		$Categories = Doctrine_Query::create()
			->select('categories_id')
			->from('Categories')
			->where('parent_id = ?', $categoriesId)
			->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		if (!empty($Categories)) {
			foreach($Categories as $catInfo){
				$categoriesArray[] = $catInfo['categories_id'];
				$this->getAllSubCategories($catInfo['categories_id'],$categoriesArray);
			}
		}
	}

	public function buildJavascript() {
		$boxWidgetProperties = $this->getWidgetProperties();
		$id = $boxWidgetProperties->id;
		$displaySlideQty = $boxWidgetProperties->displayQty;
		$moveSlideQty = $boxWidgetProperties->moveQty;
		$speed = $boxWidgetProperties->speed;
		$duration = $boxWidgetProperties->duration;
		$easing = $boxWidgetProperties->easing;
		$javascript = '';
		ob_start();

		readfile(sysConfig::getDirFsCatalog().'ext/jQuery/ui/jquery.effects.core.js');
		readfile(sysConfig::getDirFsCatalog().'ext/jQuery/ui/jquery.ui.mouse.js');
		readfile(sysConfig::getDirFsCatalog().'ext/jQuery/ui/jquery.effects.fade.js');
		readfile(sysConfig::getDirFsCatalog().'ext/jQuery/ui/jquery.ui.position.js');
		readfile(sysConfig::getDirFsCatalog().'ext/jQuery/ui/jquery.ui.draggable.js');
		readfile(sysConfig::getDirFsCatalog().'ext/jQuery/ui/jquery.ui.sortable.js');
		readfile(sysConfig::getDirFsCatalog().'ext/jQuery/ui/jquery.ui.resizable.js');
		readfile(sysConfig::getDirFsCatalog().'ext/jQuery/external/jquery.bxSlider/jquery.bxSlider.min.js');
		readfile(sysConfig::getDirFsCatalog().'ext/jQuery/external/jquery.bxSlider/jquery.easing.1.3.js');
		?>
	$('#<?php echo $id;?>').bxSlider({
	displaySlideQty: <?php echo $displaySlideQty;?>,
	moveSlideQty: <?php echo $moveSlideQty;?>,
	speed:<?php echo $speed;?>,
	easing:'<?php echo $easing;?>',
	prevSelector:'#prev<?php echo $id;?>',
	nextSelector:'#next<?php echo $id;?>',
	pause:<?php echo $duration;?>
	});


	<?php
 		$javascriptSource = ob_get_contents();
		ob_end_clean();

		$javascript .= '/* BlogSlider --BEGIN-- */' . "\n" .
			$javascriptSource .
			'/* BlogSlider --END-- */' . "\n";

		return $javascript;
	}

	public function show(){
		$boxWidgetProperties = $this->getWidgetProperties();

		//$this->setBoxId($boxWidgetProperties->id);
		$id = $boxWidgetProperties->id;
		$cInfo = $boxWidgetProperties->config;
		$Results = $this->getQueryResults($cInfo->query, $cInfo->query_limit, (isset($cInfo->selected_category)?$cInfo->selected_category:''));
		
		if (!empty($Results)) {
			$selectedCatName = '';
			if(isset($cInfo->selected_category)){
				$Qcat = Doctrine_Query::create()
				->from('Categories c')
				->leftJoin('c.CategoriesDescription cd')
				->where('cd.language_id = ?', Session::get('languages_id'))
				->where('categories_id = ?', $cInfo->selected_category)
				->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
				if(count($Qcat) > 0){
					$selectedCatName = $Qcat[0]['CategoriesDescription'][0]['categories_name'];
				}
			}

			$ProductsList = $this->buildList($Results, $cInfo, $selectedCatName, $id);
			$nextArrow = htmlBase::newElement('div')
				->attr('id','next'.$id);
			$prevArrow = htmlBase::newElement('div')
				->attr('id','prev'.$id);
			$this->setBoxContent($ProductsList->draw().$prevArrow->draw().$nextArrow->draw());
			return $this->draw();
		} else {
			return '';
		}
	}
	
	public function buildList($products, $cInfo, $selectedCategoryName = '', $id=''){
		$List = htmlBase::newElement('ul')->addClass('productsBoxList');
		if(!empty($id)){
			$List->attr('id',$id);
		}
		foreach($products as $pInfo){
			$ListItemImage = htmlBase::newElement('image')
			->setSource(sysConfig::getDirWsCatalog().'images/' . $pInfo['products_image'])
			->setWidth($cInfo->block_width)
			->setHeight($cInfo->block_height)
			->thumbnailImage(true);
			
			$productLink = itw_app_link('products_id=' . $pInfo['products_id'], 'product', 'info');
			
			if ($cInfo->reflect_blocks === true){
				$ListItemImage->addClass('productsBoxReflectImage');
			}
			
			$Block = htmlBase::newElement('div')
			->addClass('productsBoxBlock');

			$ListItemNameLink = htmlBase::newElement('a')
				->setHref($productLink)
				->html($pInfo['ProductsDescription'][0]['products_name']);
			$NameBlock = htmlBase::newElement('div')
				->addClass('productsBoxBlockName')
				->append($ListItemNameLink);

			$Block->append($NameBlock);

			$ListItemImageLink = htmlBase::newElement('a')
			->setHref($productLink)
			->append($ListItemImage);
			
			$ImageBlock = htmlBase::newElement('div')
			->addClass('productsBoxBlockImage')
			->append($ListItemImageLink);
			
			$Block->append($ImageBlock);
			
			if ($cInfo->reflect_blocks === false){

				if($selectedCategoryName != ''){
					$ListItemCategoryName= htmlBase::newElement('span')
					->html('Featured '.$selectedCategoryName);
				}

				if(isset($ListItemCategoryName)){
					$CategoryNameBlock = htmlBase::newElement('div')
					->addClass('productsBoxBlockCategoryName')
					->append($ListItemCategoryName);
					$Block->append($CategoryNameBlock);
				}
				


			}
			
			$ListItem = htmlBase::newElement('li')
			->css(array(
				'height' => $cInfo->block_height . 'px',
				'width' => $cInfo->block_width . 'px'
			))
			->append($Block);
			
			$List->append($ListItem);
		}

		
		$ListContainer = htmlBase::newElement('div')
		->addClass('productsBoxListContainer')
		->append($List);
		return $ListContainer;
	}
	
	public function getQueryResults($queryType, $queryLimit, $selectedCategory = 0){
		$Query = Doctrine_Query::create()
		->select('p.products_id, p.products_image, pd.products_name')
		->from('Products p')
		->leftJoin('p.ProductsDescription pd')
		->where('p.products_status = ?', '1')
		->andWhere('pd.language_id = ?', Session::get('languages_id'));

		
		if ($queryLimit > 0){
			$Query->limit($queryLimit);
		}
		
		switch($queryType){
			case 'best_sellers':
				$Query->andWhere('p.products_ordered > ?', '0')
				->orderBy('p.products_ordered desc, pd.products_name asc');
				
				EventManager::notify('ScrollerBestSellersQueryBeforeExecute', &$Query);
				break;
			case 'featured':
				$Query->andWhere('p.products_featured = ?', '1');
				
				EventManager::notify('ScrollerFeaturedQueryBeforeExecute', &$Query);
				break;
			case 'new_products':
				$Query->orderBy('p.products_date_added desc, pd.products_name asc');
				
				EventManager::notify('ScrollerNewProductsQueryBeforeExecute', &$Query);
				break;
			case 'top_rentals':
				EventManager::notify('ScrollerTopRentalsQueryBeforeExecute', &$Query);
				break;
			case 'specials':
				EventManager::notify('ScrollerSpecialsQueryBeforeExecute', &$Query);
				break;
			case 'related':
				EventManager::notify('ScrollerRelatedQueryBeforeExecute', &$Query);
				break;
			case 'category_featured':

				$catsArray = array($selectedCategory);
				$this->getAllSubCategories($selectedCategory, $catsArray);

				$Query->andWhere('p.products_featured = ?', '1')
					->leftJoin('p.ProductsToCategories p2c')
				//->leftJoin('p2c.Categories c')
					->andWhere('p2c.categories_id in (' . implode(',',$catsArray) . ')');//, '"' . array(implode(',',$catsArray) . '"'));
			     break;
			case 'category':
				if (Session::exists('current_category_id')){
					$Query->leftJoin('p.ProductsToCategories p2c')
					->leftJoin('p2c.Categories c')
					->andWhere('c.parent_id = ?', Session::get('current_category_id'));
				}
		
				EventManager::notify('ScrollerCategoryQueryBeforeExecute', &$Query);
				break;
		}
		return $Query->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
	}

	public function buildStylesheet(){
		$boxWidgetProperties = $this->getWidgetProperties();
		
		$css = '/* Products Box --BEGIN-- */' . "\n" . 
		'.productsBoxBlock { ' . 
			'text-align:center;' . 
		' }' . "\n" . 
		'.productsBoxBlockImage { ' . 
			'margin-left: auto;' . 
			'margin-right: auto;' . 
		' }' . "\n" . 
		'.productsBoxBlockName { ' . 
			'text-align:center;' . 
		' }' . "\n" . 
		'.productsBoxListContainer { ' . 
			'position:relative;' . 
			'display:inline-block;' . 
			'vertical-align:middle;' . 
			'overflow:hidden;' . 
			'background:transparent;' . 
		' }' . "\n" . 
		'.productsBoxList { ' . 
			'position:relative;' . 
			'list-style:none;' . 
			'display:block;' . 
			'vertical-align:middle;' . 
			'width:auto;' .
			'padding:0;' . 
			'margin:0;' . 
			'background:transparent;' . 
		' }' . "\n" . 
		'.productsBoxList li { ' . 
			'position:relative;' . 
			'display:inline-block;' . 
			'vertical-align:middle;' . 
			'background:transparent;' . 
		' }' . "\n" . 
		'/* Products Box --END-- */' . "\n";
		
		return $css;
	}
}
?>
