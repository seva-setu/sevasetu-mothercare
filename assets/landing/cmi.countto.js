jQuery(document).ready(function($){ 
    // start all the timers
	//$('.timer').countTo();  
	$('.timer').waypoint(function(direction) {
  	$('.timer').countTo('restart');
	}, { offset: '85%' }); 	
});