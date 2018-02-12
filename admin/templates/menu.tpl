<div class="menu">
	<?if($this->checkUserRight('orders')):?>
		<?/*
		<a href="/admin/orders/" <?= $this->getREQUEST()['controller']=='orders' ? 'class="underline" ' : ''?>>Заказы</a>
		*/?>
	<?endif?>
	<?if($this->checkUserRight('orders')):?>
		<?/*
		<a href="/admin/catalog/" <?= $this->getREQUEST()['controller']=='catalog' ? 'class="underline" ' : ''?>>Каталог</a>
		*/?>
	<?endif?>
	<?if($this->checkUserRight('orders')):?>
		<?/*
		<a href="/admin/parameters/" <?= $this->getREQUEST()['controller']=='parameters' ? 'class="underline" ' : ''?>>Параметры</a>
		*/?>
	<?endif?>
	<?if($this->checkUserRight('orders')):?>
		<?/*
		<a href="/admin/properties/" <?= $this->getREQUEST()['controller']=='properties' ? 'class="underline" ' : ''?>>Опции</a>
		*/?>
	<?endif?>
	<?if($this->checkUserRight('complects')):?>
		<?/*
		<a href="/admin/complects/" <?= $this->getREQUEST()['controller']=='complects' ? 'class="underline" ' : ''?>>Комплекты</a>
		*/?>
	<?endif?>
	<?if($this->checkUserRight('partners')):?>
		<?/*
		<a href="/admin/partners/" <?= $this->getREQUEST()['controller']=='partners' ? 'class="underline" ' : ''?>>Партнеры</a>
		*/?>
	<?endif?>
	<?if($this->checkUserRight('clients')):?>
		<?/*
		<a href="/admin/clients/" <?= $this->getREQUEST()['controller']=='clients' ? 'class="underline" ' : ''?>>Клиенты</a>
		*/?>
	<?endif?>
		<?if($this->checkUserRight('articles')):?>
		<a href="/admin/articles/" <?= $this->getREQUEST()['controller']=='articles' ? 'class="underline" ' : ''?>>Статьи</a>
	<?endif?>
	<?if($this->checkUserRight('promoCodes_controller')):?>
		<?/*
		<a href="/admin/promoCodes/" <?= $this->getREQUEST()['controller']=='promoCodes' ? 'class="underline" ' : ''?>>Промо-коды</a>
		*/?>
	<?endif?>
	<?if($this->checkUserRight('seo')):?>
		<a href="/admin/seo/" <?= $this->getREQUEST()['controller']=='seo' ? 'class="underline" ' : ''?>>SEO</a>
	<?endif?>
	<?if($this->checkUserRight('modulesDomain')):?>
		<?/*
		<a href="/admin/modulesDomain/" <?= $this->getREQUEST()['controller']=='modulesDomain' ? 'class="underline" ' : ''?>>Модули-Домены</a>
		*/?>
	<?endif?>
	<?if($this->checkUserRight('deliveries_controller')):?>
		<?/*
		<a href="/admin/deliveries/" <?= $this->getREQUEST()['controller']=='deliveries' ? 'class="underline" ' : ''?>>Доставки</a>
		*/?>
	<?endif?>
	<?if($this->checkUserRight('offers_controller')):?>
		<?/*
		<a href="/admin/offers/" <?= $this->getREQUEST()['controller']=='offers' ? 'class="underline" ' : ''?>>Акции</a>
		*/?>
	<?endif?>
	<?if($this->checkUserRight('settings')):?>
		<a href="/admin/settings/" <?= $this->getREQUEST()['controller']=='settings' ? 'class="underline" ' : ''?>>Настройки</a>
	<?endif?>
	<?if($this->checkUserRight('administrators')):?>
		<a href="/admin/administrators/" <?= $this->getREQUEST()['controller']=='administrators' ? 'class="underline" ' : ''?>>Администраторы</a>
	<?endif?>
	<?if($this->checkUserRight('measures')):?>
		<a href="/admin/measures/" <?= $this->getREQUEST()['controller']=='measures' ? 'class="underline" ' : ''?>>Единицы измерения</a>
	<?endif?>
</div>
<div class="more">
	<span href="#" class="toggleMoreMenu">Ещё</span>
	<div class="more-list">
		<div class="black-arrow"></div>
	</div>
</div>