<?php
$hero = get_field('hero');
$hero_images = $hero['images'];
$intro = get_field('intro');
$video = get_field('video');
$video_file = $video['file'];
$video_url = $video_file['url'];
$soins = get_field('soins');

get_header(); ?>

<div class="cdcc__home" id="home">

    <section class="cdcc__home__hero">
        <div class="cdcc__home__hero__inner">
            <div class="cdcc__home__hero__inner__col content">
                <div class="inner__content">
                    <div class="inner__content__title">
                        <h1>Cabinet dentaire</h1>
                        <span>de la côte chalonnaise</span>
                    </div>
                    <hr class="inner__content__separator">
                    <h2 class="inner__content__subtitle">
                        L’excellence dentaire au service de votre bien-être et de votre sourire et de lorem ipsum dolor sit amet et consectetur adipiscing elit.
                    </h2>

                    <a href="/rdv" class="hero__btn">
                        <span class="hero__btn__dot"></span>
                        <span class="hero__btn__text">Prendre Rendez-vous</span>
                        <span class="hero__btn__icon iconify" data-icon="tabler:arrow-right"></span>
                    </a>

                </div>
            </div>
            <div class="cdcc__home__hero__inner__col media">
                <div class="splide hero-splide" role="group" aria-label="Hero Slider">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <?php if (!empty($hero_images)) : // ACF repeater ou gallery 
                            ?>
                                <?php foreach ($hero_images as $hero_image) : ?>
                                    <li class="splide__slide">
                                        <div class="inner__media">
                                            <div class="inner__media__image">
                                                <img src="<?php echo esc_url($hero_image['url']); ?>" alt="<?php echo esc_attr($hero_image['alt']); ?>">
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="cdcc__home__intro">
        <div class="cdcc__home__intro__inner">
            <h2 class="cdcc__home__intro__title">
                <?php echo $intro['title']; ?>
            </h2>
            <div class="cdcc__home__intro__content">
                <?php echo $intro['content']; ?>
            </div>
        </div>
    </section>

    <section class="cdcc__home__video">
        <div class="cdcc__home__video__inner">
            <?php if (!empty($video_url)) : ?>
                <div class="cdcc__home__video__frame">
                    <video class="cdcc__home__video__media" autoplay muted loop playsinline>
                        <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                        Votre navigateur ne supporte pas la vidéo.
                    </video>
                </div>
            <?php endif; ?>

            <a href="/le-cabinet" class="btn">
                <span class="btn__dot"></span>
                <span class="btn__text">Le cabinet</span>
                <span class="btn__icon iconify" data-icon="tabler:arrow-right"></span>
            </a>
        </div>
    </section>

    <section class="cdcc__home__soins">
        <div class="cdcc__home__soins__title">
            <h3 class="title">
                <?php echo isset($soins['title']) ? esc_html($soins['title']) : ''; ?>
                <span>.</span>
            </h3>
            <div class="subtitle">
                <?php echo isset($soins['subtitle']) ? wp_kses_post($soins['subtitle']) : ''; ?>
            </div>
        </div>
        <div class="cdcc__home__soins__content">
            <?php
            $soins_query = new WP_Query([
                'post_type' => 'soin',
                'posts_per_page' => -1,
                'orderby' => 'menu_order',
                'order' => 'ASC',
            ]);
            if ($soins_query->have_posts()) : ?>
                <div class="list">
                    <?php while ($soins_query->have_posts()) : $soins_query->the_post(); ?>

                        <div class="item">
                            <div class="col__image">
                                <?php
                                $image = get_field('image');
                                if (!empty($image['url'])) :
                                ?>
                                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt'] ?? get_the_title()); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="col__content">
                                <h4 class="item__title"><?php the_title(); ?></h4>
                                <div class="item__description">
                                    <?php echo esc_html(get_field('description')); ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="btn btn--soin">
                                    <span class="btn__dot"></span>
                                    <span class="btn__text">En savoir plus</span>
                                    <span class="btn__icon iconify" data-icon="tabler:arrow-right"></span>
                                </a>
                            </div>
                        </div>

                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="cdcc__home__urgences">
        <div class="inner">
            <div class="col">
                <div class="image">
                    <?php
                    $urgences = get_field('urgences');
                    if (!empty($urgences['image']['url'])) :
                    ?>
                        <img src="<?php echo esc_url($urgences['image']['url']); ?>" alt="<?php echo esc_attr($urgences['image']['alt'] ?? 'Urgences'); ?>">
                    <?php endif; ?>
                </div>
            </div>
            <div class="col">
                <div class="content">
                    <h3 class="title">
                        <?php echo isset($urgences['title']) ? esc_html($urgences['title']) : ''; ?>
                    </h3>
                    <h4 class="subtitle">
                        <?php echo isset($urgences['subtitle']) ? esc_html($urgences['subtitle']) : ''; ?>
                    </h4>

                    <hr class="separator">

                    <div class="text">
                        <?php echo isset($urgences['text']) ? $urgences['text'] : ''; ?>
                    </div>

                    <a href="/urgences" class="btn btn--urgences">
                        <span class="btn__dot"></span>
                        <span class="btn__text">Urgences dentaires</span>
                        <span class="btn__icon iconify" data-icon="tabler:arrow-right"></span>
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>

<?php get_footer(); ?>