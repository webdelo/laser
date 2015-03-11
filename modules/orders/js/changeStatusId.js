$(function(){
	mydivs=new Array('.box1','.box2','.box3','.box4','.box5');
	statusIdChangeHandler();
	statusIdPartnerChangeHandler();
});

var statusIdChangeHandler = function()
{
	var select = '[name=statusId]';
	globlalStatusData =[];

	$(select).live('mouseenter', function(){
		globlalStatusData.oldStatusId = $(select).val();
		globlalStatusData.oldStatusName = $(select + ' :selected').text();
	});

	$(select).live('change', function(){
		globlalStatusData.newStatusId = $(select).val();
		globlalStatusData.newStatusName = $(select + ' :selected').text();
		showModalChangeStatusId();
	});

	$('.closeStatusIdModal').live('click', function(){
		$('#statusId0').html(globlalStatusData.oldStatusName);
		$(select + ' [value="' + globlalStatusData.oldStatusId + '"]').attr("selected", "selected");
		$('.modalChangeStautsId').remove();
	});

	$('.sendStatusModalChanged').live('click', function(){
		sendMessages();
	});

	$('.notSendStatusModalChanged').live('click', function(){
		ajaxChangeStatusId();
		$('.modalChangeStautsId').remove();
	});
};

var opcl = function(arr, e){
	if ($(e).css('display') == 'none'){
		for(var i in arr){
		   $(arr[i]).hide();
		}
		$(e).show();
	}
	$('.sendStatusModalChanged').css('display', 'inline-block');
}

var showModalChangeStatusId = function()
{
	var data ={
			'orderId' : $('.objectId').val(),
			'oldStatusName':   globlalStatusData.oldStatusName,
			'newStatusName':   globlalStatusData.newStatusName
		};
	$.ajax({
		before: $('.statusIdLoader').show(),
		url: '/admin/orders/ajaxGetModalChangeStatusId/',
		type: 'POST',
		dataType: 'html',
		data: data,
		success: function(data){
			$('.statusIdLoader').hide();
			$('.root').before(data);
		}
	});
}

var ajaxChangeStatusId = function()
{
	var editOrderSelectStatusId = new selects;
	editOrderSelectStatusId
		.setSettings({'element' : '.editOrderSelectStatusId', 'event': 'change' })
		.setCallback(function (response) {
			if ( typeof response !== 'number' )
				alert(response);
		})
		.init();

	$('.editOrderSelectStatusId').trigger('change');
	editOrderSelectStatusId.cancelInit();
};

var sendMessages = function()
{
	var myAjaxLoader = new ajaxLoader();
	var data ={
			'orderId' : $('.objectId').val(),
			'toClient' : $('.toClient:visible').length > 0,
			'toClientAndPartner' : $('.toClientAndPartner:visible').length > 0,
			'clientsMessage' : $('.clientsMessage:visible').val(),
			'partnersMessage' : $('.partnersMessage:visible').val(),
			'oldStatusName':   globlalStatusData.oldStatusName,
			'newStatusName':   globlalStatusData.newStatusName
		};
	$.ajax({
		before: myAjaxLoader.setLoader('.buttons'),
		url: '/admin/orders/ajaxSendChangeStatusIdMessages/',
		type: 'POST',
		dataType: 'html',
		data: data,
		success: function(data){
			myAjaxLoader.getElement();
			if(data == 1)
				ajaxChangeStatusId();
			else
				alert(data);
			$('.buttons a').hide();
			$('.mailersSendedOk').fadeIn('slow');
			$('.mailersSendedOk').fadeOut(3500);
			setTimeout(function() {
				$('.modalChangeStautsId').remove();
			}, 3500);
		}
	});
}

var statusIdPartnerChangeHandler = function()
{
	var button = '.changeStatusIdPartner';
	$(button).live('click', function(){
		if (confirm('Вы действительно хотите изменить статус заказа?')){
			var myAjaxLoader = new ajaxLoader();
			var data ={
					'orderId' : $('.objectId').val(),
					'statusId' : $(button).attr('data-id')
				};
			$.ajax({
				before: myAjaxLoader.setLoader(button),
				url: '/admin/orders/ajaxPartnerEditOrderStatusId/',
				type: 'POST',
				dataType: 'json',
				data: data,
				success: function(data){
					myAjaxLoader.getElement();
					if(data){
						$('.herePartnerChangeStatusBlock').html(data.statusBlock);
						$('#statusId0').html(data.status);
					}
				}
			});
		}
	});
}