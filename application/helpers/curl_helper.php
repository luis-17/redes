<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('custom_url'))
{
	function custom_url($url = '')
	{
		$uri = explode('/',uri_string());
		if( $uri[0] == '' ){
			$uri[0] = 'es';
		}
		return base_url($uri[0].'/'.$url);
	}
}
if ( ! function_exists('custom_lang'))
{
	function custom_lang()
	{
		$uri = explode('/',uri_string());
		if( $uri[0] == '' ){
			$uri[0] = 'es';
		}
		return $uri[0];
	}
}