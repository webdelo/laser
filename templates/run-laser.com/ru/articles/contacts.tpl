<?$this->includeTemplate('meta')?>
<?$this->includeTemplate('header')?>
<main>
	<div class="content">
		<h1><?=$article->getH1()?></h1>
		<?=$article->getText()?>
				<form class="contactForm">
				<input name="msgName" type="text" placeholder="Имя" class="textinput" value="">
				<input name="email" type="text" placeholder="E-mail" class="textinput" value="">
				<textarea name="msgText" placeholder="Сообщение" class="text"></textarea>
				<button class="sendMessageButton">Отправить</button>
				<div class="sendMessageOkBlock" style="margin-left: 177px; margin-top: -34px; position: absolute; display: none;">
						<font color="green">Спасибо за оставленное сообщение.<br>Наши менеджеры свяжутся с вами в ближайшее время.</font>
					</div>
				</form>
	</div>
</main>
<?$this->includeTemplate('footer')?>