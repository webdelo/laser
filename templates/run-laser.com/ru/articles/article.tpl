<?$this->includeTemplate('meta')?>
<?$this->includeTemplate('header')?>
<main>
	<div class="content">
		<h1><?=$article->getH1()?></h1>
		<?=$article->text?>
	</div>
</main>
<?$this->includeTemplate('footer')?>