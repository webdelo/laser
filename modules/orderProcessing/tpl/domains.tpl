<script type="text/javascript">
	$(function(){
		var order = new orderProcessing;
		order.radioMonitoring();
	});
</script>
Домен: 
<? foreach( $moduleDomains as $domain ): ?>
	<input 
		id="<?=$domain?>" 
		type="radio" 
		name="domain" 
		value="<?=$domain?>"
		class="radioBox"
		data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>"

		<?=($order->domain==$domain)?'checked':''?>
	>
	<label for="<?=$domain?>"><?=$domain?></label>
<? endforeach; ?>