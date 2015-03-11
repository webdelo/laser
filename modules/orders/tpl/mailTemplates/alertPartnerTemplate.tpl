<?include(DIR.'/modules/orders/tpl/mailTemplates/alertPartnerBase.tpl')?>

<br /><br />
<u>Важная дополнительная информация:</u>
	<br /><br />
<textarea class="aditionalMessage alertPartnerTemplate" style="width: 500px; height: 80px;"></textarea>

<div class="clear"></div>

<br /><br />
<div style="float: left;">
	<link rel="stylesheet" type="text/css" href="/admin/js/jquery/tree/styles/jQuery.Tree.css" />
	<script type="text/javascript" src="/admin/js/jquery/tree/jQuery.Tree.js"></script>
	 <script type="text/javascript">
		$(document).ready(function(){
			$('.tree ul:first').Tree();
			$('.jquery-tree-expandall').trigger( "click" );
		});
	</script>
	<div class="table_edit">
		<div class="left tree checkboxes" style="float: none;">
			<ul>
				<li>
					<label>
						Менеджеры партнера
					</label>
					<ul>
						<?
						if($order->getPartner()->getManagers()->count()):
						foreach($order->getPartner()->getManagers() as $manager):
						?>
						<li>
							<label class="manager">
								<input type="checkbox" class="managers alertPartnerTemplate" value="<?=$manager->getLogin()?>"> <?=$manager->name?> (<?=$manager->getLogin()?>)
							</label>
						</li>
						<?endforeach; endif?>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>

<div class="clear"></div>

Выслать копию оповещения администратору сайта ( <?=$adminEmail?> ) - <input type="checkbox" checked class="copyToAdmin" />