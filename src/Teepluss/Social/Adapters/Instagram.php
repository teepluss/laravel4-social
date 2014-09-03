<?php namespace Teepluss\Social\Adapters;

use Instagram\Instagram as BaseInstagram;
use Instagram\Auth as InstagramAuth;
use Teepluss\Social\Exceptions\SocialException;

class Instagram extends BaseInstagram {

	public function __construct($config = array(), $access_token = null, $client = null)
	{
		//$auth = new InstagramAuth($config);
		//$auth->authorize();
		
		parent::__construct($access_token, $client);
	}
	
	public function getMe($id = null)
	{
		$data = array();
		
		try 
		{
			$user = $this->getCurrentUser();
			
			$name = explode(' ', $user->fullname);
			$last_name = array_pop($name);
			$first_name = implode(' ', $name);
		
			$data = array(
				'uid'        => $user->id,
				'username'   => $user->username,
				'name'       => $user->fullname,
				'first_name' => $first_name,
				'last_name'  => $last_name,
				'email'      => null,
				'picture'    => $user->profile_picture,
			);
		}
		catch (\Instagram\Core\ApiException $e)
		{
			//throw new SocialException($e->getMessage());
		}
		
		return $data;
	}
	
	public function getPhotos($objectId = null, array $options = array())
	{
	
		$defaults = array(
			'type'   => 'image',
			'limit'  => 25,
			'after'  => null,
			'before' => null
		);
		
		$options = array_merge($defaults, $options);
		
		$data = array();
		
		// Fix options;
		$options['count'] = $options['limit'];
		$options['max_id'] = $options['after'];
		$options['min_id'] = $options['before'];
		
		unset($options['limit'], $options['after'], $options['before'], $options['fields']);
		
		//$endpoints = '/'.(empty($objectId) ? 'me' : $objectId).'/photos?'.http_build_query($options);
		
		try
		{
		
			$user = $this->getCurrentUser();
			
			$photos = $user->getMedia($options);
			
			$data = array(
				'data'   => array(),
				'paging' => array()
			);
			
			$data['paging']['after'] = $photos->getNextMaxId();
			
			foreach ($photos as $photo)
			{
				$data['data'][] = array(
					'id'        => $photo->id,
					'name'      => is_object($photo->caption) ? $photo->caption->text : $photo->caption,
					'thumbnail' => $photo->images->thumbnail->url,
					'picture'   => $photo->images->standard_resolution->url
				);
			}
		}
		catch (\Instagram\Core\ApiException $e)
		{
			//throw new SocialException($e->getMessage());
		}
		
		return $data;
		
	}
	
	public function getAlbums()
	{
		return array(
			'data'   => array(),
			'paging' => array()
		);
	}
	
	public function getPages()
	{
		return array();
	}
}