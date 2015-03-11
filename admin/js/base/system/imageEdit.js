function hexToRgb(hex) {
    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(shorthandRegex, function(m, r, g, b) {
        return r + r + g + g + b + b;
    });

    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

$(function(){
	$('#colorholder').ColorPicker({
		color: function () {
			return '#'+$('#colorInput').val();
		},
		onShow: function (colpkr) {
			$(colpkr).fadeIn();
			$(colpkr).css('z-index','404');
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut();
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#colorInput').val(hexToRgb(hex).r + ',' + hexToRgb(hex).g + ',' + hexToRgb(hex).b);
		}
	});
	//$('#colorInput').inputmask("mask", {"mask": "999,999,999"});
});