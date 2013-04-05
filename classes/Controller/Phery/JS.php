<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Phery_JS extends Controller {

	function action_index()
	{
		$javascript = file_get_contents(PHERY_JS);

		$this
		->response
		->body($javascript)
		->headers(array(
			'Content-Type' => 'text/javascript;charset=UTF-8',
			'Cache-Control' => 'max-age=29030400, public, must-revalidate'
		));
	}
}
