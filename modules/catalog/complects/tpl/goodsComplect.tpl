<?if(isset($complect->id)):?>
<?$this->getController('ComplectGoods')->getComplectGoodsTableByComplectId($complect->id)?>
<?else:?>
Введите основные параметры заказа, чтобы потом иметь возможность добавлять товары.
<?endif?>