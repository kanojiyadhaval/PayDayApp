<?php

$GLOBALS['DS'] = DIRECTORY_SEPARATOR;
// autoload class
spl_autoload_register(function ($className) {
    $dir = __DIR__;
    $className = str_replace('\\', $GLOBALS['DS'], $className);
    $file = "{$dir}{$GLOBALS['DS']}{$className}.php";
    if (is_readable($file)) require_once $file;
});

$obj = new \CliSalarydates\Cli\ExportSalaryFile();
$obj->_exportData();

?>