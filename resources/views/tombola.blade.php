<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gran Tómbola - XII Congreso Nacional de Docentes</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .patron-cruces {
            background-color: #0f172a;
            background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 18h-2v-2h2v2zm-2 2v2h2v-2h-2zm2 2h2v-2h-2v2zm2-2v-2h-2v2h2z' fill='%231e293b' fill-opacity='1' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="font-sans antialiased patron-cruces min-h-screen flex flex-col relative overflow-hidden">
    
    <!-- Encabezado Superior (Solo Logos institucionales) -->
    <nav class="absolute top-0 w-full p-6 z-50 flex items-center justify-between pointer-events-none">
        <div class="flex items-center space-x-6">
            <div class="flex items-center space-x-4">
                <!-- Asegúrate de tener estas imágenes en public/images/ -->
                <img src="{{ asset('images/logo-inatec-blanco.png') }}" alt="INATEC" class="h-12 object-contain">
                <img src="{{ asset('images/logo-gobierno-blanco.png') }}" alt="Gobierno" class="h-12 object-contain">
            </div>
            <div class="hidden md:block">
                <h1 class="text-white font-extrabold text-lg tracking-wide drop-shadow-md">Centro Nacional de Formación de Maestras y Maestros</h1>
                <p class="text-[#00aeef] text-sm font-bold drop-shadow-md">Sede Centro Tecnológico Olof Palme</p>
            </div>
        </div>
        <!-- Enlace sutil para el administrador (solo por si necesitas regresar sin usar las flechas del navegador) -->
        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-400 text-xs pointer-events-auto transition-colors font-semibold">Volver</a>
    </nav>

    <!-- Contenedor Principal de la Tómbola -->
    <div class="flex-grow flex items-center justify-center w-full px-4 mt-20">
        <div class="z-10 text-center w-full max-w-5xl">
            
            <h1 class="text-6xl md:text-7xl font-black text-white mb-4 drop-shadow-[0_5px_5px_rgba(0,0,0,0.5)] tracking-widest uppercase">
                GRAN TÓMBOLA
            </h1>
            <p class="text-[#00aeef] text-3xl mb-16 font-bold drop-shadow-lg">XII Congreso Nacional de Docentes</p>

            <!-- Tarjeta del Ganador -->
            <div id="winner-card" class="hidden mx-auto w-full bg-white/95 backdrop-blur-sm rounded-3xl p-12 shadow-[0_25px_60px_rgba(0,0,0,0.7)] transform transition-all duration-700 scale-95 opacity-0 border-b-[12px] border-[#e91e63]">
                <h2 class="text-lg font-black text-gray-400 uppercase tracking-[0.3em] mb-6 flex items-center justify-center space-x-3">
                    <span>🏆</span>
                    <span>¡Felicidades al Ganador!</span>
                    <span>🏆</span>
                </h2>
                
                <!-- Nombre y Centro en una sola línea -->
                <div id="winner-main-info" class="text-4xl md:text-5xl lg:text-6xl font-black text-[#0b4377] mb-8 leading-tight drop-shadow-sm"></div>
                
                <!-- Metadatos de procedencia (Insignias) -->
                <div class="flex justify-center space-x-4 mt-6 border-t border-gray-200 pt-8">
                    <span id="winner-depto" class="inline-block bg-gray-100 text-gray-700 px-6 py-2.5 rounded-full text-base font-bold shadow-inner border border-gray-200"></span>
                    <span id="winner-tipo" class="inline-block bg-[#00aeef] text-white px-6 py-2.5 rounded-full text-base font-bold shadow-md"></span>
                </div>
            </div>

            <!-- Loader / Suspenso -->
            <div id="loading-animation" class="hidden my-16">
                <div class="relative w-32 h-32 mx-auto">
                    <div class="absolute inset-0 border-4 border-gray-700 rounded-full"></div>
                    <div class="absolute inset-0 border-4 border-[#e91e63] rounded-full border-t-transparent animate-spin"></div>
                </div>
                <p class="text-white mt-8 text-2xl font-bold animate-pulse tracking-wider">Generando ganador al azar...</p>
            </div>

            <!-- Botón de Acción -->
            <button id="spin-btn" class="mt-12 bg-[#e91e63] hover:bg-[#c2185b] text-white text-3xl font-black py-6 px-16 rounded-full shadow-[0_0_40px_rgba(233,30,99,0.8)] transition-all transform hover:scale-105 active:scale-95 flex items-center justify-center mx-auto space-x-4 border-2 border-white/20">
                <span>🎉</span>
                <span>Sacar Ganador</span>
                <span>🎉</span>
            </button>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        document.getElementById('spin-btn').addEventListener('click', function() {
            const btn = this;
            const winnerCard = document.getElementById('winner-card');
            const loading = document.getElementById('loading-animation');

            // Preparar UI para la animación
            btn.disabled = true;
            winnerCard.classList.add('hidden', 'scale-95', 'opacity-0');
            winnerCard.classList.remove('scale-100', 'opacity-100');
            loading.classList.remove('hidden');
            btn.classList.add('hidden');

            fetch('{{ route("tombola.winner") }}')
                .then(response => response.json())
                .then(data => {
                    if(data.error) {
                        Swal.fire({ 
                            icon: 'warning', 
                            title: 'Aviso', 
                            text: data.error, 
                            confirmButtonColor: '#e91e63', 
                            background: '#1e293b', 
                            color: '#fff' 
                        });
                        loading.classList.add('hidden');
                        btn.classList.remove('hidden');
                        btn.disabled = false;
                        return;
                    }

                    // Suspenso de 3 segundos
                    setTimeout(() => {
                        loading.classList.add('hidden');
                        
                        // Lógica de concatenación (Nombre + Centro de Procedencia)
                        const mainInfoText = `${data.nombre_completo} - ${data.centro_procedencia}`;
                        document.getElementById('winner-main-info').textContent = mainInfoText;
                        
                        // Insignias inferiores
                        document.getElementById('winner-depto').textContent = `📍 ${data.departamento}`;
                        document.getElementById('winner-tipo').textContent = data.tipo_procedencia;

                        winnerCard.classList.remove('hidden');
                        
                        // Pequeño delay para permitir que el display:block se aplique antes de animar
                        setTimeout(() => {
                            winnerCard.classList.remove('scale-95', 'opacity-0');
                            winnerCard.classList.add('scale-100', 'opacity-100');
                            triggerConfetti();
                        }, 50);

                        // Restaurar botón
                        btn.disabled = false;
                        btn.classList.remove('hidden');
                        btn.innerHTML = "<span>🔄</span><span>Sacar Otro Ganador</span>";
                    }, 3000); 
                })
                .catch(error => {
                    console.error('Error:', error);
                    loading.classList.add('hidden');
                    btn.classList.remove('hidden');
                    btn.disabled = false;
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Error de conexión', 
                        text: 'Hubo un problema al conectar con el servidor.', 
                        confirmButtonColor: '#e91e63' 
                    });
                });
        });

        function triggerConfetti() {
            var duration = 5000;
            var end = Date.now() + duration;

            (function frame() {
                confetti({ 
                    particleCount: 8, 
                    angle: 60, 
                    spread: 65, 
                    origin: { x: 0 }, 
                    colors: ['#e91e63', '#00aeef', '#ffffff', '#0b4377'] 
                });
                confetti({ 
                    particleCount: 8, 
                    angle: 120, 
                    spread: 65, 
                    origin: { x: 1 }, 
                    colors: ['#e91e63', '#00aeef', '#ffffff', '#0b4377'] 
                });
                if (Date.now() < end) requestAnimationFrame(frame);
            }());
        }
    </script>
</body>
</html>