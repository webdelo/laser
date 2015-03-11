
<?include(TEMPLATES_ADMIN.'top.tpl');?>
			<tr>
				<td>
					<div class="action-area top">
						<span class="left">
							<ul id="breadcrumbs">
								<li><a class="home" href="/admin/"></a></li>
								<li class="end"><h1>System Summary</h1></li>
							</ul>
						</span>
					</div>
				</td>
			</tr>
			<tr>
				<td id="main-content" align="center">
					<div class="column parts left">
						<form class="search">
							<input type="text" name="search">
							<button>Go</button>
						</form>
						<div class="preview">
							<div class="preview-header">
								<a href="/admin/articles/articles/">Articles</a>
								<span><a href="/admin/articles/articles/">details</a></span>
							</div>
							<div class="preview-content">
								<table>
									<tr class="title">
										<td>#</td>
										<td>Title</td>
										<td>Alias</td>
										<td>Category</td>
										<td>Date</td>
									</tr>
									<tr>
										<td>1</td>
										<td><a href="/admin/articles/article/23">News</a></td>
										<td>news</td>
										<td><a href="/admin/articles/categories">Menu</a></td>
										<td>23/03/2012</td>
									</tr>
									<tr>
										<td>2</td>
										<td><a href="/admin/articles/article/24">Catalog</a></td>
										<td>catalog</td>
										<td><a href="/admin/articles/categories">Menu</a></td>
										<td>23/03/2012</td>
									</tr>
									<tr>
										<td>3</td>
										<td><a href="/admin/articles/article/23">News</a></td>
										<td>news</td>
										<td><a href="/admin/articles/categories">Menu</a></td>
										<td>23/03/2012</td>
									</tr>
									<tr>
										<td>4</td>
										<td><a href="/admin/articles/article/24">Catalog</a></td>
										<td>catalog</td>
										<td><a href="/admin/articles/categories">Menu</a></td>
										<td>23/03/2012</td>
									</tr>
									<tr>
										<td>5</td>
										<td><a href="/admin/articles/article/23">News</a></td>
										<td>news</td>
										<td><a href="/admin/articles/categories">Menu</a></td>
										<td>23/03/2012</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="preview">
							<div class="preview-header">
								<a href="/admin/files/">Files</a>
								<span><a href="/admin/catalog/">details</a></span>
							</div>
							<div class="preview-content">
								<table>
									<tr class="title">
										<td>#</td>
										<td>Title</td>
										<td>Size</td>
										<td>Category</td>
										<td>Date</td>
									</tr>
									<tr>
										<td>1</td>
										<td><a href="/admin/files/file/23">Kak_spasti_zemlu.doc</a></td>
										<td>2GB</td>
										<td><a href="/admin/files/categories">NLO</a></td>
										<td>23/03/2012</td>
									</tr>
									<tr>
										<td>2</td>
										<td><a href="/admin/files/file/24">Kak_zahvatit_planetu.doc</a></td>
										<td>2.1GB</td>
										<td><a href="/admin/files/categories">NLO</a></td>
										<td>23/03/2012</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="preview">
							<div class="preview-header">
								<a href="/admin/catalog/">Catalog</a>
								<span><a href="/admin/catalog/">details</a></span>
							</div>
							<div class="preview-content">
								<table>
									<tr class="title">
										<td>#</td>
										<td>Title</td>
										<td>Alias</td>
										<td>Category</td>
										<td>Date</td>
									</tr>
									<tr>
										<td>1</td>
										<td><a href="/admin/catalog/item/23">Tank</a></td>
										<td>tank</td>
										<td><a href="/admin/catalog/categories">armor</a></td>
										<td>23/03/2012</td>
									</tr>
									<tr>
										<td>2</td>
										<td><a href="/admin/catalog/item/24">Fighter</a></td>
										<td>fighter</td>
										<td><a href="/admin/catalog/categories">armor</a></td>
										<td>23/03/2012</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div class="column left">

					</div>
				</td>
			</tr>
		</table>
		
		<script>
			$(function() {
				$( ".column" ).sortable({ handle: '.preview-header', connectWith: ".column" });
				$( ".column" ).sortable( "option", "containment", '#main-content' );
				
				$( ".preview" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
					.find( ".preview-header" )
						.addClass( "ui-widget-header ui-corner-all" )
						.end();

				$( ".column" ).disableSelection();
			});
		</script>
<?php include(TEMPLATES_ADMIN.'footer.tpl')?>