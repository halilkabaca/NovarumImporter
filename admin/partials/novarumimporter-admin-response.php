<?php

/**
 * Response Form
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

<?php

$postTypes = get_post_types(array("public" => true));
$postStatus = array("draft" => "Draft",
                    "publish" => "Publish",
				    );
  

?>
			<?php if($this->results): ?>
			  <h3 class="ni_success"><?php echo $this->results['successCount']; ?> records imported successfully</h3>
			  
			  <?php if($this->results['errorCount'] > 0): ?>
			     <h3 class="ni_error"><?php echo $this->results['errorCount']; ?> records could not be imported</h3>
			  <?php endif; ?>
			  
			<?php endif; ?>

			<p><strong><?php _e("Post Type:", 'Novarumimporter' ); ?></strong></p>
			<p>
			  <select id="ni_settings_post_type" name="ni_settings_post_type">
			     <?php foreach($postTypes as $key=>$value): ?>
				   <option value="<?php echo $key; ?>" <?php if($this->optionValues['ni_settings_post_type'] == $key){ echo 'selected';} ?>><?php echo $value; ?></option>
				 <?php endforeach; ?>
			  </select>
			</p>
			
			
			<p><strong><?php _e("*Array Key:", 'Novarumimporter' ); ?></strong> Separate each level with . ie Response.Data</p>
			<p>
		       <input type="text" id="ni_settings_arraykey" name="ni_settings_arraykey" value="<?php echo $this->optionValues['ni_settings_arraykey']; ?>" style="width:30%;">
		    </p>
			
			
			<p><strong><?php _e("*Title Key:", 'Novarumimporter' ); ?></strong> Make sure the key is under the Array Key</p>
			<p>
		       <input type="text" id="ni_settings_titlekey" name="ni_settings_titlekey" value="<?php echo $this->optionValues['ni_settings_titlekey']; ?>" style="width:30%;">
		    </p>

			<p><strong><?php _e("*Description Key:", 'Novarumimporter' ); ?></strong> Make sure the key is under the Array Key</p>
			<p>
		       <input type="text" id="ni_settings_descriptionkey" name="ni_settings_descriptionkey" value="<?php echo $this->optionValues['ni_settings_descriptionkey']; ?>" style="width:30%;">
		    </p>

			<p><strong><?php _e("Date Key:", 'Novarumimporter' ); ?></strong> - If left blank, it takes today's date</p>
			<p>
		       <input type="text" id="ni_settings_datekey" name="ni_settings_datekey" value="<?php echo $this->optionValues['ni_settings_datekey']; ?>" style="width:30%;">
		    </p>

			<p><strong><?php _e("Post Status:", 'Novarumimporter' ); ?></strong></p>
			<p>
			  <select id="ni_settings_post_status" name="ni_settings_post_status">
			     <?php foreach($postStatus as $key=>$value): ?>
				   <option value="<?php echo $key; ?>" <?php if($this->optionValues['ni_settings_post_status'] == $key){ echo 'selected';} ?>><?php echo $value; ?></option>
				 <?php endforeach; ?>
			  </select>
			</p>			
			

			<p>
			  <input type="hidden" name="ni_settings_import" value="1">
			  <input type="submit" name="ni_settings_submit" value="Save and Import">
			</p>
			
			<hr />
