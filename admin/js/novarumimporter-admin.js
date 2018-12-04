jQuery(document).ready(function($){

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	jQuery('#ni_settings_request_authtype').on('change', function() {
		
		if(this.value == 2)
		   jQuery('#ni_div_auth').show();
	    else
		   jQuery('#ni_div_auth').hide();
		
	});
	
	
	jQuery('#ni_settings_request_type').on('change', function() {
		
		if(this.value == 'GET')
		   jQuery('#ni_div_postfields').hide();
	    else
		   jQuery('#ni_div_postfields').show();
		
	});
	
	
	//startup checks
	if(jQuery('#ni_settings_request_authtype').val() == 2)
	   jQuery('#ni_div_auth').show();
	else
	   jQuery('#ni_div_auth').hide();	
   
	if(jQuery('#ni_settings_request_type').val() == 'GET')
	   jQuery('#ni_div_postfields').hide();
	else
	   jQuery('#ni_div_postfields').show();
	
	 
});
