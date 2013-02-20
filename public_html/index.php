<?php

define("PATH_PUBLIC", dirname(__FILE__));
define("BASEPATH", realpath(dirname(__FILE__)."/../"));
set_include_path(implode(PATH_SEPARATOR, array(
    realpath('../library'),realpath('../library/Form'),realpath('../'),get_include_path(),
)));
// require("../run.php");
require 'Application.php';

Application::getInstance()->run();
