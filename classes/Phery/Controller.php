<?php

class Phery_Controller extends Controller {
	/**
	 * @var Phery
	 */
	public $ajax;
	/**
	 * @var Array
	 */
	public $ajax_config;

	/**
	 * Set per controller AJAX options
	 *
	 * @return array
	 * @see Phery::config()
	 */
	public function ajax()
	{
		return array();
	}

	public function before()
	{
		$this->ajax_config = Kohana::$config->load('Phery')->as_array();

		$this->ajax = Phery::instance();
		View::bind_global('ajax', $this->ajax);

		if (Phery::is_ajax(true))
		{
			$methods = get_class_methods(get_class($this));

			foreach($methods as $method)
			{
				if (preg_match('/^ajax_(?<action>[a-z0-9]+)_(?<function>[a-z0-9\_]+)$/i', $method, $matches))
				{
					if (!strcasecmp($matches['action'], $this->request->action()))
					{
						$this->ajax->set(array(
							$matches['function'] => array($this, $method)
						));
					}
				}
				elseif (preg_match('/^ajax_(?<function>[a-z0-9\_]+)$/i', $method, $matches))
				{
					$this->ajax->set(array(
						$matches['function'] => array($this, $method)
					));
				}
			}
		}

		parent::before();
	}

	public function after()
	{
		parent::after();

		if (Phery::is_ajax(true))
		{
			$this->ajax->config($this->ajax());

			$this->ajax->config(array(
				'exit_allowed' => false,
				'return' => true
			));

			try
			{
				if (($response = $this->ajax->process()) !== false)
				{
					$this
					->response
					->headers(array(
						'Content-Type' => 'application/json'
					))
					->body($response);
				}
			}
			catch (PheryException $exc)
			{
				Kohana::$log->add(Log::ERROR, $exc->getMessage());

				$this
				->response
				->headers(array(
					'Content-Type' => 'application/json'
				))
				->body(PheryResponse::factory());
			}
		}
	}
}
