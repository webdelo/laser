<?$this->includeTemplate('meta')?>
<?$this->includeTemplate('header')?>
<main>
	<div class="content">
		<h1><?=$article->getH1()?></h1>
		<?if ($showImages): ?>
		<div class="image-list">
			<? foreach($article->getImagesByCategoryAndStatus('2', 1) as $image ): ?>
				<a href="<?=$image->getFocusImage('0x0')?>" data-lightbox="gallery"><img src="<?=$image->getFocusImage('175x98')?>" alt=""/></a>
			<? endforeach; ?>
		</div>
		<?endif?>
		<?=$article->getText()?>
	</div>
</main>
<?$this->includeTemplate('footer')?>