<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="ru">
		<meta name="Description" content="">
		<meta name="Keywords" content="">
		<link href="/admin/images/ico/favicon.png" rel="shortcut icon" type="image/x-icon" />
		<meta name="Revisit-After" content="15 days">
		<title>Webdelo Administration Panel</title>
		<link rel="stylesheet" type="text/css" href="<?=DIR_ADMIN_HTTP?>css/style.css">
		<link rel="stylesheet" type="text/css" href="<?=DIR_ADMIN_HTTP?>js/jquery/ui/skins/blueFlat/ui.blueFlat.my.css" />
		<link rel="stylesheet" type="text/css" href="<?=DIR_ADMIN_HTTP?>js/base/actions/styles/errors.css" />
		
		<!-- start; Подключение карт -->
		<script type="text/javascript"
			src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPUe02R4VteQsZZIzrdoyLIn2S36aoYZc&sensor=false">
		</script>
		<!-- end; Подключение карт -->

		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/jquery/jquery.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/jquery/jquery.actual.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/jquery/jquery.textchange.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/jquery/ui/ui.min.js"></script>
		
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/jquery/timePicker/jquery.timePicker.js"></script>
		<link rel="stylesheet" type="text/css" href="/admin/js/jquery/timePicker/timePicker.css" />
		
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/jquery/extensions/jquery.autoClose-1.0.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/jquery/extensions/jquery.autoScroll-1.0.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/jquery/extensions/jquery.transformer.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/jquery/extensions/jquery.formTransformer.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/jquery/extensions/jquery.deparam.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/jquery/extensions/jquery.loaderMini-1.0.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/jquery/extensions/jquery.htmlFromServer.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/jquery/extensions/jquery.loaderMini-1.0.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/jquery/extensions/jquery.tap.js"></script>

		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/base/system/errorHandler.class.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/base/system/templateControls.js"></script>

		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/temp/ui-test.js"></script>
		<!--<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/base/system/groupDialog.js"></script>-->
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/base/system/groupSort.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/base/actions/loaderLight.class.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/base/actions/loader.class.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/base/actions/loaderBlock.class.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/base/actions/errors.class.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/base/actions/error.class.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/base/actions/form.class.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/base/actions/buttons.class.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/base/actions/selects.class.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/base/actions/selectsFalls.class.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/base/actions/inputs.class.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/base/actions/ajaxModal.class.js"></script>
		<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/base/actions/maps.class.js"></script>
		
		<script type="text/javascript" src="/admin/js/jquery/extensions/jquery.inputmask.js"></script>
		<script type="text/javascript" src="/admin/js/base/system/js.js"></script>
		<script type="text/javascript" src="/admin/js/base/system/moreMenuHandler.js"></script>

		<script type="text/javascript" src="/admin/js/jquery/lightbox/jquery.lightbox.js"></script>
		<link rel="stylesheet" type="text/css" href="/admin/js/jquery/lightbox/css/jquery.lightbox-0.5.css" />
		
		<script type="text/javascript" src="/admin/js/jquery/colorPicker/colorPicker.js"></script>
		<link rel="stylesheet" type="text/css" href="/admin/js/jquery/colorPicker/colorPicker.css" />
		<script>
			$(function() {
				$('.lightbox').lightBox();
				$( "#lightbox-nav" ).live( "mouseover", function() {
					if( $('.lightbox').hasClass('noNextPrev') ){
						$('#lightbox-nav-btnPrev').hide();
						$('#lightbox-nav-btnNext').hide();
					}
				  });
			});
                        
                        window.controller = "<?=$this->getREQUEST()['controller']?>";
		</script>
	</head>