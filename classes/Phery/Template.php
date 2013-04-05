<?php

class Phery_Template extends Phery_Controller {
	/**
	 * @var  View  page template
	 */
	public $template = 'template';

	/**
	 * @var  boolean  auto render template
	 **/
	public $auto_render = TRUE;

	/**
	 * Loads the template [View] object.
	 */
	public function before()
	{
		parent::before();

		if ($this->auto_render === TRUE)
		{
			// Load the template
			$this->template = View::factory($this->template);
		}
	}

	/**
	 * Assigns the template [View] as the request response.
	 */
	public function after()
	{
		if (Phery::is_ajax(true))
		{
			$this->auto_render = false;
		}

		parent::after();

		if ($this->auto_render === TRUE)
		{
			$this->response->body($this->template->render());
		}
	}

}
