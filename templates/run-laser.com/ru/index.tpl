<?$this->includeTemplate('meta')?>
<?$this->includeTemplate('header')?>

<?foreach ($articles as $article):?>
	<?=$article->getText()?>
<?endforeach?>

<?$this->includeTemplate('footer')?>