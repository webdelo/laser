$(function(){
	(new htmlCatalogPlugin()).resizeBlocks();
});

var htmlCatalogPlugin = function()
{
	this.getMaxHeight = function(){
		return Math.max.apply(Math, $(".cat_item").map(function(){
		  return $(this).height()
		}).get());
	}
	
	this.resizeBlocks = function(){
		var that = this;
		$(".cat_item").each(function(k,w){
			$(w).height(that.getMaxHeight());
		});

		$(".cat_item .description").each(function(k,w){
			$(w).css("position","absolute");
			$(w).css("bottom","0");
			$(w).css("left","0");
			$(w).css("right","0");
		});	
	}
}

