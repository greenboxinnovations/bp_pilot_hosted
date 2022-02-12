<?php

spl_autoload_register('myAutoloader');

function myAutoloader($className)
{
    $path = 'classes/';
    $className = strtolower($className);
    include $path.$className.'.class.php';
}


if(isset($_GET['url']))
{
	$url = $_GET['url'];
    $router = new Router($url);	
}

?>