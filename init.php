<?php

$phery = realpath(dirname(__FILE__).'/vendor/').DIRECTORY_SEPARATOR;
require_once $phery.'Phery.php';

define('PHERY_PATH', $phery);
define('PHERY_JS', $phery.'phery.js');

Route::set('phery.js', 'phery.js')
	->defaults(array(
		'directory' => 'Phery',
		'controller' => 'JS',
		'action' => 'index'
	));

if (Kohana::$environment === Kohana::DEVELOPMENT)
{
	Route::set('phery', 'phery(/<controller>(/<action>))')
	->defaults(array(
		'directory' => 'Phery',
		'controller' => 'Test',
		'action' => 'index'
	));
}
