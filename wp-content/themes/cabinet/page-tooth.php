<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Tooth 3D – Vanilla Three.js</title>
</head>

<body>

    <div class="test-zone">
        <div class="svg-wrap">
            <?php
            // Inline the SVG so its nodes are reachable by JS
            echo file_get_contents(get_template_directory() . '/assets/animation/tooth-draw.svg');
            ?>
        </div>
    </div>


</body>

</html>

<script src="https://unpkg.com/gsap@3/dist/gsap.min.js"></script>
<script src="https://unpkg.com/gsap@3/dist/ScrollTrigger.min.js"></script>
<script>
    gsap.registerPlugin(ScrollTrigger);

    window.addEventListener('load', () => {
        const zone = document.querySelector('.test-zone');
        const svg = zone?.querySelector('svg');
        if (!svg) return;

        svg.removeAttribute('width');
        svg.removeAttribute('height');
        svg.setAttribute('preserveAspectRatio', 'xMidYMid meet');

        const shapes = [...svg.querySelectorAll('path,line,polyline,polygon,circle,rect,ellipse')]
            .filter(el => typeof el.getTotalLength === 'function');

        // prime strokes + measure lengths
        const lengths = shapes.map(el => {
            const len = el.getTotalLength();
            gsap.set(el, {
                strokeDasharray: len,
                strokeDashoffset: len
            });
            return len;
        });

        // build a sequential timeline where duration ~ length
        const tl = gsap.timeline({
            defaults: {
                ease: 'none'
            },
            scrollTrigger: {
                trigger: '.test-zone',
                start: 'top top',
                end: 'bottom bottom',
                scrub: true,
                invalidateOnRefresh: true
            }
        });

        shapes.forEach((el, i) => {
            tl.to(el, {
                strokeDashoffset: 0,
                duration: lengths[i]
            }, i === 0 ? 0 : ">");
        });

        // reduced-motion
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            ScrollTrigger.getAll().forEach(st => st.kill());
            shapes.forEach(el => gsap.set(el, {
                strokeDashoffset: 0
            }));
        }
    });
</script>


<style>
    .test-zone {
        height: 5000px;
    }

    .svg-wrap {
        position: sticky;
        top: 0;
        width: 100vw;
        height: 100vh;
        display: grid;
        place-items: center;
        /* center the SVG in the viewport */
        background: #fff;
        /* optional */
    }

    .svg-wrap svg {
        width: 100%;
        height: 100%;
        display: block;
    }

    /* Stroke drawing look */
    .svg-wrap svg * {
        vector-effect: non-scaling-stroke;
    }

    .svg-wrap svg path,
    .svg-wrap svg line,
    .svg-wrap svg polyline,
    .svg-wrap svg polygon,
    .svg-wrap svg circle,
    .svg-wrap svg rect,
    .svg-wrap svg ellipse {
        fill: none;
        stroke: #CBAE70;
        stroke-width: 2;
    }
</style>

<?php get_footer(); ?>