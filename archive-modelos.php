<?php get_header(); ?>

<main class="nil-archive-modelos">

	<header class="nil-archive-header">
		<h1 class="nil-archive-title fw-bold"><?php post_type_archive_title(); ?></h1>
	</header>

	<div class="nil-models-grid">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<a href="<?php the_permalink(); ?>" class="nil-model-card">
				<div class="nil-model-thumb">
					<?php the_post_thumbnail( 'large' ); ?>
					<div class="nil-model-overlay">
						<span class="nil-model-name"><?php the_title(); ?></span>
					</div>
				</div>
			</a>
		<?php endwhile; endif; ?>
	</div>

	<?php the_posts_pagination( array(
		'prev_text' => '&larr;',
		'next_text' => '&rarr;',
	) ); ?>

</main>

<?php get_footer(); ?>
