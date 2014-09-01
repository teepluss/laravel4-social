<?php namespace Teepluss\Social;

use Illuminate\Support\ServiceProvider;

class SocialServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;
	
	public function boot()
    {
        $this->package('teepluss/social');
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['social'] = $this->app->share(function($app)
		{
			//$config = $app['config']->get('social::config');
			
			return new Social($app['config']);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('social');
	}

}
