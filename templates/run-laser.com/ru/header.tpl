<body>
	<header>
		<div class="topGreenLine"></div>
		<div class="headerCenter container">
			<div class="mobile-nav"></div>
				<a class="logo-blk" href="<?php echo DIR_HTTP; ?>"><img src="/images/bg/logo.png" alt="" class="logo"></a>
					<div class="lang">
						<a href="https://run-laser.com" data-lng="ru" class="rus active">Ру</a>
						<a href="https://en.run-laser.com" data-lng="en" class="eng">En</a>
					</div>
						<div class="number-blk">
							<div class="number two">
								<a href="https://wa.me/79296108708?text=Run-Laser.Com"><img src="/images/social/whatsapp_32.png" alt=""></a>
								<a href="viber://chat?number=79296108708"><img src="/images/social/viber_32.png" alt=""></a>
								<span>+7 (929) 610 87 08</span>
							</div>							
								<div class="number"> 
									<a href="https://wa.me/79057723881?text=Run-Laser.Com"><img src="/images/social/whatsapp_32.png" alt=""></a>
									<a href="viber://chat?number=79057723881"><img src="/images/social/viber_32.png" alt=""></a>
									<span>+7 (905) 772 38 81</span>
								</div>
						</div>
							<div class="naw">
								<ul>
									<?$this->getController('Article')->getTopMenu()?>
								</ul>
							</div>
		</div>
	</header>
