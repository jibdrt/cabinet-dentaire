<?php
$hero = get_field('hero');
$intro = get_field('intro');
get_header(); ?>

<div class="cdcc__default">
    <section class="cdcc__hero cdcc__hero--default" role="banner" aria-label="<?php the_title_attribute(); ?>">

        <?php
        $hero_image = get_field('image');
        if ($hero_image):
            $hero_image_url = $hero_image['url'];
            $hero_image_alt = $hero_image['alt'] ? $hero_image['alt'] : get_bloginfo('name');
        ?>
            <img src="<?php echo esc_url($hero_image_url); ?>" alt="<?php echo esc_attr($hero_image_alt); ?>">
        <?php endif; ?>
    </section>
    <section class="cdcc__default__content">
        <div class="intro">
            <a onclick="if (document.referrer !== '' || history.length > 1) { history.back(); return false; }" class="btn nav-prev">
                <span class="btn__dot"></span>
                <span class="btn__icon iconify" data-icon="tabler:chevron-left" aria-hidden="true"></span>
                <span class="btn__text">Retour</span>
            </a>
            <h1><?php the_title(); ?></h1>
        </div>

        <div class="content">
            <?php the_content(); ?>
        </div>



    </section>

</div>

<?php get_footer(); ?>