<?php
$hero = get_field('hero');
$intro = get_field('intro');
get_header(); ?>

<div class="cdcc__cabinet">
    <section class="cdcc__hero cdcc__hero--overlay" role="banner" aria-label="<?php the_title_attribute(); ?>">
        <div class="cdcc__hero__media" aria-hidden="true">
            <?php
            $hero_image = get_field('hero_image');
            if ($hero_image):
                $hero_image_url = $hero_image['url'];
                $hero_image_alt = $hero_image['alt'] ? $hero_image['alt'] : get_bloginfo('name');
            ?>
                <img src="<?php echo esc_url($hero_image_url); ?>" alt="<?php echo esc_attr($hero_image_alt); ?>">
            <?php endif; ?>
            <h1><?php the_title(); ?></h1>
            <?php if (!empty($hero['subtitle'])): ?>
                <p class="subtitle"><?php echo esc_html($hero['subtitle']); ?></p>
            <?php endif; ?>
        </div>
    </section>


    <section class="cdcc__cabinet__content">
        <div class="intro">
            <h2 class="title">
                <?php echo esc_html($intro['title']); ?>
            </h2>
            <div class="subtitle">
                <?php echo $intro['subtitle']; ?>
            </div>
        </div>
        <div class="gallery">

            <?php
            $gallery = get_field('gallery');
            if ($gallery):
            ?>
                <ul class="grid" id="cabinet-grid">
                    <li class="grid__sizer"></li>
                    <li class="grid__gutter"></li>

                    <?php foreach ($gallery as $img): ?>
                        <li class="grid__item">
                            <a href="<?php echo esc_url($img['url']); ?>" class="lightbox" data-caption="<?php echo esc_attr($img['alt']); ?>">
                                <figure class="grid__figure">
                                    <img
                                        src="<?php echo esc_url($img['sizes']['large'] ?? $img['url']); ?>"
                                        alt="<?php echo esc_attr($img['alt'] ?: get_bloginfo('name')); ?>"
                                        loading="lazy">
                                </figure>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

        </div>
    </section>
</div>

<?php get_footer(); ?>