$(document).ready(function() {
	$(".link-button").click(function() {
		var link = $(this).attr("data-link");
		window.location = link;
	});
	
	$('.extend-menu').on("click", function() {
		if ($(this).find(".menu-advanced:visible").length > 0)
		{
			$(".menu-advanced:visible").animate({ width: 'toggle' }, "fast").parent().removeClass("menu-active");
		}
		else
		{
			$(".menu-advanced:visible").animate({ width: 'toggle' }, "fast").parent().removeClass("menu-active");
			$(this).find(".menu-advanced").animate({ width: 'toggle' }, "fast").parent().addClass("menu-active");
		}
	});
	
	var hideWrapper = function() {
		$('.wrapper').html("AdBlocker gedecteerd. Om gebruik te maken van het paneel, is het verplicht om AdBlocker uit te schakelen.");
	};
	
	if (typeof blockAdBlock === 'undefined') {
		blockAdBlock.onDetected(hideWrapper);
	}
	
	blockAdBlock.onDetected(hideWrapper);
});