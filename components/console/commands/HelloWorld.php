<?php

use Illuminate\Console\Command;

class HelloWorld extends Command {
	protected $signature = 'hello:world';
	protected $description = 'This is a simple hello world';

	public function handle() {
		$this->comment('Hello World');
	}
}
