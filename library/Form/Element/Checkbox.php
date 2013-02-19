<?php
class Element_Checkbox extends OptionElement {
	protected $attributes = array("type" => "checkbox");
	protected $inline;
	protected $maxheight;

	public function jQueryDocumentReady() {
        if(!empty($this->maxheight) && is_numeric($this->maxheight)) {
            echo <<<JS
var checkboxes = jQuery("#{$this->attributes["id"]}");
if(checkboxes.outerHeight() > {$this->maxheight}) {
    checkboxes.css({
        "height": "{$this->maxheight}px",
        "overflow": "auto",
        "overflow-x": "hidden"
    });
}
JS;
        }
    }

	public function render() { 
		if(isset($this->attributes["value"])) {
			if(!is_array($this->attributes["value"]))
				$this->attributes["value"] = array($this->attributes["value"]);
		}
		else
			$this->attributes["value"] = array();

		if(substr($this->attributes["name"], -2) != "[]")
			$this->attributes["name"] .= "[]";

		$labelClass = $this->attributes["type"];
		if(!empty($this->inline))
			$labelClass .= " inline";

		$count = 0;
		foreach($this->options as $value => $text) {
			$value = $this->getOptionValue($value);
			if(!empty($this->inline) && $count > 0)
				echo ' ';
			echo '<label class="', $labelClass, '"><input id="', $this->attributes["id"], '-', $count, '"', $this->getAttributes(array("id", "value", "checked")), ' value="', $this->filter($value), '"';
			if(in_array($value, $this->attributes["value"]))
				echo ' checked="checked"';
			echo '/>', $text, '</label>';
			++$count;
		}	
	}
}
