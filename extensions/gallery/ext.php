<?php
/*
	Related Products Version 1

	I.T. Web Experts, Rental Store v2
	http://www.itwebexperts.com

	Copyright (c) 2009 I.T. Web Experts

	This script and it's source is not redistributable
*/

class Extension_gallery extends ExtensionBase {

	public function __construct(){
		parent::__construct('gallery');
	}

	public function init(){
		if ($this->isEnabled() === false) return;
	}


}
?>