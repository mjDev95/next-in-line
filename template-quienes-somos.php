<?php
/**
 * Template Name: Quienes Somos
 *
 * @package HelloElementorChild
 */

get_header();
?>

<main class="nil-simple-page-wrapper py-xl">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                    <h1 class="h2 text-uppercase text-center mb-lg"><?php the_title(); ?></h1>

                    <div class="nil-page-content">
                        <?php the_content(); ?>
                    </div>

                <?php endwhile; endif; ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer( 'modelos' ); ?>