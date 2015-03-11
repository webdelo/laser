$(function(){
	function getMaxHeight(){
		return Math.max.apply(Math, $(".post").map(function(){
		  return $(this).height()
		}).get());
	}

	$(".post").each(function(k,w){
		$(w).height(getMaxHeight());
	});
});