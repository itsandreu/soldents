<div 
  x-data="{
    fov: 60,
    camera: null,
    initViewer() {
      const stlFilePath     = '{{ $url }}';
      const modelColor      = 0xFAF5E6;
      const backgroundColor = 0x0a0a0a;

      const container = document.getElementById('stl-viewer');
      let scene, renderer, controls, mesh, viewerInitialized = false;

      const onWindowResize = () => {
        if (this.camera && renderer) {
          this.camera.aspect = container.clientWidth / container.clientHeight;
          this.camera.updateProjectionMatrix();
          renderer.setSize(container.clientWidth, container.clientHeight);
        }
      };

      const animate = () => {
        requestAnimationFrame(animate);
        controls?.update();
        if (renderer && scene && this.camera) {
          renderer.render(scene, this.camera);
        }
      };

      const loadSTL = () => {
        const loader = new THREE.STLLoader();
        const msg = document.createElement('div');
        Object.assign(msg.style, {
          position: 'absolute',
          top: '50%',
          left: '50%',
          transform: 'translate(-50%,-50%)',
          padding: '1rem',
          background: 'rgba(0,0,0,0.7)',
          color: 'white',
          borderRadius: '0.25rem',
          zIndex: '100',
        });
        msg.textContent = 'Cargando modelo…';
        container.appendChild(msg);

        loader.load(
          stlFilePath,
          (geometry) => {
            container.removeChild(msg);

            // 1) Crear y escalar el mesh
            const material = new THREE.MeshStandardMaterial({
              color: modelColor,
              metalness: 0.2,
              roughness: 0.6,
            });
            mesh = new THREE.Mesh(geometry, material);
            const scaleFactor = 1.2;
            mesh.scale.set(scaleFactor, scaleFactor, scaleFactor);

            // 2) Centrar
            geometry.computeBoundingBox();
            const bbox = geometry.boundingBox;
            const center = new THREE.Vector3();
            bbox.getCenter(center);
            mesh.position.sub(center);
            scene.add(mesh);

            // 3) Calcular distancia de cámara alto/ancho
            const size = new THREE.Vector3();
            bbox.getSize(size);
            const halfW = (size.x * scaleFactor) / 2;
            const halfH = (size.y * scaleFactor) / 2;
            const vFov  = this.camera.fov * (Math.PI / 180);
            const aspect = container.clientWidth / container.clientHeight;
            const hFov = 2 * Math.atan(Math.tan(vFov / 2) * aspect);
            const distY = halfH / Math.tan(vFov / 2);
            const distX = halfW / Math.tan(hFov  / 2);
            const paddingFactor = 1.5;
            const cameraZ = Math.max(distX, distY) * paddingFactor;

            // 4) Posicionar
            this.camera.position.set(0, size.y * 0.05, cameraZ);
            this.camera.lookAt(mesh.position);
            controls.target.copy(mesh.position);
            controls.update();
          },
          (xhr) => {
            if (xhr.lengthComputable) {
              msg.textContent = `Cargando: ${((xhr.loaded / xhr.total) * 100).toFixed(1)}%`;
            }
          },
          (err) => {
            console.error('Error STL:', err);
            msg.textContent = 'Error cargando STL';
            msg.style.background = 'rgba(200,0,0,0.7)';
          }
        );
      };

      const init = () => {
        if (viewerInitialized) { onWindowResize(); return; }
        container.innerHTML = '';
        if (!container.clientWidth || !container.clientHeight) {
          console.warn('#stl-viewer está a 0×0.');
        }

        // Escena
        scene = new THREE.Scene();
        scene.background = new THREE.Color(backgroundColor);

        // Cámara
        this.camera = new THREE.PerspectiveCamera(
          this.fov,
          container.clientWidth / container.clientHeight,
          0.1,
          1000
        );

        // Renderer
        renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(container.clientWidth, container.clientHeight);
        container.appendChild(renderer.domElement);

        // Luces
        scene.add(new THREE.AmbientLight(0xffffff, 0.4));
        const dir = new THREE.DirectionalLight(0xffffff, 0.7);
        dir.position.set(5, 10, 7.5);
        scene.add(dir);
        scene.add(new THREE.HemisphereLight(0xffffbb, 0x080820, 0.7));

        // Controles
        controls = new THREE.OrbitControls(this.camera, renderer.domElement);
        controls.enableDamping = true;
        controls.dampingFactor = 0.05;

        // Carga y animación
        loadSTL();
        animate();

        // Resize
        window.addEventListener('resize', onWindowResize);

        viewerInitialized = true;
      };

      init();
    }
  }"
  x-init="setTimeout(() => initViewer(), 150)"
  x-effect="if (camera) { camera.fov = fov; camera.updateProjectionMatrix(); }"
  class="relative w-full max-w-5xl mx-auto bg-white rounded-lg shadow-lg p-6 h-[80vh]"
>

  {{-- Slider FOV --}}
  <div class="mb-4 text-center ">
    <h1 style="font-size: 1.5rem; font-weight: bold;">
    {{ basename($url) }}
  </h1>
    
  </div>

  {{-- STL Viewer --}}
  <div id="stl-viewer" class="flex w-full h-96"></div>
  <br>
  <label class="text-center block text-sm font-medium text-gray-700 mb-1">
      Campo de Visión: <span x-text="fov"></span>°
    </label>
    <input
      type="range"
      min="10" max="120" step="1"
      x-model.number="fov"
      class="w-full"
    />
</div>

@assets
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/mrdoob/three.js@r128/examples/js/loaders/STLLoader.js"></script>
<script src="https://cdn.jsdelivr.net/gh/mrdoob/three.js@r128/examples/js/controls/OrbitControls.js"></script>
@endassets

<!-- <div id="stl-viewer-container" style="height: 400px;" x-data="{
        initViewer() {
            // --- CONFIGURACIÓN ---
            const stlFilePath = '{{ $url }}';
            const modelColor =  0xf8f8ff; // Color del modelo
            const backgroundColor = 0x0a0a0a; // Color de fondo
            // --- FIN CONFIGURACIÓN ---

            const container = document.getElementById('stl-viewer');
            let scene, camera, renderer, controls, mesh;
            let viewerInitialized = false;

            function init() {
                if (viewerInitialized) {
                    if (renderer && container && camera) {
                        camera.aspect = container.clientWidth / container.clientHeight;
                        camera.updateProjectionMatrix();
                        renderer.setSize(container.clientWidth, container.clientHeight);
                    }
                    return;
                }

                container.innerHTML = '';

                if (container.clientWidth === 0 || container.clientHeight === 0) {
                    console.warn('Advertencia: El contenedor #stl-viewer tiene dimensiones 0x0. El visor 3D podría no mostrarse correctamente. Asegúrate de que el modal esté completamente visible ANTES de llamar a initViewer(). El timeout en x-init podría necesitar ser más largo.');
                }

                // 1. Escena
                scene = new THREE.Scene();
                scene.background = new THREE.Color(backgroundColor);

                // 2. Cámara
                camera = new THREE.PerspectiveCamera(
                    60,
                    container.clientWidth / Math.max(1, container.clientHeight),
                    0.1,
                    1000
                );
                // La posición de la cámara se ajustará después de cargar el modelo

                // 3. Renderer
                renderer = new THREE.WebGLRenderer({ antialias: true });
                renderer.setSize(container.clientWidth, container.clientHeight);
                container.appendChild(renderer.domElement);

                // 4. LUCES (MODIFICADO PARA MEJOR ILUMINACIÓN)
                // ----------------------------------------------------
                // Luz ambiental: ilumina todo de forma pareja.
                const ambientLight = new THREE.AmbientLight(0xffffff, 0.4); // Intensidad más baja (0.4)
                scene.add(ambientLight);

                // Luz direccional principal (como el sol).
                const directionalLight1 = new THREE.DirectionalLight(0xffffff, 0.7); // Intensidad 0.7
                directionalLight1.position.set(5, 10, 7.5); // Posición: X, Y (altura), Z (profundidad)
                scene.add(directionalLight1);

                // Luz hemisférica: simula luz del cielo y luz reflejada del suelo.
                // Muy buena para rellenar sombras de forma natural.
                const hemisphereLight = new THREE.HemisphereLight(
                    0xffffbb, // Color del cielo (blanco amarillento)
                    0x080820, // Color del suelo (azul muy oscuro)
                    0.7       // Intensidad
                );
                scene.add(hemisphereLight);
                // ----------------------------------------------------
                // FIN DE MODIFICACIONES DE LUCES

                // 5. Controles de órbita
                controls = new THREE.OrbitControls(camera, renderer.domElement);
                controls.enableDamping = true;
                controls.dampingFactor = 0.05;

                // 6. Cargar el modelo STL
                loadSTLModel();

                // 7. Bucle de animación
                animate();

                // 8. Manejar redimensionamiento de ventana
                window.addEventListener('resize', onWindowResize, false);
                viewerInitialized = true;
            }

            function loadSTLModel() {
                const loader = new THREE.STLLoader();
                let loadingMessage = document.createElement('div');
                loadingMessage.className = 'viewer-message';
                loadingMessage.textContent = 'Cargando modelo...';
                loadingMessage.style.position = 'absolute';
                loadingMessage.style.top = '50%';
                loadingMessage.style.left = '50%';
                loadingMessage.style.transform = 'translate(-50%, -50%)';
                loadingMessage.style.padding = '20px';
                loadingMessage.style.backgroundColor = 'rgba(0,0,0,0.7)';
                loadingMessage.style.color = 'white';
                loadingMessage.style.borderRadius = '5px';
                loadingMessage.style.textAlign = 'center';
                loadingMessage.style.zIndex = '100';
                if (container) container.appendChild(loadingMessage);

                loader.load(
                    stlFilePath,
                    function (geometry) {
                        if (container && container.contains(loadingMessage)) {
                            container.removeChild(loadingMessage);
                        }
                        // Ajustes al material para reaccionar mejor a las luces
                        const material = new THREE.MeshStandardMaterial({
                            color: modelColor,
                            metalness: 0.2, // Menos metálico para la mayoría de los objetos
                            roughness: 0.6, // Un poco menos rugoso para reflejos suaves
                            flatShading: false // Para sombreado suave
                        });
                        mesh = new THREE.Mesh(geometry, material);
                        geometry.computeBoundingBox();
                        const boundingBox = geometry.boundingBox;
                        const center = new THREE.Vector3();
                        boundingBox.getCenter(center);
                        mesh.position.sub(center);
                        scene.add(mesh);

                        const size = new THREE.Vector3();
                        boundingBox.getSize(size);
                        const maxDim = Math.max(size.x, size.y, size.z);
                        const fov = camera.fov * (Math.PI / 180);
                        let cameraZ = Math.abs(maxDim / 2 / Math.tan(fov / 2));
                        
                        // Ajustar el zoom inicial de la cámara y su posición Y
                        cameraZ *= 2.0; // Zoom un poco más cercano
                        camera.position.set(
                            0,                      // Centrado en X
                            maxDim * 0.05,           // Ligeramente elevado en Y
                            cameraZ                 // Distancia Z calculada
                        );
                        
                        if (controls) {
                            controls.target.copy(mesh.position); // Asegura que los controles orbitan alrededor del objeto
                            controls.update();
                        }
                        camera.lookAt(mesh.position); // Asegura que la cámara apunta al objeto
                    },
                    (xhr) => {
                        if (xhr.lengthComputable) {
                            const percentComplete = xhr.loaded / xhr.total * 100;
                            loadingMessage.textContent = `Cargando: ${Math.round(percentComplete, 2)}%`;
                        }
                    },
                    (error) => {
                        console.error('Error al cargar el archivo STL:', error);
                        loadingMessage.textContent = `Error al cargar ${stlFilePath}. Verifica la ruta y la consola.`;
                        loadingMessage.style.backgroundColor = 'rgba(200,0,0,0.7)';
                    }
                );
            }

            function animate() {
                requestAnimationFrame(animate);
                if (controls) controls.update();
                if (renderer && scene && camera) renderer.render(scene, camera);
            }

            function onWindowResize() {
                if (camera && renderer && container && container.clientWidth > 0 && container.clientHeight > 0) {
                    camera.aspect = container.clientWidth / container.clientHeight;
                    camera.updateProjectionMatrix();
                    renderer.setSize(container.clientWidth, container.clientHeight);
                }
            }
            init();
        }
    }" x-init="setTimeout(() => initViewer(), 150)"></div>
</div>

@assets
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/mrdoob/three.js@r128/examples/js/loaders/STLLoader.js"></script>
<script src="https://cdn.jsdelivr.net/gh/mrdoob/three.js@r128/examples/js/controls/OrbitControls.js"></script>
@endassets -->



<!-- 

 x-data="{
        initViewer() {
            // --- CONFIGURACIÓN ---
            const stlFilePath = '{{ $url }}';
            const modelColor =  0xf8f8ff; // Color del modelo
            const backgroundColor = 0x0a0a0a; // Color de fondo
            // --- FIN CONFIGURACIÓN ---

            const container = document.getElementById('stl-viewer');
            let scene, camera, renderer, controls, mesh;
            let viewerInitialized = false;

            function init() {
                if (viewerInitialized) {
                    if (renderer && container && camera) {
                        camera.aspect = container.clientWidth / container.clientHeight;
                        camera.updateProjectionMatrix();
                        renderer.setSize(container.clientWidth, container.clientHeight);
                    }
                    return;
                }

                container.innerHTML = '';

                if (container.clientWidth === 0 || container.clientHeight === 0) {
                    console.warn('Advertencia: El contenedor #stl-viewer tiene dimensiones 0x0. El visor 3D podría no mostrarse correctamente. Asegúrate de que el modal esté completamente visible ANTES de llamar a initViewer(). El timeout en x-init podría necesitar ser más largo.');
                }

                // 1. Escena
                scene = new THREE.Scene();
                scene.background = new THREE.Color(backgroundColor);

                // 2. Cámara
                camera = new THREE.PerspectiveCamera(
                    60,
                    container.clientWidth / Math.max(1, container.clientHeight),
                    0.1,
                    1000
                );
                // La posición de la cámara se ajustará después de cargar el modelo

                // 3. Renderer
                renderer = new THREE.WebGLRenderer({ antialias: true });
                renderer.setSize(container.clientWidth, container.clientHeight);
                container.appendChild(renderer.domElement);

                // 4. LUCES (MODIFICADO PARA MEJOR ILUMINACIÓN)
                // ----------------------------------------------------
                // Luz ambiental: ilumina todo de forma pareja.
                const ambientLight = new THREE.AmbientLight(0xffffff, 0.4); // Intensidad más baja (0.4)
                scene.add(ambientLight);

                // Luz direccional principal (como el sol).
                const directionalLight1 = new THREE.DirectionalLight(0xffffff, 0.7); // Intensidad 0.7
                directionalLight1.position.set(5, 10, 7.5); // Posición: X, Y (altura), Z (profundidad)
                scene.add(directionalLight1);

                // Luz hemisférica: simula luz del cielo y luz reflejada del suelo.
                // Muy buena para rellenar sombras de forma natural.
                const hemisphereLight = new THREE.HemisphereLight(
                    0xffffbb, // Color del cielo (blanco amarillento)
                    0x080820, // Color del suelo (azul muy oscuro)
                    0.7       // Intensidad
                );
                scene.add(hemisphereLight);
                // ----------------------------------------------------
                // FIN DE MODIFICACIONES DE LUCES

                // 5. Controles de órbita
                controls = new THREE.OrbitControls(camera, renderer.domElement);
                controls.enableDamping = true;
                controls.dampingFactor = 0.05;

                // 6. Cargar el modelo STL
                loadSTLModel();

                // 7. Bucle de animación
                animate();

                // 8. Manejar redimensionamiento de ventana
                window.addEventListener('resize', onWindowResize, false);
                viewerInitialized = true;
            }

            function loadSTLModel() {
                const loader = new THREE.STLLoader();
                let loadingMessage = document.createElement('div');
                loadingMessage.className = 'viewer-message';
                loadingMessage.textContent = 'Cargando modelo...';
                loadingMessage.style.position = 'absolute';
                loadingMessage.style.top = '50%';
                loadingMessage.style.left = '50%';
                loadingMessage.style.transform = 'translate(-50%, -50%)';
                loadingMessage.style.padding = '20px';
                loadingMessage.style.backgroundColor = 'rgba(0,0,0,0.7)';
                loadingMessage.style.color = 'white';
                loadingMessage.style.borderRadius = '5px';
                loadingMessage.style.textAlign = 'center';
                loadingMessage.style.zIndex = '100';
                if (container) container.appendChild(loadingMessage);

                loader.load(
                    stlFilePath,
                    function (geometry) {
                        if (container && container.contains(loadingMessage)) {
                            container.removeChild(loadingMessage);
                        }
                        // Ajustes al material para reaccionar mejor a las luces
                        const material = new THREE.MeshStandardMaterial({
                            color: modelColor,
                            metalness: 0.2, // Menos metálico para la mayoría de los objetos
                            roughness: 0.6, // Un poco menos rugoso para reflejos suaves
                            flatShading: false // Para sombreado suave
                        });
                        mesh = new THREE.Mesh(geometry, material);
                        geometry.computeBoundingBox();
                        const boundingBox = geometry.boundingBox;
                        const center = new THREE.Vector3();
                        boundingBox.getCenter(center);
                        mesh.position.sub(center);
                        scene.add(mesh);

                        const size = new THREE.Vector3();
                        boundingBox.getSize(size);
                        const maxDim = Math.max(size.x, size.y, size.z);
                        const fov = camera.fov * (Math.PI / 180);
                        let cameraZ = Math.abs(maxDim / 2 / Math.tan(fov / 2));
                        
                        // Ajustar el zoom inicial de la cámara y su posición Y
                        cameraZ *= 2.0; // Zoom un poco más cercano
                        camera.position.set(
                            0,                      // Centrado en X
                            maxDim * 0.05,           // Ligeramente elevado en Y
                            cameraZ                 // Distancia Z calculada
                        );
                        
                        if (controls) {
                            controls.target.copy(mesh.position); // Asegura que los controles orbitan alrededor del objeto
                            controls.update();
                        }
                        camera.lookAt(mesh.position); // Asegura que la cámara apunta al objeto
                    },
                    (xhr) => {
                        if (xhr.lengthComputable) {
                            const percentComplete = xhr.loaded / xhr.total * 100;
                            loadingMessage.textContent = `Cargando: ${Math.round(percentComplete, 2)}%`;
                        }
                    },
                    (error) => {
                        console.error('Error al cargar el archivo STL:', error);
                        loadingMessage.textContent = `Error al cargar ${stlFilePath}. Verifica la ruta y la consola.`;
                        loadingMessage.style.backgroundColor = 'rgba(200,0,0,0.7)';
                    }
                );
            }

            function animate() {
                requestAnimationFrame(animate);
                if (controls) controls.update();
                if (renderer && scene && camera) renderer.render(scene, camera);
            }

            function onWindowResize() {
                if (camera && renderer && container && container.clientWidth > 0 && container.clientHeight > 0) {
                    camera.aspect = container.clientWidth / container.clientHeight;
                    camera.updateProjectionMatrix();
                    renderer.setSize(container.clientWidth, container.clientHeight);
                }
            }
            init();
        }
    }"  -->