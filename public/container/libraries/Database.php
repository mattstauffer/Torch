<?php

namespace Acme;

class Database
{
	public function select($query)
	{
		return array(
			array('title' => 'Example article title'),
			array('title' => 'Another example article title')
		);
	}
}
