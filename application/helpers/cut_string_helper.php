<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------

/**
 * Phrase Highlighter
 *
 * Highlights a phrase within a text string
 *
 * @access	public
 * @param	string	the text string
 * @param	string	the phrase you'd like to highlight
 * @param	string	the openging tag to precede the phrase with
 * @param	string	the closing tag to end the phrase with
 * @return	string
 */
if ( ! function_exists('cut_string'))
{
	function cut_string($string, $sub_string)
	{
		if ($string == '')
		{
			return '';
		}

		if ($sub_string != '')
		{
			return preg_replace('/('.preg_quote($sub_string, '/').')/i', '', $string);
		}

		return $string;
	}
}