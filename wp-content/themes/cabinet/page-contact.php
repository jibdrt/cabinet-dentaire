<?php
$hero = get_field('hero');
get_header(); ?>

<div class="cdcc__contact">
    <section class="cdcc__hero" role="banner" aria-label="<?php the_title_attribute(); ?>">
        <div class="cdcc__hero__inner">
            <div class="image">
                <?php
                $hero_image = get_field('hero_image');
                if ($hero_image):
                    $hero_image_url = $hero_image['url'];
                    $hero_image_alt = $hero_image['alt'] ? $hero_image['alt'] : get_bloginfo('name');
                ?>
                    <img src="<?php echo esc_url($hero_image_url); ?>" alt="<?php echo esc_attr($hero_image_alt); ?>">
                <?php endif; ?>
            </div>

            <span class="divider" aria-hidden="true"></span>

            <div class="content">
                <h1><?php the_title(); ?></h1>
                <?php if (!empty($hero['subtitle'])): ?>
                    <p class="subtitle"><?php echo esc_html($hero['subtitle']); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <section class="cdcc__contact__content">
        <div class="intro">
            <h2 class="title">
                <?php echo esc_html($intro['title']); ?>
            </h2>
            <div class="subtitle">
                <?php echo $intro['subtitle']; ?>
            </div>
        </div>
        <div class="form__container">
            <?php
            echo do_shortcode('[wpforms id="173" title="Formulaire de contact"]');
            ?>
        </div>
    </section>

</div>

<?php get_footer(); ?>