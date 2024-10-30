<?php
/*
Plugin Name: iMinify
Plugin URI:
Description: Improved to 100% load speed of your site compressing HTML, CSS and Javascript
Tags: minify code,html minify,compress, CSS, fix, google page speed, javascript, JS,optimize, performance, speed
Version: 1.1
Author: iLen
Author URI:  http://ilentheme.com
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd =_s-xclick&hosted_button_id=QLBNAP75VGMVC
*/
if ( !class_exists('iMinify') ) {
require_once 'assets/ilenframework/assets/lib/utils.php'; // get utils
require_once 'assets/functions/options.php';
class iMinify extends iMinify_make{
	// Settings
	protected $compress_css    = true;
	protected $compress_js     = false;
	protected $info_comment    = true;
	protected $remove_comments = true;

	// Variables
	protected $html;
	public function __construct($html){

		parent::__construct(); // configuration general

		global $iminify_options,$if_utils;

		if( is_admin() ){
			// add scripts & styles
			add_action( 'admin_enqueue_scripts', array( &$this,'script_and_style_admin' ) );
		}else{
			
			$this->compress_css    = isset($iminify_options->css) && $iminify_options->css? false: true;
			$this->compress_js     = isset($iminify_options->js) && $iminify_options->js? false: true;
			$this->remove_comments = isset($iminify_options->comments) && $iminify_options->comments? false: true;

			if (!empty($html)){
				$this->parseHTML($html);
			}
		}


	}

	public function __toString(){
		return $this->html;
	}

	protected function bottomComment($raw, $compressed){
		$raw = strlen($raw);
		$compressed = strlen($compressed);
		
		$savings = ($raw-$compressed) / $raw * 100;
		
		$savings = round($savings, 2);
		
		return '<!--HTML compressed by iMinify, size saved '.$savings.'%. From '.$raw.' bytes, now '.$compressed.' bytes-->';
	}
	protected function minifyHTML($html){
		$pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
		preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
		$overriding = false;
		$raw_tag = false;
		// Variable reused for output
		$html = '';
		foreach ($matches as $token){
			$tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;
			
			$content = $token[0];
			
			if (is_null($tag))
			{
				if ( !empty($token['script']) )
				{
					$strip = $this->compress_js;
				}
				else if ( !empty($token['style']) )
				{
					$strip = $this->compress_css;
				}
				else if ($content == '<!--wp-html-compression no compression-->')
				{
					$overriding = !$overriding;
					
					// Don't print the comment
					continue;
				}
				else if ($this->remove_comments)
				{
					if (!$overriding && $raw_tag != 'textarea')
					{
						// Remove any HTML comments, except MSIE conditional comments
						$content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
					}
				}
			}
			else
			{
				if ($tag == 'pre' || $tag == 'textarea')
				{
					$raw_tag = $tag;
				}
				else if ($tag == '/pre' || $tag == '/textarea')
				{
					$raw_tag = false;
				}
				else
				{
					if ($raw_tag || $overriding)
					{
						$strip = false;
					}
					else
					{
						$strip = true;
						
						// Remove any empty attributes, except:
						// action, alt, content, src
						$content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);
						
						// Remove any space before the end of self-closing XHTML tags
						// JavaScript excluded
						$content = str_replace(' />', '/>', $content);
					}
				}
			}
			
			if ($strip)
			{
				$content = $this->removeWhiteSpace($content);
			}
			
			$html .= $content;
		}
		
		return $html;
	}
			
	public function parseHTML($html){
		$this->html = $this->minifyHTML($html);
		
		if ($this->info_comment)
		{
			$this->html .= "\n" . $this->bottomComment($html, $this->html);
		}
	}
		
	protected function removeWhiteSpace($str){
		$str = str_replace("\t", ' ', $str);
		$str = str_replace("\n",  '', $str);
		$str = str_replace("\r",  '', $str);
		
		while (stristr($str, '  '))
		{
			$str = str_replace('  ', ' ', $str);
		}
		
		return $str;
	}

	function script_and_style_admin(){

		// Register styles
		wp_register_style( 'front-css-'.$this->parameter["name_option"], plugins_url('/assets/css/admin.css',__FILE__),'all',$this->parameter['version'] );
		// Enqueue styles
		wp_enqueue_style( 'front-css-'.$this->parameter["name_option"] );

	}

} // end class
} // end if

function wp_html_compression_finish($html){
	return new iMinify($html);
}

function wp_html_compression_start()
{
	ob_start('wp_html_compression_finish');
}



global $IF_CONFIG, $IMINIFY;
unset($IF_CONFIG);
$IF_CONFIG = null;
$IF_CONFIG = $IMINIFY = new iMinify($html=null);
require_once "assets/ilenframework/core.php";

global $iminify_options,$if_utils;
$iminify_options = $if_utils->IF_get_option( $IMINIFY->parameter['name_option'] );
if( isset($iminify_options->enabled) && $iminify_options->enabled ){
	add_action('get_header', 'wp_html_compression_start');	
}

?>