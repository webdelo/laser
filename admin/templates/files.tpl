<link rel="stylesheet" type="text/css" href="/modules/files/css/style.css" />
<script type="text/javascript" src="/modules/files/js/filesHandler.js"></script>
<script type="text/javascript" src="/modules/files/js/files.js"></script>
<script type="text/javascript" src="/admin/js/jquery/uploadify/jquery.uploadifive-v1.0_files.js"></script>
<div class="main_edit">
	<div class="list_files">
		<div class="col_in">
			<p class="title">Список файлов:</p>
			<div class="filesList">
				<?$this->getFilesListBlock($object->id)?>
			</div>
		</div>
	</div>

	<div class="dop_param">
		<div class="col_in">
			<input type="hidden" class="mainControllerFiles" value="<?=$this->getCurrentController()?>" />
			<div id="placeForDrugAndDropFiles" class="drop_window_files">
				<input type="file" name="file_upload" id="file_uploadFile" />
				<p class="add"></p>
				<p>Вы можете выбрать 1 или несколько фото<br /> зажав кнопку ctrl, за один раз</p>
				<p class="uppercase">ИЛИ</p>
				<p>Перетяните сюда фото <br />прямо с вашего компьютера</p>
				<p><img src="/admin/images/backgrounds/browser.png" alt="" /></p>
			</div>
		</div>
	</div><!--dop_param-->

	<div class="newFiles" data-title="Добавить файлы" data-action="/admin/<?=$this->getREQUEST()['controller']?>/uploadFile/">
		<?$this->getFilesBlock($object->id)?>
	</div>

	<div class="clear"></div>
</div>