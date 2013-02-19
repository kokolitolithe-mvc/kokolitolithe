$(document).ready(function() {
	$('font>table').addClass('xdebug-error');
	$('font>table *').removeAttr('style').removeAttr('bgcolor');
	$('font>table tr:first-child').addClass('xdebug-error_description');
	$('font>table tr:nth-child(2)').addClass('xdebug-error_callStack');
});