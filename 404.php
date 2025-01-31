<?php get_header(); ?>

<div class="contents__head">
	KVなど
	<?php include("template-parts/kv.php"); ?>
	<div class="breadcrumb contents__breadcrumb">
		<?php echo get_breadcrumb_list(); ?>
	</div>
	<h1 class="contents__heading" data-title='NOT FIND'>このページは存在しません。</h1>
</div>

<div class="contents__body page-404">
	<div class="page-404__head">
		<h2 class="page-404__lead lead">申し訳ありません。<br>こちらのページはURLが変更になったか削除になった可能性があります。</h2>
	</div>
	<div class="page-404__body">
		<div class="page-404__text">
			<p>お手数ですが、TOPページから閲覧くださいますようお願いいたします。</p>
		</div>
	</div>
	<div class="page-404__foot">
		<div class="page-404__button">
			<a href="<?php home_url() ?>">TOPへ戻る</a>
		</div>
	</div>
</div>

<div class="contents__foot">
	CVボタンなど
</div>


<?php get_footer(); ?>