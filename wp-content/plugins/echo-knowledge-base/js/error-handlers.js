/** This file will show notice about js errors */
var errorsList = [];
var showNoticeTimeout = true;
var previousErrorHandler = window.onerror;
var jqueryChecked = false;

// create new errors handler - fires when an error occurs during object loading
window.onerror = function( errorMsg, url, lineNumber, columnNumber, errorObject ) {
	
	errorsList.push({ 'msg' : errorMsg, 'url' : url });

	if ( showNoticeTimeout ) {
		setTimeout(epkbShowErrorNotices, 2000);
		showNoticeTimeout = false;
	}

	// run previous errors handler if it exists
	if ( previousErrorHandler ) {
		return previousErrorHandler( errorMsg, url, lineNumber, columnNumber, errorObject );
	}
	
	// run default handler 
	return false;
};

function epkbShowErrorNotices() {
	
	// wait for jquery
	if ( typeof jQuery == 'undefined' || jQuery('.epkb-js-error-notice').length == 0 ) {
		setTimeout( epkbShowErrorNotices, 1000 );
		if ( jqueryChecked ) {
			return;	// prevent infinite loop
		}
		jqueryChecked = true;
		return;
	}
	
	// hide previous message 
	jQuery('.epkb-js-error-notice').hide('fast');

	let error;
	for (error of errorsList) {

		jQuery('.epkb-js-error-notice').find('.epkb-js-error-msg').text(error.msg);
		jQuery('.epkb-js-error-notice').find('.epkb-js-error-url').text(error.url);
		jQuery('.epkb-js-error-notice').show('fast');

		console.log();
	}

	jQuery('.epkb-js-error-close').click(function(){
		jQuery(this).closest('.epkb-js-error-notice').hide('fast');
	});
}