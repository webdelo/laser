<div id="header">
	<a href="/admin/"><img src="/admin/images/logo/studio.png" height="40px" /></a>
	
	<span class="user">
		<img src="/admin/images/ico/nobody.png" height="24"/>
		<?=$this->getAuthorizatedUser()->getUserName()?>
	</span>
	
	<div class="logout">
		<a href="/admin/?logout=1" class="exit">
			<span>Выход</span>
		</a>
	</div>
	
	<?php include(TEMPLATES_ADMIN.'menu.tpl')?>
</div>