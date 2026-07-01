<?php
/**
 * Template Part: Quiénes Somos
 * Incluido desde template-quienes-somos.php (raíz del tema).
 *
 * @package HelloElementorChild
 */
?>

<main class="nil-page nil-page--quienes-somos py-2xl">
    <div class="container">

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

            <!-- Eyebrow + Título -->
            <div class="row mb-lg">
                <div class="col-12">
                    <h1 class="h1 text-uppercase mb-0"><?php the_title(); ?></h1>
                </div>
            </div>

            <hr class="nil-page__divider mb-lg">

            <!-- Contenido editorial -->
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="nil-page__content">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>

        <?php endwhile; endif; ?>

    </div><!-- .container -->
</main>
