<?php
/**
 * Options Plugin
 * Make configutarion
*/

if ( !class_exists('iMinify_make') ) {

class iMinify_make{

	public $parameter       = array();
	public $options         = array();
	public $components      = array();



	function __construct(){

		if( is_admin() )
			self::configuration_plugin();
		else
			self::parameters();

	}

	function getHeaderPlugin(){

		return array('id'             =>'iminify_id',
					 'id_menu'        =>'iminify',
					 'name'           =>'iminify_id',
					 'name_long'      =>'iMinify',
					 'name_option'    =>'iminify',
					 'name_plugin_url'=>'iminify',
					 'descripcion'    =>'Improved to 100% load speed of your site compressing HTML, CSS and Javascript',
					 'version'        =>'1.1',
					 'url'            =>'',
					 'logo'           =>'<i class="fa fa-compress" style="padding:16px 18px;"></i>',
					 'logo_text'      =>'', // alt of image
					 'slogan'         =>'', // powered by <a href="">iLenTheme</a>
					 'url_framework'  =>plugins_url()."/iminify/assets/ilenframework",
					 'theme_imagen'   =>plugins_url()."/iminify/assets/images",
					 'twitter'        =>'',
					 'wp_review'      =>'https://wordpress.org/support/view/plugin-reviews/iminify?filter=5',
					 'link_donate'    =>'https://www.paypal.com/cgi-bin/webscr?cmd =_s-xclick&hosted_button_id=QLBNAP75VGMVC',
					 'wp_support'     =>'http://support.ilentheme.com/forums/forum/plugins/iminify/',
					 'type'           =>'plugin',
					 'method'         =>'free',
					 'themeadmin'     =>'fresh',
					 'scripts_admin'  =>array( 'page' => array('iminify' => array('jquery_ui_reset')), ));
	}

	function getOptionsPlugin(){


	global ${'tabs_plugin_' . $this->parameter['name_option']};
	${'tabs_plugin_' . $this->parameter['name_option']} = array();
	${'tabs_plugin_' . $this->parameter['name_option']}['tab01']=array('id'=>'tab01','name'=>'Settings','icon'=>'<i class="fa fa-circle-o"></i>','width'=>'550px'); 

	return array('a'=>array(                'title'      => __('Settings',$this->parameter['name_option']),        //title section
											'title_large'=> __('Settings',$this->parameter['name_option']),//title large section
											'description'=> '', //description section
											'icon'       => 'fa fa-circle-o',
											'tab'        => 'tab01',


											'options'    => array(  

																array(  'title' =>__('Enable / Disable:',$this->parameter['name_option']), //title section
																		'help'  =>'Enable / Disable the "iMInify" plugin.',
																		'type'  =>'checkbox', //type input configuration
																		'value' =>'1', //value default
																		'value_check'=>1,
																		'id'    =>$this->parameter['name_option'].'_enabled', 
																		'name'  =>$this->parameter['name_option'].'_enabled', 
																		'class' =>'', //class 
																		'row'   =>array('a','b')),

																array(  'title' =>__('Ignore Comments:',$this->parameter['name_option']), //title section
																		'help'  =>'Disable the elimination of html comments to reduce the code',
																		'type'  =>'checkbox', //type input configuration
																		'value' =>'0', //value default
																		'value_check'=>1,
																		'id'    =>$this->parameter['name_option'].'_comments', 
																		'name'  =>$this->parameter['name_option'].'_comments', 
																		'class' =>'', //class 
																		'row'   =>array('a','b')), 

																array(  'title' =>__('Ignore CSS:',$this->parameter['name_option']), //title section
																		'help'  =>'Disable the CSS code reduction in HTML.',
																		'type'  =>'checkbox', //type input configuration
																		'value' =>'0', //value default
																		'value_check'=>1,
																		'id'    =>$this->parameter['name_option'].'_css', 
																		'name'  =>$this->parameter['name_option'].'_css', 
																		'class' =>'', //class 
																		'row'   =>array('a','b')), 

																array(  'title' =>__('Ignore Javascript:',$this->parameter['name_option']), //title section
																		'help'  =>'Disable the Javascript code reduction in HTML.',
																		'type'  =>'checkbox', //type input configuration
																		'value' =>'1', //value default
																		'value_check'=>1,
																		'id'    =>$this->parameter['name_option'].'_js', 
																		'name'  =>$this->parameter['name_option'].'_js', 
																		'class' =>'', //class 
																		'row'   =>array('a','b')), 
 

															),
				),
				'last_update'=>time(),


			);
		
	}
 

	function parameters(){
		
		//require_once 'assets/functions/options.php';
		//global $wp_social_pupup_header_plugins;

		//$this->parameter = $wp_social_pupup_header_plugins;
		$this->parameter = self::getHeaderPlugin();
	}

	function myoptions_build(){
		
		//require_once 'assets/functions/options.php';
		//global $wp_social_pupup_make_plugins;

		//$this->options = $wp_social_pupup_make_plugins;
		$this->options = self::getOptionsPlugin();

		return $this->options;
		
	}

	function use_components(){
		//code 
		$this->components = array();

	}

	function configuration_plugin(){
		// set parameter 
		self::parameters();

		// my configuration 
		self::myoptions_build();

		// my component to use
		self::use_components();
	}

}
}


?>