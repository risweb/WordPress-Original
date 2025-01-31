<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<meta name="format-detection" content="telephone=no" />
	<?php if (is_singular() && pings_open(get_queried_object())) : ?>
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"><?php endif; ?>
	<?php wp_head(); ?>
	<!--CSS-->
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css?<?php echo filemtime(get_template_directory() . '/style.css'); ?>">
	<!-- GTAG -->
</head>

<body class="page-<?php echo get_post($wp_query->post->ID)->post_name; ?>">

	<div id="container">
		<!-- ページヘッダ -->
		<header class="header">
			<div class="header__container">
				<?php if (is_front_page()) : ?>
					<h1 class="header__logo">
						<a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/logo.svg" alt="<?php bloginfo('name'); ?>" decoding="async" loading="lazy"></a>
					</h1>
				<?php else : ?>
					<div class="header__logo">
						<a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/logo.svg" alt="<?php bloginfo('name'); ?>" decoding="async" loading="lazy"></a>
					</div>
				<?php endif; ?>
				<div class="js-nav-area header__nav-area" id="navigation">
					<?php
					wp_nav_menu(array(
						'theme_location' => 'main-menu',
						'container'       => 'nav',
						'items_wrap' => '<ul class="global-navigation__list">%3$s</ul>',
						'container_class' => 'global-navigation',
						'container_id' => 'js-global-navigation',
					));
					?>
					<div id="js-focus-trap" tabindex="0"></div>
				</div>
				<!-- ハンバーガーメニュー -->
				<button id="js-humberger" type="button" class="humberger header__humberger" aria-controls="navigation" aria-expanded="false">
					<span class="humberger__text"></span>
					<span class="humberger__line"></span>
				</button>
			</div>
		</header>
		<!-- /ページヘッダ -->

		<!-- main -->
		<main class="contents <?php if (is_front_page()) : ?> _home<?php endif; ?>">