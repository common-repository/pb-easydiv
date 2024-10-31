<?php

/*
Plugin Name: pb-easyDiv
Plugin URI: http://pascal-berkhahn.de/category/plugins/
Description: A filter for WordPress that inserts div tags easily. Useful if your're using a WYSIWYG- or any other RichtText editor.
Version: 2.0
Author: Pascal Berkhahn
Author URI: http://pascal-berkhahn.de/

**********************************************************************
Copyright (c) 2007 Pascal Berkhahn
Released under the terms of the GNU GPL: http://www.gnu.org/licenses/gpl.txt

This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
**********************************************************************

Installation: Place this file in your plugins directory and activate it in your admin panel.

ATTENTION: Version 2 is NOT backwards compatible to version 1!

Usage, Issues, Change log:
Visit http://wordpress.org/extend/plugins/pb-easydiv/

*/
define('PB_EASYDIV_REGEXP', '%\[div(?:[[:space:]](.+?))?\](.*?)\[\/div\]%is');

# define("PB_EASYDIV_TARGET", "<div###ATTRIBUTES###>###TEXT###</div>"); // XHTML validation problem (div-tag inside p-tag is not allowed)
# define("PB_EASYDIV_TARGET", "</p><div###ATTRIBUTES###><p>###TEXT###</p></div><p>"); // valid XHTML, but ugly style (padding and margin between p-tags)
define('PB_EASYDIV_TARGET', '</p><div###ATTRIBUTES###><p style="padding:0px;margin:0px;">###CONTENT###</p></div><p>'); //ugly but valid code and beautiful display :)

function pb_easyDiv_plugin_callback($match)
{
	$output = PB_EASYDIV_TARGET;
	if(isset($match[2]))
	{
		$output = str_replace("###ATTRIBUTES###", ($match[2] ? ' '.str_replace('&#8221;','"',$match[1]) : ''), $output);
		$output = str_replace("###CONTENT###", ($match[2] ? $match[2] : $match[1]), $output);
	} return ($output);
}

function pb_easyDiv_plugin($content)
{
	$countDiv = substr_count($content, '[div');
	for($i = 1; $i <= $countDiv; $i++)
	{ /* I know that this is not a beautiful method to enable interleaving. Proposals to improve it are always welcomed. */
		$content = preg_replace_callback(PB_EASYDIV_REGEXP, 'pb_easyDiv_plugin_callback', $content);
	} unset($countDiv);
	return ($content);
}

add_filter('the_content', 'pb_easyDiv_plugin');
add_filter('the_excerpt', 'pb_easyDiv_plugin');
// add_filter('comment_text', 'pb_easyDiv_plugin');
?>