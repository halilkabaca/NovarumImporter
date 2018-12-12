<?php

/**
 * The admin-specific functionality of Novarum Importer plugin.
 *
 * @link       www.novarumsoftware.com
 * @since      1.0.0
 *
 * @package    Novarumimporter
 * @subpackage Novarumimporter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * It divides the functionality as Request and Import part
 * Handles the logic based on what's submitted on the forms
 *
 * @package    Novarumimporter
 * @subpackage Novarumimporter/admin
 * @author     Halil Kabaca <hk@novarumsoftware.com>
 */
class Novarumimporter_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	private $optionValues;
	
	private $results;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function novarumimporter_enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Novarumimporter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Novarumimporter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/novarumimporter-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function novarumimporter_enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Novarumimporter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Novarumimporter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/novarumimporter-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	public function novarumimporter_defineSettings()
	{		
		add_options_page( 'Novarum Importer Options', 'Novarum Importer', 'manage_options', 'Novarumimporter_settings', array($this,'novarumimporter_options') );
			 
	}

	public function novarumimporter_options() 
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		
		if( isset( $_GET[ 'tab' ] ) ) {
		   $active_tab = $_GET[ 'tab' ];
		}
		
		if($active_tab == '')
		   $active_tab = 'request_options';

		$this->novarumimporter_saveOptions();		
		$this->novarumimporter_getOptions();		

		$test_response = "";
		$isJsonValid = false;
		
		if(isset($_POST['ni_settings_test_connection']))
		{
		  $test_response = $this->novarumimporter_testConnection();
		  $isJsonValid = $this->novarumimporter_isJsonValid($test_response);
		  
		  if($isJsonValid)
		  {
			 update_option('ni_isJsonValid', 1 , false );
			 
			 //Halilk: not implemented yet! This is to determine the keys
			 //update_option('ni_responseKeys', serialize($this->parseKeys($test_response)) , false );
		  }
		  else
			 update_option('ni_isJsonValid', 0 , false );
		  
		}
		else if(isset($_POST['ni_settings_import']))
		{
			$test_response = $this->novarumimporter_testConnection();
			
			$this->results = $this->novarumimporter_importData($test_response);
			
		}
		
		?><h2 class="nav-tab-wrapper">
			<a href="?page=Novarumimporter_settings&tab=request_options" class="nav-tab <?php echo $active_tab == 'request_options' ? 'nav-tab-active' : ''; ?>">Request Options</a>
			<a href="?page=Novarumimporter_settings&tab=response_options" class="nav-tab <?php echo $active_tab == 'response_options' ? 'nav-tab-active' : ''; ?>">Importer Options</a>
		  </h2>
		
		
		
		<form name="form1" method="post" action="">
		  
		  <?php if( $active_tab == 'request_options' ): ?>
				
				<?php require('partials/novarumimporter-admin-request.php'); ?>
		  
		  <?php else: ?>
		        
				<?php require('partials/novarumimporter-admin-response.php'); ?>
				
		  <?php endif; ?>
		  
		  
		  <div class="wrap">
		    <p>Novarum Importer plugin to call REST Apis and import JSON response as post types to wordpress.</p>
			
		  </div>
		
		</form>
		<?php
	}	
	
	
	public function novarumimporter_isJsonValid($string) {
       json_decode($string);
       return (json_last_error() == JSON_ERROR_NONE);
	}
	
	public function novarumimporter_testConnection()
	{
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, $this->optionValues['ni_settings_request_url']);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		
		//Set Headers
		if(isset($this->optionValues['ni_settings_headers']))
		{
			$headers = explode("\n",$this->optionValues['ni_settings_headers']);
			
			if(count($headers) > 0)
			{
				curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
			}
			
		}
		
		if($this->optionValues['ni_settings_request_authtype'] == 2)
		{
			curl_setopt($handle, CURLOPT_USERPWD, $this->optionValues['ni_settings_request_username'] . ":" . $this->optionValues['ni_settings_request_password']);
		}
		
		if(isset($this->optionValues['ni_settings_fields']))
		{
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->optionValues['ni_settings_fields']);
		}
		

		$data = curl_exec($handle);
		
		//Check if we got an error instead
		if (curl_error($handle)) 
		{
           $data = curl_error($handle);
		}
		
		curl_close($handle);		

		return $data;
	}
	
	
	public function novarumimporter_saveOptions()
	{
		if(isset($_POST['ni_settings_request_url']))
		{
			update_option('ni_settings_request_url', $_POST['ni_settings_request_url'] , false );
		}
		
		if(isset($_POST['ni_settings_request_type']))
		{
			update_option('ni_settings_request_type', $_POST['ni_settings_request_type'] , false );
		}
		
		if(isset($_POST['ni_settings_request_authtype']))
		{
			update_option('ni_settings_request_authtype', $_POST['ni_settings_request_authtype'] , false );
		}
		
		if(isset($_POST['ni_settings_request_username']))
		{
			update_option('ni_settings_request_username', $_POST['ni_settings_request_username'] , false );
		}
		
		if(isset($_POST['ni_settings_request_password']))
		{
			update_option('ni_settings_request_password', $_POST['ni_settings_request_password'] , false );
		}
		
		if(isset($_POST['ni_settings_headers']))
		{
			update_option('ni_settings_headers', $_POST['ni_settings_headers'] , false );
		}
		
		if(isset($_POST['ni_settings_fields']))
		{
			update_option('ni_settings_fields', $_POST['ni_settings_fields'] , false );
		}
		
		if(isset($_POST['ni_settings_post_type']))
		{
			update_option('ni_settings_post_type', $_POST['ni_settings_post_type'] , false );
		}
		
		if(isset($_POST['ni_settings_arraykey']))
		{
			update_option('ni_settings_arraykey', $_POST['ni_settings_arraykey'] , false );
		}
		
		if(isset($_POST['ni_settings_titlekey']))
		{
			update_option('ni_settings_titlekey', $_POST['ni_settings_titlekey'] , false );
		}
		
		if(isset($_POST['ni_settings_descriptionkey']))
		{
			update_option('ni_settings_descriptionkey', $_POST['ni_settings_descriptionkey'] , false );
		}
		
		if(isset($_POST['ni_settings_datekey']))
		{
			update_option('ni_settings_datekey', $_POST['ni_settings_datekey'] , false );
		}
		
		if(isset($_POST['ni_settings_post_status']))
		{
			update_option('ni_settings_post_status', $_POST['ni_settings_post_status'] , false );
		}
		
		
	}
	
	public function novarumimporter_getOptions()
	{
		$this->optionValues = array();
		
		$this->optionValues['ni_settings_request_url']      = get_option('ni_settings_request_url','');
		$this->optionValues['ni_settings_request_type']     = get_option('ni_settings_request_type','');
		$this->optionValues['ni_settings_request_authtype'] = get_option('ni_settings_request_authtype','');
		$this->optionValues['ni_settings_request_username'] = get_option('ni_settings_request_username','');
		$this->optionValues['ni_settings_request_password'] = get_option('ni_settings_request_password','');
		$this->optionValues['ni_settings_headers']          = get_option('ni_settings_headers','');
		
		$this->optionValues['ni_isJsonValid']           = get_option('ni_isJsonValid','');
		$this->optionValues['ni_responseKeys']          = get_option('ni_responseKeys','');
		
		
		$this->optionValues['ni_settings_post_type']         = get_option('ni_settings_post_type','');
		$this->optionValues['ni_settings_arraykey']          = get_option('ni_settings_arraykey','');
		$this->optionValues['ni_settings_titlekey']          = get_option('ni_settings_titlekey','');
		$this->optionValues['ni_settings_descriptionkey']    = get_option('ni_settings_descriptionkey','');
		$this->optionValues['ni_settings_datekey']           = get_option('ni_settings_datekey','');
		$this->optionValues['ni_settings_post_status']       = get_option('ni_settings_post_status','');
		
	}
	
	public function novarumimporter_parseKeys($response)
	{
		$parsedData = json_decode($response,true);
		$keyArray = array_keys($parsedData);
		$allKeys = array();
		
		if(count($keyArray) > 0 )
		{
			foreach($keyArray as $eachKey)
			{
				if(is_array($parsedData[$eachKey]))
				{
					$subKeys = array_keys($parsedData[$eachKey]);
					
					foreach($subKeys as $eachSubKey)
					{
						if(is_array($parsedData[$eachKey][$eachSubKey]))
				        {
							$subSubKeys = array_keys($parsedData[$eachKey][$eachSubKey]);
							
							foreach($subSubKeys as $eachSubSubKey)
							{
								if(is_array($parsedData[$eachKey][$eachSubKey][$eachSubSubKey]))
				                {
									$allKeys[$eachKey][$eachSubKey][$eachSubSubKey] = array_keys($parsedData[$eachKey][$eachSubKey][$eachSubSubKey]);
								}
								else
								{
									$allKeys[$eachKey][$eachSubKey][$eachSubSubKey] = $eachSubSubKey;
								}
							}
							
							
						}
						else
						{
							$allKeys[$eachKey][$eachSubKey] = $eachSubKey;
						}
					}
					
					
				}
				else
				{
					$allKeys[$eachKey] = $eachKey;
				}
			}
		}
		
		return $allKeys;
		
	}
	
	
	public function novarumimporter_importData($response)
	{
		$parsedData = json_decode($response,true);
		$successCount = 0;
		$errorCount = 0;
		
		
		$arrayData = $parsedData;
		
		if(stripos($this->optionValues['ni_settings_arraykey'],".") > 0)
		{
		  //It's a nested array
		  $arrayKey = explode(".",$this->optionValues['ni_settings_arraykey']);
		  
		  foreach($arrayKey as $eachKey)
		  {
				$arrayData = $arrayData[$eachKey];
		  }
		}
		elseif( trim($this->optionValues['ni_settings_arraykey']) != "" )
		{
			//There is some value but not nested
			$arrayData = $arrayData[ $this->optionValues['ni_settings_arraykey'] ];
		}

		
		foreach($arrayData as $eachData)
		{
			$post_type = $eachData[$this->optionValues['ni_settings_post_type']];
			$post_title = $eachData[$this->optionValues['ni_settings_titlekey']];
			$content = $eachData[$this->optionValues['ni_settings_descriptionkey']];
			
			if(empty($eachData[$this->optionValues['ni_settings_datekey']]))
			   $date = date("Y-m-d H:i:s");
		    else
			{
			   $dateRaw = $eachData[$this->optionValues['ni_settings_datekey']];
			   $date = date("Y-m-d H:i:s",strtotime($dateRaw));
			}
			$post_status = $eachData[$this->optionValues['ni_settings_post_status']];
			
			$result = wp_insert_post(array(
			  "post_title"    => $post_title,
			  "post_type"     => $post_type,
			  "post_content"  => $content,
			  "post_status"   => $post_status,
			  "post_date"     => $date,
			));
			
			if($result > 0)
				$successCount +=1;
			else
				$errorCount +=1;
			
		}
		
		return array("successCount" => $successCount,
		             "errorCount" => $errorCount
					 );
		
		
	}
	
	
	
	

}
