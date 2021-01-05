<?php

spl_autoload_register("classLoad");
function classLoad($class) {
  if(file_exists(__DIR__ . "/classes/{$class}.php")) {
    require_once __DIR__ . "/classes/{$class}.php";
  }
  else if(file_exists(__DIR__ . "/Controllers/{$class}.php")) {
    require_once __DIR__ . "/Controllers/{$class}.php";
  }
}
require_once('Routes.php');

?>