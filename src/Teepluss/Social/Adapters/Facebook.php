<?php namespace Teepluss\Social\Adapters;

use Facebook as BaseFacebook;
use Teepluss\Social\Exceptions\SocialException;

class Facebook extends BaseFacebook {
	
	public function __construct($config)
	{
		parent::__construct($config);
	}
	
	public function getUser()
	{
		//$access_token = \Input::get('access_token');
		//$this->setAccessToken($access_token);
		$data = null;
		
		try 
		{
			$user = $this->api('/me');
		
			$data = array(
				'uid'        => $user['id'],
				'name'       => $user['name'],
				'first_name' => $user['first_name'],
				'last_name'  => $user['last_name'],
				'email'      => $user['email'],
			);
		}
		catch (\FacebookApiException $e)
		{
			//throw new SocialException($e->getMessage());
		}
		
		return $data;
	}
	
	public function getPages()
	{
		//$access_token = \Input::get('access_token');
		//$this->setAccessToken($access_token);
		$data = null;
		
		try
		{
			$pages = $this->api('/me/accounts');
			
			$data = array();
			
			foreach ($pages['data'] as $page)
			{
				
				$data[] = array(
					'id'   => $page['id'],
					'name' => $page['name'],
				);
				
			}

		}
		catch (\FacebookApiException $e)
		{
			//throw new SocialException($e->getMessage());
		}
		
		return $data;
	}
	
	public function getPhotos($pageId = null)
	{
		if (empty($pageId))
		{
			$endpoints = '/me';
		}
		else
		{
			$endpoints = '/'.$pageId;
		}
		
		$endpoints .= '/photos';
		
		try
		{
			
			$photos = $this->api($endpoints);
			
			$data = array();
			
			sd($photos);
			
		}
		catch (\FacebookApiException $e)
		{
			//throw new SocialException($e->getMessage());
		}
		
		return $data;
		
	}
	
	
	public function test()
	{
		$name = 'name';
		return 'name';
	}
	
}