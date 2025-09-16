<?php
$hero = get_field('hero');
$hero_images = $hero['images'];
$intro = get_field('intro');
$video = get_field('video');
$video_file = $video['file'];
$video_url = $video_file['url'];
$soins = get_field('soins');
$team = get_field('team');
$team_image = $team['image'];
$hours = get_field('hours');

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

    <section class="cdcc__home__intro" id="intro-tooth">
        <!-- HTML -->
        <div class="tooth-wrap">
            <svg id="toothSVG" class="tooth-svg"
                xmlns="http://www.w3.org/2000/svg" version="1.1"
                viewBox="0 0 141.7 214.1"
                style="shape-rendering:geometricPrecision; text-rendering:optimizeQuality;">
                <g>
                    <!-- former <rect x="39.7" y="141.4" width="26.1" height="31.4" /> -->
                    <path class="tooth-path" fill="#3D4C51"
                        d="M39.7 141.4h26.1v31.4H39.7Z" />
                    <path class="tooth-path" fill="#3D4C51"
                        d="M127.5,11H87.5H83H65.4h-3.9H16.9v18.3v4.6v40.3v4.4v19.8l-7.1,3.6l0.1,4.3l0.1,4.5L52.7,89L95.9,111v69h3.9
             h3.9v-12.8c2.4-1.5,4.7-3.1,6.9-4.9c2.1-1.8,4.1-3.7,6-5.7c2.5-2.7,4.7-5.7,6.6-8.8c1-1.6,1.8-3.2,2.6-4.9
             c0.8-1.7,1.5-3.4,2.2-5.1c2.2-6.1,3.5-12.8,3.5-19.7V111v-4.6V90v-7.3V13.2V11H127.5z M117.9,16.5L97.9,28l-7-11.5H117.9z
             M86.3,16.5l8.2,13.4L72.2,42.6l-5.6-26.1H86.3z M73.7,67.5L56.6,56l13-7.4L73.7,67.5z M22.3,16.5h40.4l6,28.1l-15.7,9L22.3,33
             V16.5z M22.3,37.6l26.9,18.1L22.3,71.2V37.6z M52.7,80.3L22.3,95.8V75.6l30.5-17.4l22,14.8l4.5,20.9L52.7,80.3z M125.9,118.2
             c0,17.6-8.8,33.1-22.2,42.5v-54.5l-20-10.1l-4.3-20l46.5,31.3V118.2z M125.9,102.7l-47.7-32l-5.2-24l23.4-13.4l29.4,47.9V102.7z
             M125.9,73.7l-26-42.5l25.8-14.8h0.2V73.7z" />
                    <path class="tooth-path" fill="#3D4C51"
                        d="M52.7,205.8c-26.9,0-40.1-20.4-40.3-20.6l2.7-1.7c0.6,1,16.1,24.7,48.6,18l0.6,3.1
             C60.3,205.4,56.4,205.8,52.7,205.8z" />
                </g>
            </svg>
        </div>


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

    <section class="cdcc__home__soins" id="soins">
        <!-- SVG crossed lines background -->
        <svg class="soins-lines" viewBox="0 0 1440 600" preserveAspectRatio="none" aria-hidden="true">
            <g class="lines" fill="none" stroke-linecap="round" stroke-width="1">
                <!-- tweak these paths to match your exact style/angles -->
                <path d="M1200 0 L600 600" />
                <path d="M1440 80 L760 600" />
                <path d="M1100 0 L1440 340" />
                <path d="M900 0 L1400 500" />
                <path d="M1440 220 L1000 600" />
                <path d="M1280 0 L820 460" />
            </g>
        </svg>
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
                    <?php
                    while ($soins_query->have_posts()) : $soins_query->the_post();
                    ?>
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
                                    <?php echo get_field('description'); ?>
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

    <section class="cdcc__home__team" id="notre-equipe">
        <div class="cdcc__home__team__title">
            <h4 class="title">
                <?php echo isset($team['title']) ? esc_html($team['title']) : ''; ?>
                <span>.</span>
            </h4>

            <div class="subtitle">
                <?php echo isset($team['subtitle']) ? wp_kses_post($team['subtitle']) : ''; ?>
            </div>

            <a href="/notre-equipe" class="btn btn--team">
                <span class="btn__dot"></span>
                <span class="btn__text">Voir l'équipe</span>
                <span class="btn__icon iconify" data-icon="tabler:arrow-right"></span>
            </a>
        </div>

        <div class="cdcc__home__team__content">
            <div class="image">
                <?php if (!empty($team_image['url'])): ?>
                    <img src="<?php echo esc_url($team_image['url']); ?>" alt="<?php echo esc_attr($team_image['alt'] ?? 'Équipe'); ?>">
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="cdcc__home__urgences">
        <div class="inner">
            <div class="col__image">
                <div class="image">
                    <?php
                    $urgences = get_field('urgences');
                    if (!empty($urgences['image']['url'])) :
                    ?>
                        <img src="<?php echo esc_url($urgences['image']['url']); ?>" alt="<?php echo esc_attr($urgences['image']['alt'] ?? 'Urgences'); ?>">
                    <?php endif; ?>
                </div>
            </div>
            <div class="col__content">
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

    <section class="cdcc__home__acces" id="acces">
        <div class="cdcc__home__acces__title">
            <h5>
                Accès
            </h5>
        </div>
        <div class="cdcc__home__acces__content">
            <div id="map">

            </div>
        </div>
    </section>

    <section class="cdcc__home__hours" id="horaires">
        <div class="cdcc__home__hours__title">
            <h6>
                <?php echo isset($hours['title']) ? esc_html($hours['title']) : ''; ?>
            </h6>
        </div>
        <div class="cdcc__home__hours__content">
            <div class="list">
                <?php
                if (!empty($hours['content'])) {
                    echo wp_kses_post($hours['content']);
                }
                ?>
            </div>
        </div>
    </section>

</div>

<?php get_footer(); ?>

<script>
    function initMap() {
        console.log('Initializing the map...');

        // Check if the map container exists
        var mapContainer = document.getElementById('map');
        if (!mapContainer) {
            console.error('Map container not found.');
            return;
        }

        // Center position of the map
        var centerPosition = {
            lat: 46.75764045037094,
            lng: 4.71702667406135
        };
        // Initialize the map with custom styles
        var map = new google.maps.Map(mapContainer, {
            center: centerPosition, // Set center
            mapTypeControl: false, // Disable plan/satellite toggle
            zoomControl: false, // Disable zoom controls
            streetViewControl: false, // Disable Street View control
            fullscreenControl: false,
            zoom: 12,
            styles: [{
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#f5f5f5"
                    }]
                },
                {
                    "elementType": "labels.icon",
                    "stylers": [{
                        "visibility": "off"
                    }]
                },
                {
                    "elementType": "labels.text.fill",
                    "stylers": [{
                        "color": "#616161"
                    }]
                },
                {
                    "elementType": "labels.text.stroke",
                    "stylers": [{
                        "color": "#f5f5f5"
                    }]
                },
                {
                    "featureType": "administrative.land_parcel",
                    "elementType": "labels.text.fill",
                    "stylers": [{
                        "color": "#bdbdbd"
                    }]
                },
                {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#eeeeee"
                    }]
                },
                {
                    "featureType": "poi",
                    "elementType": "labels.text.fill",
                    "stylers": [{
                        "color": "#757575"
                    }]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#e5e5e5"
                    }]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "labels.text.fill",
                    "stylers": [{
                        "color": "#9e9e9e"
                    }]
                },
                {
                    "featureType": "road",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#ffffff"
                    }]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "labels.text.fill",
                    "stylers": [{
                        "color": "#CBAE70"
                    }]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#CBAE70"
                    }]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "labels.text.fill",
                    "stylers": [{
                        "color": "#616161"
                    }]
                },
                {
                    "featureType": "road.local",
                    "elementType": "labels.text.fill",
                    "stylers": [{
                        "color": "#9e9e9e"
                    }]
                },
                {
                    "featureType": "transit.line",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#e5e5e5"
                    }]
                },
                {
                    "featureType": "transit.station",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#eeeeee"
                    }]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#3D4C51"
                    }]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry.fill",
                    "stylers": [{
                        "color": "#3D4C51"
                    }]
                },
                {
                    "featureType": "water",
                    "elementType": "labels.text.fill",
                    "stylers": [{
                        "color": "#9e9e9e"
                    }]
                }
            ]
        });

        // Custom red marker icon
        var markerIcon = {
            scaledSize: new google.maps.Size(40, 40), // Scaled size (optional)
        };

        // Add a marker at the center of the map

        var marker = new google.maps.marker.AdvancedMarkerElement({
            position: centerPosition,
            map: map,
            icon: markerIcon, // Set custom red icon
            title: "Marbrerie Vessot" // Tooltip text on hover
        });

        console.log('Map initialized with center marker:', map);
    }

    // Check if the Google Maps script has loaded and then run initMap
    window.addEventListener('load', function() {
        if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
            console.log('Google Maps API loaded successfully.');
            initMap();
        } else {
            console.error('Google Maps API not loaded.');
        }
    });
</script>