<?php
/**
 * Radio Element Widget Class
 * @package Html
 */
class htmlWidget_radio implements htmlWidgetPlugin {
	protected $inputElement;
	
	public function __construct(){
		$this->inputElement = htmlBase::newElement('input')->setType('radio');
		$this->isGroup = false;
	}
	
	public function __call($function, $args){
		$return = call_user_func_array(array($this->inputElement, $function), $args);
		if (!is_object($return)){
			return $return;
		}
		return $this;
	}
	
	/* Required Functions From Interface: htmlElementPlugin --BEGIN-- */
	public function startChain(){
		return $this;
	}
	
	public function setId($val){
		if ($this->isGroup === true) die('Error: This is a group of radio inputs, please use data array to set id');
		
		$this->inputElement->setId($val);
		return $this;
	}
	
	public function setName($val){
		if ($this->isGroup === true){
			foreach($this->groupElements as $button){
				$button->setName($val);
			}
		}else{
			$this->inputElement->setName($val);
		}
		return $this;
	}
	
	public function setValue($val){
		if ($this->isGroup === true) die('Error: This is a group of radio inputs, please use data array to set values');
		
		$this->inputElement->val($val);
		return $this;
	}
	
	public function draw(){
		$html = '';
		if ($this->isGroup === true){
			$htmlOutput = array();
			foreach($this->groupElements as $button){
				$htmlOutput[] = $button->draw();
			}
			$html .= implode($this->groupSeparator, $htmlOutput);
		}else{
			$html = $this->inputElement->draw();
		}
		
		return $html;
	}
	/* Required Functions From Interface: htmlElementPlugin --END-- */
	
	public function setGroupSeparator($html){
		if ($this->isGroup === true){
			$this->groupSeparator = $html;
		}
		return $this;
	}
	
	public function addGroup(array $data){
		$this->isGroup = true;
		$this->groupSeparator = (isset($data['separator']) ? $data['separator'] : '&nbsp;');
		
		$this->groupElements = array();
		foreach($data['data'] as $bInfo){
			$button = htmlBase::newElement('radio')
			->setName($data['name'])
			->setValue($bInfo['value'])
			->setLabel($bInfo['label']);
			
			if (isset($data['addCls'])){
				$button->addClass($data['addCls']);
			}
			
			if (isset($bInfo['labelPosition'])){
				$button->setLabelPosition($bInfo['labelPosition']);
			}
			
			if (isset($bInfo['labelSeparator'])){
				$button->setLabelSeparator($bInfo['labelSeparator']);
			}
			
			if (isset($bInfo['disabled']) && $bInfo['disabled'] === true){
				$button->attr('disabled', 'disabled');
			}
			
			if (isset($bInfo['id'])){
				$button->setId($bInfo['id']);
			}else{
				$number = rand(rand(1, 500), rand(505, 9000))*rand(1, 100)/rand(1, 15);
				$button->setId(strtolower($data['name'] . '_' . str_replace(array('-', ' '), '_', $bInfo['value']) . '_' . round($number)));
			}
			
			if (isset($data['checked']) && $data['checked'] == $bInfo['value']){
				$button->setChecked(true);
			}else{
				$button->setChecked(false);
			}
			
			$this->groupElements[] = $button;
		}
		return $this;
	}

	public function setChecked($val){
		if ($this->isGroup === true){
			foreach($this->groupElements as $button){
				$button->setChecked($button->val() == $val);
			}
		}else{
			if ($val === true){
				$this->inputElement->setChecked(true);
			}else{
				$this->inputElement->setChecked(false);
			}
		}
		
		return $this;
	}
	
	public function isChecked(){
		return $this->inputElement->hasAttr('checked');
	}
}
?>