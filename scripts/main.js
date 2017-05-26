// On ready function
$(function(){
	// Eventlistener on article rating buttons
	$('a[data-id]').click(function(){
		// The paragrapgh tag containing vite info
		var votebox = $(this).parent().parent().prev('.votebox');
		// btn var which works as jquery object
		var btn = $(this);
		// Send vote and id to castvote.php
		$.ajax({
			'method' : 'POST',
			'dataType'	: 'json',
			'url'		: 'controllers/functions/castvote.php',
			'data'	: {
				id : $(this).data('id'),
				vote : $(this).data('vote'),
			},
			
			// If ajax request suceedes, run function block
			success : function(data){
				
				// If database returns null when therer are no votes, replace with an actual zero
				// Also convert floats to integers
				var vote = data.vote === null ? 0 : parseInt(data.vote);
				var percent = data.percent === null ? 0 : parseInt(data.percent);
				
				// Update votebox paragrapgh
				$(votebox).text("Vote: "+vote+" / "+percent+"%");
				
				// Toggle active class on the like/dislike button which is clicked
				$(btn).parent().parent().find('a').not(btn).removeClass('active');
				$(btn).toggleClass('active');
				
			}
			
		});
		
	});
});