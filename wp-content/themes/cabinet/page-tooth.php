<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Tooth 3D – Vanilla Three.js</title>
    <style>
        :root {
            --bg: #ffffff;
            --ink: #334155;
        }

        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto;
            background: var(--bg);
            color: var(--ink);
        }

        .wrap {
            max-width: 1200px;
            margin: 0 auto;
            padding: 56px 24px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 32px;
        }

        @media (min-width: 960px) {
            .wrap {
                grid-template-columns: 1fr 1fr;
                align-items: center;
            }
        }

        h1 {
            font-weight: 600;
            line-height: 1.05;
            font-size: clamp(32px, 6vw, 64px);
            margin: 0 0 12px;
        }

        p.lead {
            font-size: clamp(16px, 2.2vw, 20px);
            color: #475569;
            max-width: 52ch;
        }

        .canvasCard {
            position: relative;
            aspect-ratio: 4/5;
            background: linear-gradient(#f1f5f9, #fff);
            border-radius: 22px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .12);
            overflow: hidden;
        }

        .blobA,
        .blobB {
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            pointer-events: none;
        }

        .blobA {
            width: 260px;
            height: 260px;
            right: -80px;
            top: -80px;
            background: #0f172a15;
        }

        .blobB {
            width: 320px;
            height: 320px;
            left: -90px;
            bottom: -110px;
            background: #fbbf241e;
        }

        /* CSS2D labels */
        .label {
            padding: 6px 10px;
            font-size: 12px;
            border-radius: 999px;
            border: 1px solid rgba(15, 23, 42, .15);
            background: rgba(255, 255, 255, .7);
            backdrop-filter: blur(6px);
            color: #334155;
            white-space: nowrap;
        }

        .label.active {
            border-color: #f59e0b;
            background: rgba(254, 243, 199, .8);
        }

        /* ensure the 2D renderer sits above the WebGL canvas */
        .labelsLayer {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div>
            <p style="letter-spacing:.25em;text-transform:uppercase;color:#64748b;margin:0 0 8px;">Cabinet dentaire</p>
            <h1>Excellence dentaire,<br />technologie de pointe</h1>
            <p class="lead">
                Survolez la dent pour mettre en évidence la <strong>couronne</strong>, l’<strong>émail</strong> et les <strong>racines</strong>.
                Animation fluide en WebGL (Three.js), aucun framework.
            </p>
        </div>

        <div class="canvasCard" id="tooth3d-card">
            <div class="blobA"></div>
            <div class="blobB"></div>
            <!-- WebGL will mount here -->
        </div>
    </div>

    <script type="importmap">
        {
            "imports": {
                "three": "https://unpkg.com/three@0.159.0/build/three.module.js",
                "three/addons/": "https://unpkg.com/three@0.159.0/examples/jsm/"
            }
        }
    </script>


    <script type="module">
        import * as THREE from "three";
        import {
            OrbitControls
        } from "three/addons/controls/OrbitControls.js";
        import {
            CSS2DRenderer,
            CSS2DObject
        } from "three/addons/renderers/CSS2DRenderer.js";

        const card = document.getElementById('tooth3d-card');

        // Scene, camera, renderer
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(45, 1, 0.1, 100);
        camera.position.set(0.9, 1.2, 2.2);

        const renderer = new THREE.WebGLRenderer({
            antialias: true,
            alpha: true
        });
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        renderer.setClearColor(0x000000, 0);
        card.appendChild(renderer.domElement);

        // 2D labels layer
        const labelRenderer = new CSS2DRenderer();
        labelRenderer.domElement.className = 'labelsLayer';
        card.appendChild(labelRenderer.domElement);

        // Lights
        scene.add(new THREE.AmbientLight(0xffffff, 0.6));
        const dir = new THREE.DirectionalLight(0xffffff, 0.8);
        dir.position.set(3, 5, 4);
        scene.add(dir);


        // --- Tooth group (REPLACE THIS WHOLE BLOCK) ---
        const tooth = new THREE.Group();
        scene.add(tooth);

        // Materials (reuse yours)
        const matCrown = new THREE.MeshPhysicalMaterial({
            color: '#edf2f7',
            roughness: .55,
            metalness: 0,
            clearcoat: .6,
            clearcoatRoughness: .35
        });
        const matEnamel = new THREE.MeshPhysicalMaterial({
            color: '#f8fbff',
            roughness: .15,
            metalness: 0,
            transmission: .7,
            ior: 1.45,
            thickness: .6,
            transparent: true,
            opacity: .95,
            clearcoat: 1,
            clearcoatRoughness: .1
        });
        const matRoots = new THREE.MeshPhysicalMaterial({
            color: '#e9edf2',
            roughness: .6,
            metalness: 0,
            clearcoat: .3
        });

        // 1) Crown/Neck: Lathe profile (units ≈ meters)
        function crownProfile() {
            // r (x) vs y profile (right side), top→bottom
            // top/occlusal = y=0.9, neck ≈ y=0
            const pts = [
                new THREE.Vector2(0.00, 0.95), // almost closed top
                new THREE.Vector2(0.18, 0.90),
                new THREE.Vector2(0.38, 0.78), // bulge of crown
                new THREE.Vector2(0.45, 0.62),
                new THREE.Vector2(0.42, 0.42),
                new THREE.Vector2(0.32, 0.20),
                new THREE.Vector2(0.22, 0.00), // neck constriction
                new THREE.Vector2(0.24, -0.05) // slight flare toward roots
            ];
            return pts;
        }

        const crownGeom = new THREE.LatheGeometry(crownProfile(), 128);
        crownGeom.computeVertexNormals();
        const crown = new THREE.Mesh(crownGeom, matCrown);
        crown.castShadow = crown.receiveShadow = true;
        tooth.add(crown);

        // Enamel shell: clone + small outward normal push
        const enamelGeom = crownGeom.clone();
        enamelGeom.computeVertexNormals();
        {
            const pos = enamelGeom.attributes.position;
            const norm = enamelGeom.attributes.normal;
            for (let i = 0; i < pos.count; i++) {
                pos.setXYZ(
                    i,
                    pos.getX(i) + norm.getX(i) * 0.02,
                    pos.getY(i) + norm.getY(i) * 0.02,
                    pos.getZ(i) + norm.getZ(i) * 0.02
                );
            }
            enamelGeom.computeVertexNormals();
        }
        const enamel = new THREE.Mesh(enamelGeom, matEnamel);
        tooth.add(enamel);

        // 2) Sculpt 4 cusps on the occlusal (top) so it reads like a molar
        function sculptCusps(geom, {
            yStart = 0.55,
            yEnd = 0.95,
            amp = 0.06,
            folds = 4
        } = {}) {
            geom.computeBoundingBox();
            const pos = geom.attributes.position;
            for (let i = 0; i < pos.count; i++) {
                const x = pos.getX(i),
                    y = pos.getY(i),
                    z = pos.getZ(i);
                if (y < yStart) continue; // only top area
                // 4-fold angular modulation
                const theta = Math.atan2(z, x);
                // smooth mask along Y
                const t = Math.min(1, Math.max(0, (y - yStart) / (yEnd - yStart)));
                const mask = t * t * (3 - 2 * t); // smoothstep
                const k = Math.cos(folds * theta) * amp * mask;

                // push radially outward to form cusps
                const r = Math.hypot(x, z);
                if (r > 1e-6) {
                    const nx = x / r,
                        nz = z / r;
                    pos.setXYZ(i, x + nx * k, y, z + nz * k);
                }
            }
            geom.attributes.position.needsUpdate = true;
            geom.computeVertexNormals();
        }

        // apply to both crown + enamel for coherent silhouette
        sculptCusps(crown.geometry, {
            amp: 0.07
        });
        sculptCusps(enamel.geometry, {
            amp: 0.07
        });

        // 3) Roots: twin curved tubes from the neck downward
        function makeRootCurve(offsetX = 0.22, curl = 0.18, length = 0.9) {
            const p0 = new THREE.Vector3(offsetX, -0.02, 0.0); // start: neck
            const p1 = new THREE.Vector3(offsetX * 1.2, -length * 0.33, curl * 0.3);
            const p2 = new THREE.Vector3(offsetX * 0.9, -length * 0.66, curl * 0.8);
            const p3 = new THREE.Vector3(offsetX * 0.7, -length, curl); // tip
            return new THREE.CubicBezierCurve3(p0, p1, p2, p3);
        }

        // Tube geometry, then taper radius toward the end
        function makeTaperedTube(curve, radialSegments = 12, tubularSegments = 64) {
            // base tube with unit radius; we'll scale vertices for taper
            const base = new THREE.TubeGeometry(curve, tubularSegments, 0.12, radialSegments, false);
            const pos = base.attributes.position;
            // build an array of param 'u' along length per vertex (approximate)
            // TubeGeometry stores a Frenet frame; easier approach: compute v index per ring
            const ring = radialSegments + 1; // per segment (includes seam vertex)
            for (let s = 0; s <= tubularSegments; s++) {
                const u = s / tubularSegments; // 0..1
                const radius = 0.12 * (0.95 - 0.75 * u); // taper to ~25% smaller
                for (let r = 0; r < ring; r++) {
                    const i = s * ring + r;
                    // scale each ring to new radius by normalizing XY of local cross-section
                    // We don't have local frames here, so approximate: scale relative to curve center
                    // Better: rebuild from scratch; Simpler: uniformly shrink away from curve center
                    // Since vertices already positioned, we can't easily get center — fallback: slight global shrink per segment:
                    const scale = radius / 0.12;
                    pos.setXYZ(i, pos.getX(i) * scale, pos.getY(i) * (0.98 + 0.02 * (1 - u)), pos.getZ(i) * scale);
                }
            }
            pos.needsUpdate = true;
            base.computeVertexNormals();
            return base;
        }

        const rootCurveL = makeRootCurve(-0.22, -0.18);
        const rootCurveR = makeRootCurve(0.22, 0.18);

        const rGeomL = makeTaperedTube(rootCurveL);
        const rGeomR = makeTaperedTube(rootCurveR);

        const r1 = new THREE.Mesh(rGeomL, matRoots);
        const r2 = new THREE.Mesh(rGeomR, matRoots);

        // small blend at neck: move roots slightly inside crown to hide seam
        r1.position.y = -0.01;
        r2.position.y = -0.01;

        const roots = new THREE.Group();
        roots.add(r1, r2);
        tooth.add(roots);

        // Recenter the whole tooth a bit
        tooth.position.y = -0.05;

        // --- end replacement ---

        // Update pickables for raycasting:
        const pickables = [crown, enamel, r1, r2];

        // === INTERACTIVITY & CONTROLS (ADD THIS) ===

        // Orbit controls (use 2D labels layer so UI stays clickable-free)
        const controls = new OrbitControls(camera, labelRenderer.domElement);
        controls.enablePan = false;
        controls.enableDamping = true;
        controls.dampingFactor = 0.08;
        controls.minDistance = 1.6;
        controls.maxDistance = 3.2;

        // Raycaster + pointer for hover picking
        const raycaster = new THREE.Raycaster();
        const pointer = new THREE.Vector2();
        let hovered = null;


        // Hover material/label switching
        function setActive(part) {
            hovered = part;
            // tweak colors when hovered
            matCrown.color.set(hovered === 'crown' ? '#e9eef3' : '#edf2f7');
            matEnamel.color.set(hovered === 'enamel' ? '#ffffff' : '#f8fbff');
            matRoots.color.set(hovered === 'roots' ? '#e7ebef' : '#e9edf2');

            // If you use CSS2D labels, toggle classes here:
            // labCrown?.element.classList.toggle('active', hovered === 'crown');
            // labEnamel?.element.classList.toggle('active', hovered === 'enamel');
            // labRoots?.element.classList.toggle('active', hovered === 'roots');
        }

        // Track pointer relative to the card
        function onPointerMove(e) {
            const rect = card.getBoundingClientRect();
            pointer.x = ((e.clientX - rect.left) / rect.width) * 2 - 1;
            pointer.y = -((e.clientY - rect.top) / rect.height) * 2 + 1;
        }
        card.addEventListener('pointermove', onPointerMove, {
            passive: true
        });



        // Resize
        function resize() {
            const w = card.clientWidth;
            const h = card.clientHeight;
            camera.aspect = w / h;
            camera.updateProjectionMatrix();
            renderer.setSize(w, h, false);
            labelRenderer.setSize(w, h);
        }
        window.addEventListener('resize', resize, {
            passive: true
        });
        resize();

        // Animate
        const clock = new THREE.Clock();

        function animate() {
            requestAnimationFrame(animate);
            const t = clock.getElapsedTime();
            tooth.rotation.y = t * 0.25;
            tooth.position.y = Math.sin(t * 0.8) * 0.05;

            // hover detection
            raycaster.setFromCamera(pointer, camera);
            const hit = raycaster.intersectObjects(pickables, false)[0]?.object || null;
            if (hit === crown) setActive('crown');
            else if (hit === enamel) setActive('enamel');
            else if (hit === r1 || hit === r2) setActive('roots');
            else if (hovered) setActive(null);

            controls.update();
            renderer.render(scene, camera);
            labelRenderer.render(scene, camera);
        }
        animate();
    </script>
</body>

</html>