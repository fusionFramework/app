$(document).ready(function() {
	$('a.confirm').each(function(index){
		$(this).on('click', function(e){
			e.preventDefault();
			var follow = confirm('Are you sure you want to delete this '+$(this).data('confirm')+'?');

			if(follow)
			{
				window.location = $(this).attr('href');
			}
		})
	});
});