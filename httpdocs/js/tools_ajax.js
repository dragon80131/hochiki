$(document).ready(function(){
	$("#mainTable").each(function(){
		jQuery(this).find(".common-list:even").addClass("even");
	});
	$("#mainTable .common-list").hover(
		function() {
			jQuery(this).addClass("hover");
		},
		function() {
			jQuery(this).removeClass("hover");
		}
	);

	$("input").focus( function() {
		if ($(this).attr('type') == 'text' || $(this).attr('type') == 'file') {
	       	$(this).addClass('focus-text');
		}
	});
	$("input").blur( function() {
		if ($(this).attr('type') == 'text' || $(this).attr('type') == 'file') {
	       	$(this).removeClass('focus-text');
		}
	});

	$("textarea").focus( function() {
       	$(this).addClass('focus-text');
	});
	$("textarea").blur( function() {
       	$(this).removeClass('focus-text');
	});
});
