<?php
$hero = get_field('hero');
$intro = get_field('intro');
get_header(); ?>

<div class="cdcc__equipe">
    <section class="cdcc__hero" role="banner" aria-label="<?php the_title_attribute(); ?>">

        <?php
        $hero_image = get_field('hero_image');
        if ($hero_image):
            $hero_image_url = $hero_image['url'];
            $hero_image_alt = $hero_image['alt'] ? $hero_image['alt'] : get_bloginfo('name');
        ?>
            <img src="<?php echo esc_url($hero_image_url); ?>" alt="<?php echo esc_attr($hero_image_alt); ?>">
        <?php endif; ?>
    </section>
    <section class="cdcc__equipe__content">
        <div class="intro">
            <h1><?php the_title(); ?></h1>
            <div class="subtitle">
                <?php echo $intro['subtitle']; ?>
            </div>
        </div>
        <!-- <?php
                $field_obj = get_field_object('type');
                $tax_name  = $field_obj['taxonomy'] ?? '';

                $terms_all = [];
                if ($tax_name) {
                    $terms_all = get_terms([
                        'taxonomy'   => $tax_name,
                        'hide_empty' => true,
                    ]);
                }
                ?>
        <div class="team-filters" role="toolbar" aria-label="Filtrer l’équipe">

            <?php foreach ($terms_all as $t): ?>
                <button type="button"
                    data-filter='[data-taxonomy~="<?php echo esc_attr($t->slug); ?>"]'>
                    <?php echo esc_html($t->name); ?>
                </button>
            <?php endforeach; ?>
            <button class="praticien" type="button" data-filter='[data-taxonomy~="praticien"]'>Praticiens</button>
            <button class="assistante" type="button" data-filter='[data-taxonomy~="assistante"]'>Assistantes</button>
        </div> -->

        <div class="equipe__container">
            <?php
            $team_query = new WP_Query([
                'post_type'      => 'membre_equipe',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            ]);

            if ($team_query->have_posts()) :

                // two buckets
                $items_praticiens  = [];
                $items_assistantes = [];
                $idx = 0;

                while ($team_query->have_posts()) : $team_query->the_post();
                    $image         = get_field('image');
                    $qualification = get_field('qualification');
                    $bio           = get_field('introduction');
                    if (!$bio) $bio = apply_filters('the_content', get_the_content());

                    // Build “type” label(s)
                    $type       = get_field('type');
                    $type_label = '';
                    $terms      = [];

                    if (!empty($type)) {
                        $field    = get_field_object('type');
                        $taxonomy = $field['taxonomy'] ?? '';
                        if (is_array($type)) {
                            foreach ($type as $t) {
                                if (is_object($t) && isset($t->name)) $terms[] = $t;
                                elseif (is_numeric($t) && $taxonomy) {
                                    $term = get_term((int)$t, $taxonomy);
                                    if ($term && !is_wp_error($term)) $terms[] = $term;
                                }
                            }
                        } else {
                            if (is_object($type) && isset($type->name)) $terms[] = $type;
                            elseif (is_numeric($type) && $taxonomy) {
                                $term = get_term((int)$type, $taxonomy);
                                if ($term && !is_wp_error($term)) $terms[] = $term;
                            }
                        }
                        if ($terms) $type_label = implode(', ', array_map(fn($t) => $t->name, $terms));
                    }

                    // role detection (assistante/assistant goes to staff list)
                    $is_staff = false;
                    foreach ($terms as $t) {
                        $slug = isset($t->slug) ? $t->slug : sanitize_title($t->name ?? '');
                        if (in_array($slug, ['assistante', 'assistant'], true)) {
                            $is_staff = true;
                            break;
                        }
                    }

                    // specialisations -> array of strings
                    $specs_names = [];
                    $specialisations = get_field('specialisations');
                    if (!empty($specialisations) && is_array($specialisations)) {
                        foreach ($specialisations as $spec) {
                            $name = '';
                            if (is_array($spec)) {
                                $name = $spec['item'] ?? '';
                                if (!$name && isset($spec['term_id'])) {
                                    $term = get_term((int)$spec['term_id']);
                                    if ($term && !is_wp_error($term)) $name = $term->name;
                                }
                            } elseif (is_object($spec) && isset($spec->name)) {
                                $name = $spec->name;
                            } elseif (is_numeric($spec)) {
                                $term = get_term((int)$spec);
                                if ($term && !is_wp_error($term)) $name = $term->name;
                            } elseif (is_string($spec)) {
                                $name = $spec;
                            }
                            if ($name) $specs_names[] = $name;
                        }
                    }

                    // render one <li> into a buffer
                    ob_start();
                    // Build data-taxonomy (space-separated slugs)
                    $tax_slugs = [];
                    foreach ($terms as $t) {
                        $slug = isset($t->slug) ? $t->slug : sanitize_title($t->name ?? '');
                        if ($slug) $tax_slugs[] = $slug;
                    }
                    // Group pseudo-term for quick filter buttons
                    $tax_slugs[] = $is_staff ? 'assistante' : 'praticien';

                    $tax_attr = implode(' ', array_unique($tax_slugs));

            ?>
                    <li class="team__item <?php echo $is_staff ? 'is-staff' : 'is-praticien'; ?>"
                        tabindex="0"
                        data-taxonomy="<?php echo esc_attr($tax_attr); ?>"
                        data-index="<?php echo esc_attr($idx); ?>"
                        data-name="<?php echo esc_attr(get_the_title()); ?>"
                        data-qualification="<?php echo esc_attr($qualification ?: ''); ?>"
                        data-type="<?php echo esc_attr($type_label); ?>"
                        data-image="<?php echo esc_url($image['url'] ?? ''); ?>"
                        data-specs="<?php echo esc_attr(wp_json_encode($specs_names)); ?>"
                        data-hours="<?php echo esc_attr(get_field('hours') ?: ''); ?>"
                        aria-controls="teamModal"
                        aria-haspopup="dialog">

                        <?php if ($type_label): ?>
                            <div class="team__item__type <?php echo $is_staff ? 'is-staff' : 'is-praticien'; ?>">
                                <?php echo esc_html($type_label); ?>
                            </div>
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

                        <!-- Hidden details for modal -->
                        <div class="team__item__details" hidden>
                            <?php echo wp_kses_post($bio); ?>
                        </div>
                    </li>
                <?php
                    $li = ob_get_clean();

                    // push into the right bucket
                    if ($is_staff) $items_assistantes[] = $li;
                    else           $items_praticiens[]  = $li;

                    $idx++;
                endwhile;

                // Output two separate lists
                ?>
                <?php if (!empty($items_praticiens)) : ?>
                    <ul class="list list--praticiens">
                        <?php echo implode("\n", $items_praticiens); ?>
                    </ul>
                <?php endif; ?>

                <?php if (!empty($items_assistantes)) : ?>
                    <ul class="list list--assistantes">
                        <?php echo implode("\n", $items_assistantes); ?>
                    </ul>
                <?php endif; ?>

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

                                <div class="teamModalHours__container">
                                    <span id="teamModalHours__title">Horaires standard téléphonique</span>
                                    <div id="teamModalHours">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endif;
            wp_reset_postdata(); ?>
        </div>

    </section>
</div>

<?php get_footer(); ?>