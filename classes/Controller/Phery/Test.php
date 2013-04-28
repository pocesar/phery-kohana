<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Phery_Test extends Phery_Controller {

	function ajax_remote()
	{
		$r = new PheryResponse;
		return $r->alert('holy cow');
	}

	function ajax_test_remote()
	{
		$r = new PheryResponse;
		return $r->alert('holy mother');
	}

	function action_test()
	{
		echo '<html>';
		echo '<head>';
		echo $this->ajax->csrf();
		echo '</head><body>';
		echo html::script('javascripts/jquery.js');
		echo html::script(Route::get('phery.js')->uri());
		echo Phery::link_to('link', 'remote');
		echo '</body></html>';
	}

	function action_index()
	{
		echo '<html>';
		echo '<head>';
		echo $this->ajax->csrf();
		echo '</head><body>';
		echo html::script('javascripts/jquery.js');
		echo html::script(Route::get('phery.js')->uri());
		echo Phery::link_to('link', 'remote');
		echo '</body></html>';
	}
}
