<?php

/**
 * Request Form
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www.novarumsoftware.com
 * @since      1.0.0
 *
 * @package    Novarumimporter
 * @subpackage Novarumimporter/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->



			
			<p><strong><?php _e("Url:", 'Novarumimporter' ); ?></strong></p>
			<p>
		     <input type="text" id="ni_settings_request_url" name="ni_settings_request_url" value="<?php echo $this->optionValues['ni_settings_request_url']; ?>" style="width:50%;">
		    </p>
			
			<p><strong><?php _e("Request Type:", 'Novarumimporter' ); ?></strong></p>
			<p>
			  <select id="ni_settings_request_type" name="ni_settings_request_type">
			     <option value="GET" <?php if($this->optionValues['ni_settings_request_type'] == 'GET'){ echo 'selected';} ?>>GET</option>
				 <option value="POST" <?php if($this->optionValues['ni_settings_request_type'] == 'POST'){ echo 'selected';} ?>>POST</option>
				 <option value="PUT" <?php if($this->optionValues['ni_settings_request_type'] == 'PUT'){ echo 'selected';} ?>>PUT</option>
			  </select>
			</p>
			
			<div id="ni_div_postfields" style="display:none">
				<p><strong><?php _e("Fields: (format: key1=value1&key2=value2)", 'Novarumimporter' ); ?></strong></p>
				<p>			
				  <textarea name="ni_settings_fields" style="width:50%;height:100px;"><?php echo $this->optionValues['ni_settings_fields']; ?></textarea>
				</p>
			</div>
			
			<p><strong><?php _e("Authentication Type:", 'Novarumimporter' ); ?></strong></p>
			<p>
			  <select id="ni_settings_request_authtype" name="ni_settings_request_authtype">
			     <option value="1" <?php if($this->optionValues['ni_settings_request_authtype'] == 1){ echo 'selected';} ?>>No Authentication</option>
				 <option value="2" <?php if($this->optionValues['ni_settings_request_authtype'] == 2){ echo 'selected';} ?>>Basic Authentication</option>
			  </select>
			</p>
			
			<div id="ni_div_auth" style="display:none">
			  <p><strong><?php _e("Username:", 'Novarumimporter' ); ?></strong></p>
			  <p>
		        <input type="text" id="ni_settings_request_username" name="ni_settings_request_username" value="<?php echo $this->optionValues['ni_settings_request_username']; ?>" style="width:30%;">
		      </p>
			  
			  <p><strong><?php _e("Password:", 'Novarumimporter' ); ?></strong></p>
			  <p>
		        <input type="password" id="ni_settings_request_password" name="ni_settings_request_password" value="<?php echo $this->optionValues['ni_settings_request_password']; ?>" style="width:30%;">
		      </p>
				
			</div>
			
			<p><strong><?php _e("Headers: (One per line)", 'Novarumimporter' ); ?></strong></p>
            <p>			
			  <textarea name="ni_settings_headers" style="width:50%;height:100px;"><?php echo $this->optionValues['ni_settings_headers']; ?></textarea>
			</p>
			
			<p>
			  <input type="hidden" name="ni_settings_test_connection" value="1">
			  <input type="submit" name="ni_settings_submit" value="Save and Make a Test Call">
			</p>
			
			<hr />
			
			<?php if(!empty($test_response)): ?>
			   <h3>Test Response:</h3> 			   
			     <?php if($isJsonValid): ?>
				   <h4 class="ni_success">Response is valid JSON</h4>
				 <?php else: ?>
				   <h4 class="ni_error">Response is not JSON. Please make sure it's valid JSON before proceed.</h4>
				 <?php endif; ?>
			   </h4>
			   
			   <?php if($isJsonValid): ?>
			     <a href="/wp-admin/options-general.php?page=Novarumimporter_settings&tab=response_options">
			       <input type="button" name="ni_settings_next" value="Now Setup the importer">
				 </a>
			   <?php endif; ?>
			   
			   <h3>Response Dump: </h3>
			   <p><?php var_dump($test_response); ?></p>

			   <hr />
			<?php endif; ?>