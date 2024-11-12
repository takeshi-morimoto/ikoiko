<?php 
	include_once( dirname(__FILE__) . '/../../wp-load.php' );
	$posts = get_posts( array( 'category' => 12, ) );
?>
<section id="newArticle">
	<div class="inner">
		<h2></span>最新ニュース</h2>
		<?php if (! $posts){ echo "<p>記事はまだ１件もありません。</p>"; } ?>
		<ul>
			<?php 
			global $post;
			foreach ($posts as $post): ?>
					<?php 
						setup_postdata($post);
						$thumbId = get_post_thumbnail_id();
						$postedAt = get_the_date('Y/n/j');
						$imgUri = wp_get_attachment_image_src( $thumbId, 'medium' );
						$categories = get_the_category();

					?>
					<li class='row'>
						<a href='<?php the_permalink(); ?>'>
						<section class='clearfix'>
							<div class='right'>
								<ul class="categories">
										<?php foreach( $categories as $cat ): ?>
											<li class="category cat_<?= $cat->term_id ?>"><?= $cat->name ?></li>	
										<?php endforeach; ?>
								</ul>
								<div class="postedAt"><?= $postedAt ?></div>
								<div class="title"><?php the_title(); ?></div>
							</div>
							<div class='left'>
								<img src='<?= $imgUri[0] ?>' alt=''>
							</div>
						</section>
						</a>
					</li>
			<?php endforeach;?>
		</ul>
	</div>
</section>