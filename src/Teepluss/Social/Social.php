<?php namespace Teepluss\Social;

class Social {

	public static $instance = array();

	protected $config;

	public function __construct($config)
	{
		$this->config = $config;
	}
	
	public function adapter($adapter, $config = array())
	{
		if ( ! isset(static::$instance[$adapter]))
		{
			$default = $this->config->get('social::'.$adapter);
		
			$config = array_merge($default, $config);
		
			switch ($adapter)
			{
				case 'facebook' : $instance = new Adapters\Facebook($config); break;
			}
		
			static::$instance[$adapter] = $instance;
		}
		
		return static::$instance[$adapter];
	}
	
}