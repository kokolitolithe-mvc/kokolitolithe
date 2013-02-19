<?php
class View_Search extends View_Inline {
	protected $class = "form-search";
	
	public function render() {
		$this->form->setClass($this->class);
		parent::render();
    }
}	
