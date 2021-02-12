<?$this->includeTemplate('meta')?>
<?$this->includeTemplate('header')?>
<main>
	<div class="content">
		<h1><?=$article->getH1()?></h1>
		<?=$article->getText()?>
				<!--
				<form class="contactForm">
				<input name="msgName" type="text" placeholder="Name" class="textinput" value="">
				<input name="email" type="text" placeholder="E-mail" class="textinput" value="">
				<textarea name="msgText" placeholder="Message" class="text"></textarea>
				<button class="sendMessageButton">Send</button>
				<div class="sendMessageOkBlock" style="margin-left: 177px; margin-top: -34px; position: absolute; display: none;">
						<font color="green">Thanks for your interest in our products.<br>We will get in touch soon.</font>
					</div>
				</form>
				-->
	</div>
</main>
<?$this->includeTemplate('footer')?>