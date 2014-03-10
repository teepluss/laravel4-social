<?php namespace Teepluss\Social\Adapters;

use Facebook as BaseFacebook;

class Facebook extends BaseFacebook {
	
	public function __construct($config)
	{
		sd($config);
	}
	
	
	public function test()
	{
		return 'name';
	}
	
}