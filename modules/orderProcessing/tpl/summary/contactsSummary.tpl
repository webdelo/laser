Контактное лицо: <?=$order->surname?> <?=$order->name?> <?=$order->patronimic?> <br />
Домашний: <?=$order->phone?> <br />
Мобильный: <?=$order->mobile?$order->mobile:' - - - '?> <br />
E-mail: <?=$order->email?$order->email:' - - - '?> <br />
<? if ( $order->company ) : ?>
Название компании: <?=$order->company?><br />
<? endif; ?>