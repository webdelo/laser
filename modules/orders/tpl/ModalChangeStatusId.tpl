<div class="modalChangeStautsId">
	<a class="overlay" id="status"></a>
	<div class="popup">
		<span class="zag">Смена статуса</span>
		<div class="pop-text">
			<span class="smena">Вы изменяете статус заказа</span>
			<span class="sogl"><?=$oldStatusName?></span> <span class="na">на</span> <span class="proizv"><?=$newStatusName?></span>
				<p>Выберите один из вариантов оповещения о смене статуса заказа</p>
				<a onclick="opcl(mydivs, '.box1'); return false;" class="modal1">
					<img src="/modules/orders/images/modal-letter.png">
						Оповестить клиента
				</a>
				<?if($order->getPartner()->getManagers()->count()):?>
				<a onclick="opcl(mydivs, '.box2'); return false;" class="modal2">
					<img src="/modules/orders/images/modal-partner.png">
						Оповестить клиента и партнера
				</a>
				<?endif?>
				<a onclick="opcl(mydivs, '.box3'); return false;" class="modal3 notSendStatusModalChanged">
						<img src="/modules/orders/images/modal-letter_none.png" class="second">
							Не отправлять оповещений
				</a>
				<div class="box1 toClient">
					<label>Примечание для клиента</label>
						<textarea class="clientsMessage"></textarea>
				</div>
				<div class="box2 toClientAndPartner">
					<form>
						<label>Примечание для клиента</label>
						<textarea class="clientsMessage"></textarea>
					</form>
					<form>
						<label>Примечание для партнера</label>
						<textarea class="partnersMessage"></textarea>
					</form>
				</div>
				<div class="box3"></div>
		</div>
		<div class="buttons">
			<a class="closeStatusIdModal" title="Отменить">Отменить</a>
			<a class="sendStatusModalChanged" title="Подтвердить">Подтвердить</a>
			<span class="sogl mailersSendedOk hide">отправлено успешно</span>
		</div>
	</div>
</div>