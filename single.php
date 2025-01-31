<?php get_header(); ?>

<div class="contents__head">
	KVなど
	<?php include("template-parts/kv.php"); ?>
	<div class="breadcrumb contents__breadcrumb">
		<?php echo get_breadcrumb_list(); ?>
	</div>
	<!-- <h1 class="contents__heading" data-title='ENGLISH'>タイトル</h1> -->
</div>

<div class="contents__body single">
	<div class="single__head">
		<h1 class="single__heading">
			<?php the_title(); ?>
		</h1>
		<!-- メタデータ -->
		<div class="single__meta">
			<time class="single__time" datetime="<?php echo get_the_date('Y-m-d'); ?>">
				<?php echo get_the_date('Y年m月d日'); ?>
			</time>
		</div>
	</div>
	<div class="single__body format">
		<?php the_content() ?>
	</div>
	<div class="single__foot"></div>
</div>

<div class="contents__foot">
	CVボタンなど
</div>

<?php get_footer(); ?>