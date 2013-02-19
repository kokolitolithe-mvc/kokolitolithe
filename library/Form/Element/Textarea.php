<?php
class Element_Textarea extends Element {
	protected $attributes = array("rows" => "5");

	public function render() {
        echo "<textarea", $this->getAttributes("value"), ">";
        if(!empty($this->attributes["value"]))
            echo $this->attributes["value"];
        echo "</textarea>";
    }
}
