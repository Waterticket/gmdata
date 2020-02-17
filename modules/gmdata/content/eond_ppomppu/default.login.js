 /* After Login */
function completeLogin(ret_obj, response_tags, params, fo_obj) {
	var url =  current_url.setQuery('act','');
	location.href = url;
}

jQuery(function($){
	// Login
	// Div unwrap
	var $account = $('.account');
	$account.unwrap().unwrap();
	// Toggle
	var $acTog = $('a[href="#acField"]');
	var $acField = $('#acField');
	$acTog.click(function(){
		$this = $(this);
		$acField.slideToggle(200, function(){
			var $user_id = $(this).find('input[name="user_id"]:eq(0)');
			if($user_id.is(':visible')){
				$user_id.focus();
			} else {
				$this.focus();
			}
		});
		return false;
	});


	// Login Error
	$('#fo_login_widget .message').parent($acField).show();
});
