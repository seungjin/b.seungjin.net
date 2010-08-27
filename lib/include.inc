<?php

foreach(glob("./*.php") as $class_filename) {
     require_once($class_filename);
}

?>
