</main><!-- /main.contents -->

<!-- フッター -->
<footer class="footer">
	<div class="footer__head">
	</div>
	<div class="footer__body">
	</div>
	<div class="footer__foot">
	</div>
</footer>
<!-- /フッター -->

</div><!-- /container -->

<?php wp_footer(); ?>

<!--JS-->
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/base.js?<?php echo filemtime(get_template_directory() . '/assets/js/base.js'); ?>"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/menu_responsive.js?<?php echo filemtime(get_template_directory() . '/assets/js/menu_responsive.js'); ?>"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/sp_responsive.js?<?php echo filemtime(get_template_directory() . '/assets/js/sp_responsive.js'); ?>"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/slider.js?<?php echo filemtime(get_template_directory() . '/assets/js/slider.js'); ?>"></script>
<!-- slick -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js" media="print" onload="this.media='all'"></script>
</body>

</html>