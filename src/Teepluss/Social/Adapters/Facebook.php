<?php namespace Teepluss\Social\Adapters;

use Facebook as BaseFacebook;
use Teepluss\Social\Exceptions\SocialException;

class Facebook extends BaseFacebook {
	
	public function __construct($config)
	{
		parent::__construct($config);
	}
	
	public function getUser(array $options = array())
	{
		$defaults = array(
			'fields' => 'id,name,first_name,last_name,email,picture.type(large)',
		);
		
		$options = array_merge($defaults, $options);
	
		$data = array();
		
		try 
		{
			$user = $this->api('/me', $options);
		
			$data = array(
				'uid'        => $user['id'],
				'name'       => $user['name'],
				'first_name' => $user['first_name'],
				'last_name'  => $user['last_name'],
				'email'      => $user['email'],
				'picture'    => $user['picture']['data']['url']
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
		$data = array();
		
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
	
	public function getPhotos($objectId = null, array $options = array())
	{
	
		$defaults = array(
			'type'   => 'uploaded',
			'fields' => 'id,name,picture,source',
			'limit'  => 25,
			'after'  => null,
			'before' => null
		);
		
		$options = array_merge($defaults, $options);
		
		$endpoints = '/'.(empty($objectId) ? 'me' : $objectId).'/photos?'.http_build_query($options);
		
		$data = array();
		
		try
		{
			
			$photos = $this->api($endpoints);
			
			$data = array(
				'data'   => array(),
				'paging' => array()
			);
			
			$data['paging'] = $photos['paging']['cursors'];
			
			foreach ($photos['data'] as $photo)
			{
				$data['data'][] = array(
					'id'        => $photo['id'],
					'name'      => array_get($photo, 'name', null),
					'thumbnail' => $photo['picture'],
					'picture'   => $photo['source']
				);
			}
			
		}
		catch (\FacebookApiException $e)
		{
			//throw new SocialException($e->getMessage());
		}
		
		return $data;
		
	}
	
	public function getAlbums($objectId = null, array $options = array())
	{
	
		$defaults = array(
			'fields' => 'id,name,cover_photo,count',
			'limit'  => 25,
			'after'  => null,
			'before' => null
		);
		
		$options = array_merge($defaults, $options);
		
		$endpoints = '/'.(empty($objectId) ? 'me' : $objectId).'/albums?'.http_build_query($options);
		
		$data = array();
		
		try
		{
			
			$albums = $this->api($endpoints);
			
			$data = array(
				'data'   => array(),
				'paging' => array()
			);
			
			$data['paging'] = $albums['paging']['cursors'];
			
			foreach ($albums['data'] as $album)
			{
				$data['data'][] = array(
					'id'          => $album['id'],
					'name'        => array_get($album, 'name', null),
					'count'       => $album['count'],
					'thumbnail'   => 'https://graph.facebook.com/'.$album['id'].'/picture?type=album&access_token='.$this->getAccessToken()
				);
			}
			
		}
		catch (\FacebookApiException $e)
		{
			//throw new SocialException($e->getMessage());
		}
		
		return $data;
		
	}
	
}