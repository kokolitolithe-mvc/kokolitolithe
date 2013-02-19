<?php
class Element_Week extends Element_Textbox {
    protected $attributes = array(
        "type" => "week",
        "pattern" => "\d{4}-W\d{2}"
    );

    public function __construct($label, $name, array $properties = null) {
        $this->attributes["placeholder"] = "YYYY-Www (e.g. " . date("Y-\WW") . ")";
        $this->attributes["title"] = $this->attributes["placeholder"];

        parent::__construct($label, $name, $properties);
    }

    public function render() {
        $this->validation[] = new Validation_RegExp("/" . $this->attributes["pattern"] . "/", "Error: The %element% field must match the following date format: " . $this->attributes["title"]);
        parent::render();
    }
}
