<?php
$hero = get_field('hero');
$intro = get_field('intro');
get_header(); ?>

<div class="cdcc__equipe">
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
    <section class="cdcc__equipe__content">
        <div class="intro">
            <h2 class="title">
                <?php echo esc_html($intro['title']); ?>
            </h2>
            <div class="subtitle">
                <?php echo $intro['subtitle']; ?>
            </div>
        </div>
        <div class="equipe__container">
            <?php
            $team_query = new WP_Query([
                'post_type'      => 'membre_equipe',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            ]);
            if ($team_query->have_posts()) : ?>
                <ul class="list">
                    <?php
                    $i = 0;
                    while ($team_query->have_posts()) : $team_query->the_post();
                        $image         = get_field('image');
                        $qualification = get_field('qualification');
                        $bio           = get_field('introduction');
                        if (!$bio) $bio = apply_filters('the_content', get_the_content());

                        // Build “type” label(s)
                        $type       = get_field('type');
                        $type_label = '';
                        if (!empty($type)) {
                            $field    = get_field_object('type');
                            $taxonomy = $field['taxonomy'] ?? '';
                            $terms    = [];

                            if (is_array($type)) {
                                foreach ($type as $t) {
                                    if (is_object($t) && isset($t->name)) $terms[] = $t;
                                    elseif (is_numeric($t) && $taxonomy) {
                                        $term = get_term((int) $t, $taxonomy);
                                        if ($term && !is_wp_error($term)) $terms[] = $term;
                                    }
                                }
                            } else {
                                if (is_object($type) && isset($type->name)) $terms[] = $type;
                                elseif (is_numeric($type) && $taxonomy) {
                                    $term = get_term((int) $type, $taxonomy);
                                    if ($term && !is_wp_error($term)) $terms[] = $term;
                                }
                            }

                            if ($terms) $type_label = implode(', ', array_map(fn($t) => $t->name, $terms));
                        }

                        // Collect repeater "specialisations" into an array of strings
                        $specs_names     = [];
                        $specialisations = get_field('specialisations'); // repeater OR taxonomy OR plain array
                        if (!empty($specialisations) && is_array($specialisations)) {
                            foreach ($specialisations as $spec) {
                                $name = '';
                                if (is_array($spec)) {
                                    // Common repeater subfield keys - adapt if yours differ
                                    $name = $spec['item'] ?? '';
                                    if (!$name && isset($spec['term_id'])) {
                                        $term = get_term((int) $spec['term_id']);
                                        if ($term && !is_wp_error($term)) $name = $term->name;
                                    }
                                } elseif (is_object($spec) && isset($spec->name)) {
                                    $name = $spec->name;
                                } elseif (is_numeric($spec)) {
                                    $term = get_term((int) $spec);
                                    if ($term && !is_wp_error($term)) $name = $term->name;
                                } elseif (is_string($spec)) {
                                    $name = $spec;
                                }
                                if ($name) $specs_names[] = $name;
                            }
                        }
                    ?>
                        <li class="team__item"
                            tabindex="0"
                            data-index="<?php echo esc_attr($i); ?>"
                            data-name="<?php echo esc_attr(get_the_title()); ?>"
                            data-qualification="<?php echo esc_attr($qualification ?: ''); ?>"
                            data-type="<?php echo esc_attr($type_label); ?>"
                            data-image="<?php echo esc_url($image['url'] ?? ''); ?>"
                            data-specs="<?php echo esc_attr(wp_json_encode($specs_names)); ?>"
                            aria-controls="teamModal"
                            aria-haspopup="dialog">
                            <?php if ($type_label): ?>
                                <div class="team__item__type"><?php echo esc_html($type_label); ?></div>
                            <?php endif; ?>

                            <?php if (!empty($image)) : ?>
                                <div class="team__item__image">
                                    <img src="<?php echo esc_url($image['url']); ?>"
                                        alt="<?php echo esc_attr($image['alt'] ?: get_the_title()); ?>"
                                        class="image">
                                </div>
                            <?php endif; ?>

                            <div class="team__item__info">
                                <h5 class="team__item__title"><?php the_title(); ?></h5>
                                <?php if ($qualification): ?>
                                    <h6 class="team__item__qualification"><?php echo esc_html($qualification); ?></h6>
                                <?php endif; ?>
                            </div>

                            <!-- Hidden details pulled into the modal -->
                            <div class="team__item__details" hidden>
                                <?php echo wp_kses_post($bio); ?>
                            </div>
                        </li>
                    <?php
                        $i++;
                    endwhile; ?>
                </ul>

                <!-- Modal -->
                <div class="cdcc__modal" id="teamModal" role="dialog" aria-modal="true"
                    aria-hidden="true" aria-labelledby="teamModalTitle" aria-describedby="teamModalDesc">
                    <div class="cdcc__modal__overlay" data-close></div>
                    <div class="cdcc__modal__dialog" role="document">
                        <button class="cdcc__modal__close" type="button" aria-label="Fermer" data-close>&times;</button>

                        <button class="cdcc__modal__nav prev" type="button" aria-label="Précédent" data-prev>
                            <span class="iconify" data-icon="tabler:chevron-left" aria-hidden="true"></span>
                        </button>
                        <button class="cdcc__modal__nav next" type="button" aria-label="Suivant" data-next>
                            <span class="iconify" data-icon="tabler:chevron-right" aria-hidden="true"></span>
                        </button>

                        <div class="cdcc__modal__body">
                            <div class="cdcc__modal__media">
                                <img id="teamModalImg" alt="">
                            </div>
                            <div class="cdcc__modal__content">
                                <h3 id="teamModalTitle"></h3>
                                <p class="cdcc__modal__meta">
                                    <span id="teamModalType"></span>
                                    <span class="sep" aria-hidden="true"> · </span>
                                    <span id="teamModalQualif"></span>
                                </p>
                                <p id="teamModalDesc"></p>
                                <!-- Empty; populated by JS -->
                                <ul id="teamModalSpecialisations"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php get_footer(); ?>