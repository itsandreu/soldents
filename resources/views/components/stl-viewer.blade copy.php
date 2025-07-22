<div id="stl-viewer-container" x-data="{
        initViewer() {
            // --- CONFIGURACIÓN ---
            const stlFilePath = 'https://www.ricardoandreu.com/storage/trabajos/prueba.stl'; // ¡IMPORTANTE! Reemplaza con la ruta a tu archivo STL
            // Puedes usar una URL completa si el archivo está en otro servidor (CORS debe estar configurado)
            // Ejemplo: const stlFilePath = 'https://storage.googleapis.com/ucloud-v3/ccab50f18fb14c91ccca300a.stl';

            const modelColor = 0x007bff; // Color del modelo (azul)
            const backgroundColor = 0xeeeeee; // Color de fondo del canvas
            // --- FIN CONFIGURACIÓN ---

            const container = document.getElementById('stl-viewer');
            let scene, camera, renderer, controls, mesh;

            function init() {

                // 1. Escena
                scene = new THREE.Scene();
                scene.background = new THREE.Color(backgroundColor);

                // 2. Cámara
                camera = new THREE.PerspectiveCamera(
                    75, // Campo de visión (FOV)
                    container.clientWidth / container.clientHeight, // Relación de aspecto
                    0.1, // Plano de recorte cercano
                    1000 // Plano de recorte lejano
                );
                // Posición inicial de la cámara (se ajustará después de cargar el modelo)
                camera.position.set(0, 0, 100);

                // 3. Renderer
                renderer = new THREE.WebGLRenderer({ antialias: true });
                renderer.setSize(container.clientWidth, container.clientHeight);
                container.appendChild(renderer.domElement);

                // 4. Luces
                const ambientLight = new THREE.AmbientLight(0xffffff, 0.6); // Luz ambiental suave
                scene.add(ambientLight);
                const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8); // Luz direccional
                directionalLight.position.set(1, 1, 1).normalize();
                scene.add(directionalLight);

                // 5. Controles de órbita
                controls = new THREE.OrbitControls(camera, renderer.domElement);
                controls.enableDamping = true; // Suaviza el movimiento
                controls.dampingFactor = 0.05;

                // 6. Cargar el modelo STL
                loadSTLModel();

                // 7. Bucle de animación
                animate();

                // 8. Manejar redimensionamiento de ventana
                window.addEventListener('resize', onWindowResize, false);
            }

            function loadSTLModel() {
                const loader = new THREE.STLLoader();

                // Mostrar mensaje de carga
                let loadingMessage = document.createElement('div');
                loadingMessage.className = 'viewer-message';
                loadingMessage.textContent = 'Cargando modelo...';
                container.appendChild(loadingMessage);

                loader.load(
                    stlFilePath,
                    function (geometry) {
                        // Eliminar mensaje de carga
                        container.removeChild(loadingMessage);

                        const material = new THREE.MeshStandardMaterial({
                            color: modelColor,
                            metalness: 0.1,
                            roughness: 0.75
                        });
                        mesh = new THREE.Mesh(geometry, material);

                        // Centrar la geometría y ajustar la cámara
                        geometry.computeBoundingBox();
                        const boundingBox = geometry.boundingBox;
                        const center = new THREE.Vector3();
                        boundingBox.getCenter(center);

                        mesh.position.sub(center); // Mueve el origen del objeto a su centro geométrico
                        scene.add(mesh);

                        // Ajustar la cámara para que el objeto sea visible
                        const size = new THREE.Vector3();
                        boundingBox.getSize(size);
                        const maxDim = Math.max(size.x, size.y, size.z);
                        const fov = camera.fov * (Math.PI / 180);
                        let cameraZ = Math.abs(maxDim / 2 / Math.tan(fov / 2));
                        cameraZ *= 1.75; // Añadir un poco de espacio (zoom out)

                        camera.position.set(
                            center.x, // Mantener X y Y del centro (si no se movió mesh)
                            center.y + maxDim * 0.2, // Un poco por encima
                            cameraZ
                        );

                        // Asegurar que los controles apunten al centro del objeto si este se movió
                        controls.target.copy(mesh.position); // O scene.position si el objeto está en 0,0,0
                        controls.update();
                    },
                    (xhr) => { // Progreso de carga
                        if (xhr.lengthComputable) {
                            const percentComplete = xhr.loaded / xhr.total * 100;
                            loadingMessage.textContent = `Cargando: ${Math.round(percentComplete, 2)}%`;
                        }
                    },
                    (error) => { // Error de carga
                        console.error('Error al cargar el archivo STL:', error);
                        loadingMessage.textContent = `Error al cargar ${stlFilePath}. Verifica la ruta y la consola.`;
                        loadingMessage.style.backgroundColor = 'rgba(200,0,0,0.7)';
                    }
                );
            }

            function animate() {
                requestAnimationFrame(animate);
                controls.update(); // Necesario si enableDamping es true
                renderer.render(scene, camera);
            }

            function onWindowResize() {
                camera.aspect = container.clientWidth / container.clientHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(container.clientWidth, container.clientHeight);
            }

            // Iniciar todo
            init();
        }
    }" x-init="initViewer()">
    <div id="stl-viewer" style="width: 400px; height: 300px; border: 1px solid black;">
        STL Viewer Placeholder
    </div>
</div>

@assets
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/mrdoob/three.js@r128/examples/js/loaders/STLLoader.js"></script>
<script src="https://cdn.jsdelivr.net/gh/mrdoob/three.js@r128/examples/js/controls/OrbitControls.js"></script>
@endassets

@script
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
    });

</script>
@endscript
















<!-- Importa Three.js y los loaders/controls necesarios desde un CDN -->
<!-- Asegúrate de que la versión (r128) sea compatible o usa una más reciente -->


<!-- 
<script src="{{ asset('js/three/three.min.js') }}"></script>
<script src="{{ asset('js/three/STLLoader.js') }}"></script>
<script src="{{ asset('js/three/OrbitControls.js') }}"></script> 
-->