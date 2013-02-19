<?php
class Element_Month extends Element_Textbox {
    protected $attributes = array(
        "type" => "month",
        "pattern" => "\d{4}-\d{2}"
    );

    public function __construct($label, $name, array $properties = null) {
        $this->attributes["placeholder"] = "YYYY-MM (e.g. " . date("Y-m") . ")";
        $this->attributes["title"] = $this->attributes["placeholder"];

        parent::__construct($label, $name, $properties);
    }

    public function render() {
        $this->validation[] = new Validation_RegExp("/" . $this->attributes["pattern"] . "/", "Error: The %element% field must match the following date format: " . $this->attributes["title"]);
        parent::render();
    }
}
