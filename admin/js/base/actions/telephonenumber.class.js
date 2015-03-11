var telnumber = function (type) {
    this.type = type;
    this.checkdata = getdata;
	this.init = Initialize;
};

function getdata() {
	if (this.type === "tel.number")
		Initialize();
}

function Initialize() {
	$('.teledit').focus(function() {
    $('.teledit').inputmask("mask", {"mask": "+9 (999) 999-99-99 доб. 99999"});
	}).blur(function() {
    $(this).inputmask('remove');
	var targ = $(this).val();
	 
	if (targ.length > 11)
		targ = targ.replace(/(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})(\d{1})/, "+$1 ($2) $3-$4-$5 доб. $6");
	else if (targ.length === 11)
		targ = targ.replace(/(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})/, "+$1 ($2) $3-$4-$5");
	else if (targ.length > 9 && targ.length < 11)
		targ = targ.replace(/(\d{1})(\d{3})(\d{3})(\d{2})(\d{1})/, "+$1 ($2) $3-$4-$5");
	else if (targ.length > 7 && targ.length <= 9)
		targ = targ.replace(/(\d{1})(\d{3})(\d{3})(\d{1})/, "+$1 ($2) $3-$4");
	else if (targ.length > 4 && targ.length < 8)
		targ = targ.replace(/(\d{1})(\d{3})(\d{1})/, "+$1 ($2) $3");
	 
	 $('.teledit').val(targ);
    });
}