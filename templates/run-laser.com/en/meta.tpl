<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="icon" type="image/png" href="/favicon.ico" />

	<!-- Start: Meta Information -->
	<title><?=$this->getMetaTitle();?></title>
	<meta name="description" content="<?=$this->getMetaDescription();?>" />
	<meta name="keywords" content="<?=$this->getMetaKeywords();?>" />
	<!-- End: Meta Information -->
	
		<!-- Start: CSS files -->
	<?
	$this->getController('imploder')
		->css()
		->add('reset.css', '/css/RunLasers/')
		->add('style.css', '/css/RunLasers/')
		->add('newStyles.css', '/css/RunLasers/')
		->add('lightbox.css', '/css/RunLasers/')
		->add('jquery-ui-1.10.1.custom.min.css')
		->add('jquery.lightbox-0.5.css', '/admin/js/jquery/lightbox/css/')
		->printf('compact');
	?>
	<!-- End: CSS files -->
	<link rel="stylesheet" type="text/css" href="/admin/js/base/actions/styles/errors.css" />
	<!-- Start: JS files -->
	<?
	$js = $this->getController('imploder')->js();
	$js->add('jquery.js')
		->add('slides.min.jquery.js')
		->add('jquery-ui-1.10.1.custom.min.js','/js/extensions/')
		->add('feedbackHandler.js','/js/feedback/')
		->add('feedback.class.js','/js/feedback/')
		->add('ajaxLoader.class.js', '/js/')
		->add('allJS.js', '/js/base/')
		->add('jquery.gradienttext.js', '/js/base/')
		->add('jquery.measurer.js', '/js/base/')
		->add('lightbox.js', '/js/lightbox/')
		->add('errors.class.js','/admin/js/base/actions/')
		->add('error.class.js','/admin/js/base/actions/')
		->add('buttons.class.js','/admin/js/base/actions/')
		->add('simpleModal.js','/js/modal/')
		->add('quickOrder.class.js','/js/quickOrder/')
		->add('quickOrderHandler.js','/js/quickOrder/')
		->add('selects.class.js','/admin/js/base/actions/')
		->add('loader.class.js','/admin/js/base/actions/')
		->add('jquery.htmlFromServer.js','/admin/js/jquery/extensions/')
		->add('form.class.js','/admin/js/base/actions/')
		->add('jquery.lightbox.js','/admin/js/jquery/lightbox/')
		->add('jquery.inputmask.js','/admin/js/jquery/extensions/')
		->tagsPrint();
	?>
	<!-- End: JS files -->
	<script type="text/javascript" src="/js/base/languageHandler.js?v=1.1"></script>

</head>