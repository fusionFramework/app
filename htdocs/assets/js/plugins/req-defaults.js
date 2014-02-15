$.fn.req.defaults.errorHandler = function (jqXHR, status, error) {
	// status: null, "timeout", "error", "abort", and "parser error"
	// error: textual portion of the HTTP status, such as "Not Found" or "Internal Server Error."
	/**
	 * jqXHR useful methods:
	 *  - getResponseHeader()
	 *  - getAllResponseHeaders()
	 *  - statusCode()
	 */
	if(typeof $.fn.Req.statusCodes[jqXHR.status] == 'undefined') {
		// show some debug info
		console.log('Status: '+jqXHR.status+' - '+status);
		console.log('Error: '+error);

		// show notification
		$('.notifications').notify({
			message: { text: 'Request failed.' },
			type: 'danger',
			fadeOut: { enabled: false }
		}).show();
	}
};

$.fn.req.defaultRequestHandlers = {
	success: function(response, status, jqXHR) {
		console.log('FIRED success');
		$.reqResponse(response, function(response){
			$('.notifications').notify({
				message: { text: response.value },
				type: response.type,
				fadeOut: { enabled: true, delay: 5000 }
			}).show();
		});
	},
	error: function(response, status, jqXHR) {
		$.reqResponse(response, function(response){
			$('.notifications').notify({
				message: { text: response.value },
				type: 'danger',
				fadeOut: { enabled: true, delay: 5000 }
			}).show();
		});
	}
};

$.fn.req.statusCodes = {
	404: function() {
		$('.notifications').notify({
			message: { text: '<i class="fa fa-exclamation-circle"></i> 404, request not found.' },
			type: 'warning',
			fadeOut: { enabled: true, delay: 5000 }
		}).show();
	},
	403: function() {
		$('.notifications').notify({
			message: { text: '<i class="fa fa-shield"></i> You don\'t have the permission to complete this request.' },
			type: 'info',
			fadeOut: { enabled: true, delay: 7000 }
		}).show();
	},
	500: function() {
		$('.notifications').notify({
			message: { text: '<i class="fa fa-exclamation-circle"></i> Error performing the request.' },
			type: 'danger',
			fadeOut: { enabled: true, delay: 5000 }
		}).show();
	}
};