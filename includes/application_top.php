<?php
spl_autoload_register(function ($class_name) {
	if(file_exists(DIR_INCLUDES.strtolower($class_name).'.php'))
    include DIR_INCLUDES.strtolower($class_name).'.php';
});