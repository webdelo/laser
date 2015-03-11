$(function(){
	(new offerHandler())
		.onChangeType();
});

var offerHandler = function()
{
	this.onChangeType = function()
	{
		$('select[name=type]').live('change',function(){
			var type = $(this + ':selected').val();
			$('.type').hide();
			$('input[name=' + type + ']').show();
		});
		return this;
	}
}