<?php get_header(); ?>

<div class="contents__head">
	KVなど
	<?php include("template-parts/kv.php"); ?>
	<div class="breadcrumb contents__breadcrumb">
		<?php echo get_breadcrumb_list(); ?>
	</div>
	<h1 class="contents__heading" data-title='ENGLISH'>タイトル</h1>
</div>

<div class="contents__body page">
	<div class="page__head">FVから</div>
	<div class="page__body">
		<?php the_content(); ?>
	</div>
	<div class="page__foot"></div>
</div>

<div class="contents__foot">
	CVボタンなど
</div>

<?php get_footer(); ?>