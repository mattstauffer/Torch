<?php

namespace Acme;

class Controller
{
	private $database;
	private $template;

	public function __construct(\Acme\Database $database, \Acme\Template $template)
	{
		$this->database = $database;
		$this->template = $template;
	}

	public function home()
	{
		echo $this->template->render('home');
	}
}
