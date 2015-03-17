<link rel="stylesheet" type="text/css" href="/modules/images/css/style.css" />
<link rel="stylesheet" type="text/css" href="/admin/js/jquery/uploadify/uploadifive.css" />
<script type="text/javascript" src="/admin/js/jquery/uploadify/jquery.uploadifive-v1.0.js"></script>
<script type="text/javascript" src="/modules/images/js/imagesHandler.js"></script>
<script type="text/javascript" src="/modules/images/js/images.js"></script>
<input type="hidden" class="mainController" value="<?=$this->getCurrentController()?>" />

<?if( ! $this->isAuthorisatedUserAnManager()):?>
<div id="placeForDrugAndDrop" class="drop_window">
	<input type="file" name="file_upload" id="file_upload" />
	<p class="add"></p>
	<p>Вы можете выбрать 1 или несколько фото<br /> зажав кнопку ctrl, за один раз</p>
	<p class="uppercase">ИЛИ</p>
	<p>Перетяните сюда фото <br />прямо с вашего компьютера</p>
	<p><img src="/admin/images/backgrounds/browser.png" alt="" /></p>
</div>
<div class="clearfix"></div>
<?endif?>

<div class="newImages" data-title="Добавить фото" data-action="/admin/<?=$this->getREQUEST()['controller']?>/uploadImage/">
    <?$this->getImagesBlock($object->id)?>
</div>
<div class="clearfix"></div>

<div class="download_images">
	<div class="imagesList">
		    <?$this->getImagesListBlock($object->id)?>
	</div>
</div>
