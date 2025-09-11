</div><!--page-->
<footer class="cdcc__footer">
    <div class="inner">
        <div class="left">
            <a href="<?php echo home_url(); ?>" class="logo">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo_horizontal_1_blanc.png" alt="<?php bloginfo('name'); ?>">
            </a>
            <div class="infos">
                <div class="location">
                    <?php
                    $address = get_field('location', 'option');
                    $location_map = get_field('location_map', 'option');
                    $map_url = !empty($location_map['url']) ? $location_map['url'] : 'https://maps.app.goo.gl/5fJi3e9aY51NXUQt9';
                    ?>
                    <span class="iconify icon" data-icon="zondicons:location" data-inline="false"></span>
                    <a href="<?php echo esc_url($map_url); ?>" target="_blank" rel="noopener">
                        <?php echo esc_html($address); ?>
                    </a>
                </div>
                <div class="phone">
                    <span class="iconify icon" data-icon="solar:phone-bold" data-inline="false"></span>
                    <?php
                    $phone = get_field('phone', 'option');
                    if ($phone):
                        // Remove non-numeric characters for tel: link
                        $tel = preg_replace('/\D+/', '', $phone);
                    ?>
                        <a href="tel:<?php echo esc_attr($tel); ?>"><?php echo esc_html($phone); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="right">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu'           => 'Menu principal',
                'container'      => false,
                'menu_class'     => 'footer__menu',
                'fallback_cb'    => false,
                'depth'          => 2,
                'link_before'    => '',
                'link_after'     => '',
            ]);
            ?>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>

</html>