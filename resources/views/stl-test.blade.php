<div id="stl-viewer" style="width: 100%; height: 500px; background: #f0f0f0;"></div>

@push('scripts')
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SCRIPTS_AFTER) }}

    <script src="https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.160.0/examples/js/loaders/STLLoader.js"></script>

    <script>
            const container = document.getElementById('stl-viewer');

            if (!container) {
                console.error("No se encontró el contenedor #stl-viewer");
                return;
            }

            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
            camera.position.z = 100;

            const renderer = new THREE.WebGLRenderer({ antialias: true });
            renderer.setSize(container.clientWidth, container.clientHeight);
            container.appendChild(renderer.domElement);

            const loader = new STLLoader();
            loader.load('/storage/trabajos/2025-05-18-Sonia%20Sancho%20Saex%2014714%20UpperJawScan.stl', function (geometry) {
                const material = new THREE.MeshNormalMaterial();
                const mesh = new THREE.Mesh(geometry, material);
                scene.add(mesh);

                function animate() {
                    requestAnimationFrame(animate);
                    mesh.rotation.y += 0.01;
                    renderer.render(scene, camera);
                }

                animate();
            }, undefined, function (error) {
                console.error("Error cargando STL:", error);
            });
    </script>
@endpush
