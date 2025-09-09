   <section class="cdcc__home__soins">
        <div class="cdcc__home__soins__inner">
            <div class="cdcc__home__soins__inner__title">
                <h3>
                    <?php echo isset($soins['title']) ? esc_html($soins['title']) : ''; ?>
                    <span>.</span>
                </h3>
                <h4>
                    <?php echo isset($soins['subtitle']) ? esc_html($soins['subtitle']) : ''; ?>
                </h4>
                <hr class="soins__title__separator">
            </div>

            <?php
            $soins_query = new WP_Query([
                'post_type' => 'soin',
                'posts_per_page' => -1,
                'orderby' => 'menu_order',
                'order' => 'ASC',
            ]);
            if ($soins_query->have_posts()) : ?>
                <div class="cdcc__home__soins__inner__list">
                    <?php while ($soins_query->have_posts()) : $soins_query->the_post(); ?>
                        <div class="soins__item soin">
                            <h5 class="soins__item__title"><?php the_title(); ?></h5>
                            <div class="soins__item__description">
                                <?php echo esc_html(get_field('description')); ?>
                            </div>
                            <!-- <a href="<?php the_permalink(); ?>" class="btn soins__item__btn">
                                <span class="btn__dot"></span>
                                <span class="btn__text">En savoir plus</span>
                                <span class="btn__icon iconify" data-icon="tabler:arrow-right"></span>
                            </a> -->
                        </div>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </div>
            <?php endif; ?>
        </div>


    </section>

    <section class="cdcc__home__team">
        <div class="inner">
            <div class="title">
                <h3>Notre équipe<span>.</span></h3>
                <h4>
                    Découvrez notre gamme complète de soins dentaires,<br> alliant expertise et technologie de pointe pour votre sourire.
                </h4>
                <hr class="team__title__separator">
            </div>
            <?php
            $team_query = new WP_Query([
                'post_type' => 'membre_equipe',
                'posts_per_page' => -1,
                'orderby' => 'menu_order',
                'order' => 'ASC',
            ]);
            if ($team_query->have_posts()) : ?>
                <ul class="list">
                    <?php while ($team_query->have_posts()) : $team_query->the_post();
                        $image = get_field('image');
                        $type = get_field('type');
                    ?>
                        <li class="team__item">
                            <?php
                            $type = get_field('type');
                            if (! empty($type)) {
                                $field    = get_field_object('type');
                                $taxonomy = $field['taxonomy'] ?? '';
                                $terms    = [];

                                if (is_array($type)) {
                                    foreach ($type as $t) {
                                        if (is_object($t) && isset($t->name)) {
                                            $terms[] = $t;
                                        } elseif (is_numeric($t) && $taxonomy) {
                                            $term = get_term((int) $t, $taxonomy);
                                            if ($term && ! is_wp_error($term)) $terms[] = $term;
                                        }
                                    }
                                } else {
                                    if (is_object($type) && isset($type->name)) {
                                        $terms[] = $type;
                                    } elseif (is_numeric($type) && $taxonomy) {
                                        $term = get_term((int) $type, $taxonomy);
                                        if ($term && ! is_wp_error($term)) $terms[] = $term;
                                    }
                                }

                                if ($terms) {
                                    $names = array_map(fn($term) => $term->name, $terms);
                                    echo '<div class="team__item__type">' . esc_html(implode(', ', $names)) . '</div>';
                                }
                            }
                            ?>
                            <?php if (!empty($image)) : ?>
                                <div class="team__item__image">
                                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="image">
                                </div>
                            <?php endif; ?>
                            <div class="team__item__info">
                                <h5 class="team__item__title"><?php the_title(); ?></h5>
                                <h6 class="team__item__qualification">
                                    <?php echo esc_html(get_field('qualification')); ?>
                                </h6>
                            </div>
                        </li>
                    <?php endwhile; ?>

                    <!-- FAKE ITEM WITH BUTTON -->
                    <li class="team__item team__item--cta">
                        <a href="/rejoindre-notre-equipe" class="btn btn--team">
                            <span class="btn__dot"></span>
                            <span class="btn__text">Toute l'équipe</span>
                            <span class="btn__icon iconify" data-icon="tabler:arrow-right"></span>
                        </a>
                    </li>

                    <?php wp_reset_postdata(); ?>
                </ul>
            <?php endif; ?>

        </div>
    </section>