<?php get_header(); ?>

<div class="contents__head">
	KVなど
	<?php include("template-parts/kv.php"); ?>
	<div class="breadcrumb contents__breadcrumb">
		<?php echo get_breadcrumb_list(); ?>
	</div>
	<h1 class="contents__heading" data-title='ENGLISH'>タイトル</h1>
</div>

<div class="contents__body archive">
	<div class="archive__head"></div>
	<div class="archive__body">

		<ul class="post-news__list archive__post">
			<?php if (have_posts()) : ?>
				<?php while (have_posts()) : the_post(); ?>

					<li>
						<a href="<?php the_permalink(); ?>">
							<figure class="post-news__pic">
								<?php the_post_thumbnail('medium'); ?>
							</figure>
							<div class="post-news__detail">
								<div class="post-news__meta">
									<time class="post-news__time" datetime="<?php echo get_the_date('Y-m-d'); ?>">
										<?php echo get_the_date('Y.m.d'); ?></time>
									<div class="post-news__term">
										<?php
										$category = get_the_category();
										echo $category[0]->cat_name;
										?>
									</div>
								</div>
								<h4 class="post-news__title">
									<?php the_title(); ?>
								</h4>
							</div>
						</a>
					</li>

				<?php endwhile; ?>
			<?php endif; ?>
		</ul>

	</div>
	<div class="archive__foot">
		<div class="archive__pagination pagination">
			<?php
			$args = array(
				'mid_size' => 1,
				'prev_text' => '<span class="pagination__arrow _prev"></span>',
				'next_text' => '<span class="pagination__arrow _next"></span>',
				'screen_reader_text' => ' ',
			);
			the_posts_pagination($args);
			?>
		</div>
	</div>
</div>

<div class="contents__foot">
	CVボタンなど
</div>

<?php get_footer(); ?>