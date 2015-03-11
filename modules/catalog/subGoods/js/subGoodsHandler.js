$(function(){
	(new subGoodsHandler())
		.clickAddSubGoodButton()
		.clickDeleteSubGoodButton()
		.initSubGoodAutosuggest();
});

var subGoodsHandler = function()
{
	this.sources = {
		'addSubGoodButton' : '#addSubGood',
		'subGoodId' : 'input[name=subGoodId]',
		'subGoodQuantity' : 'input[name=subGoodQuantity]',
		'goodsTableBlock' : '.goodsTable',
		'deleteSubGoodButton' : '.deleteSubGood'
	};

	this.ajaxLoader = new ajaxLoader();

	this.clickAddSubGoodButton = function()
	{
		var that = this;
		$(that.sources.addSubGoodButton).live('click',function(){
			(new subGoods).addSubGood(this);
		});
		return this;
	};

	this.clickDeleteSubGoodButton = function()
	{
		var that = this;
		$(that.sources.deleteSubGoodButton).live('click',function(){
			(new subGoods).deleteSubGood(this);
		});
		return this;
	};

	this.initSubGoodAutosuggest = function()
	{
			$('.inputSubGoodId').autoSuggest('/admin/orderGoods/ajaxGetAutosuggestGoods/', {
				selectedItemProp: "name",
				searchObjProps: "name",
				targetInputName: 'subGoodId',
				secondItemAtribute: 'code',
				thirdItemAtribute: 'price',
				fifthItemAtribute: 'availability'
			});
	}
};