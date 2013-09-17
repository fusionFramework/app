<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller_Fusion {

	public function action_index()
	{
		$this->_tpl = new View_Welcome;
	}

} // End Welcome
