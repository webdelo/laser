<?include(TEMPLATES_ADMIN.'top.tpl');?>
        <div class="main">
        	<div class="max_width">
            	<div class="list">
			<div class="col_in">
			</div>
		</div><!--list-->

                <div class="action">
			<?if($this->checkUserRight('article_add')):?>
			<div class="box">
				<a href="/admin/articles/article/"><img src="/admin/images/buttons/add_article.png" alt="" /><span>Добавить статью</span></a>
			</div>
			<?endif?>
			<?if($this->checkUserRight('articles')):?>
			<div class="box">
				<a href="/admin/articles/"><img src="/admin/images/buttons/edit_articles.png" alt="" /><span>Редактировать <br />статьи</span></a>
			</div>
			<?endif?>
			<?if($this->checkUserRight('settings')):?>
			<div class="box">
			<a href="/admin/settings/"><img src="/admin/images/buttons/edit_settings.png" alt="" /><span>Настройки</span></a>
			</div>
			<?endif?>
                </div>
                <div class="clear"></div>
            </div>
        </div><!--main-->

        <div class="vote"></div>
    </div><!--root-->
<?include(TEMPLATES_ADMIN.'footer.tpl');?>