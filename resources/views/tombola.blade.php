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
<body class="font-sans antialiased patron-cruces min-h-screen flex flex-col relative overflow-x-hidden overflow-y-auto">

    <!-- Texto Superior Minimalista -->
    <div class="w-full text-center pt-8 pb-4 px-4 z-50">
        <h2 class="text-sm md:text-base font-bold text-gray-300 tracking-widest uppercase drop-shadow-md">Centro Nacional de Formación de Maestras y Maestros</h2>
        <p class="text-[#00aeef] font-semibold text-xs md:text-sm drop-shadow-md mt-1">Sede Centro Tecnológico Olof Palme</p>
        <a href="{{ route('dashboard') }}" class="inline-block mt-4 text-gray-400 hover:text-white text-xs transition-colors border border-gray-600 hover:border-white rounded-full px-6 py-2 bg-white/5">
            Volver al Panel
        </a>
    </div>

    <!-- Contenedor Principal (Con pb-24 para dar espacio al scroll debajo del botón) -->
    <div class="flex-grow flex items-center justify-center w-full px-4 pt-12 pb-24">
        <div class="z-10 text-center w-full max-w-5xl">
            
            <h1 class="text-6xl md:text-7xl font-black text-white mb-4 drop-shadow-[0_5px_5px_rgba(0,0,0,0.5)] tracking-widest uppercase">
                GRAN TÓMBOLA
            </h1>
            <p class="text-[#00aeef] text-3xl mb-16 font-bold drop-shadow-lg">XII Congreso Nacional de Docentes</p>

            <!-- Tarjeta del Ganador -->
            <div id="winner-card" class="hidden mx-auto w-full bg-white/95 backdrop-blur-sm rounded-3xl p-12 shadow-[0_25px_60px_rgba(0,0,0,0.7)] transform transition-all duration-700 scale-95 opacity-0 border-b-[12px] border-[#e91e63] mb-12">
                <h2 class="text-lg font-black text-gray-400 uppercase tracking-[0.3em] mb-6 flex items-center justify-center space-x-3">
                    <span>🏆</span>
                    <span>¡Felicidades al Ganador!</span>
                    <span>🏆</span>
                </h2>
                
                <div id="winner-main-info" class="text-4xl md:text-5xl lg:text-6xl font-black text-[#0b4377] mb-8 leading-tight drop-shadow-sm"></div>
                
                <div class="flex justify-center space-x-4 mt-6 border-t border-gray-200 pt-8">
                    <span id="winner-depto" class="inline-block bg-gray-100 text-gray-700 px-6 py-2.5 rounded-full text-base font-bold shadow-inner border border-gray-200"></span>
                    <span id="winner-tipo" class="inline-block bg-[#00aeef] text-white px-6 py-2.5 rounded-full text-base font-bold shadow-md"></span>
                </div>
            </div>

            <!-- Animación de Carga (Ajustada con w-32 h-32 explícitos para no deformarse) -->
            <div id="loading-animation" class="hidden my-16">
                <div class="relative w-32 h-32 mx-auto">
                    <div class="absolute inset-0 border-4 border-gray-700 rounded-full"></div>
                    <div class="absolute inset-0 border-4 border-[#e91e63] rounded-full border-t-transparent animate-spin"></div>
                </div>
                <p class="text-white mt-8 text-2xl font-bold animate-pulse tracking-wider">Generando ganador al azar...</p>
            </div>

            <!-- Botón de Acción -->
            <button id="spin-btn" class="mt-8 bg-[#e91e63] hover:bg-[#c2185b] text-white text-3xl font-black py-6 px-16 rounded-full shadow-[0_0_40px_rgba(233,30,99,0.8)] transition-all transform hover:scale-105 active:scale-95 inline-flex items-center justify-center mx-auto space-x-4 border-2 border-white/20">
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

            btn.disabled = true;
            winnerCard.classList.add('hidden', 'scale-95', 'opacity-0');
            winnerCard.classList.remove('scale-100', 'opacity-100');
            
            // Usamos block para la animación de carga en lugar de flex para evitar conflictos
            loading.classList.remove('hidden');
            loading.classList.add('block'); 
            
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
                        loading.classList.remove('block');
                        btn.classList.remove('hidden');
                        btn.disabled = false;
                        return;
                    }

                    setTimeout(() => {
                        loading.classList.add('hidden');
                        loading.classList.remove('block');
                        
                        const mainInfoText = `${data.nombre_completo} - ${data.centro_procedencia}`;
                        document.getElementById('winner-main-info').textContent = mainInfoText;
                        
                        document.getElementById('winner-depto').textContent = `📍 ${data.departamento}`;
                        document.getElementById('winner-tipo').textContent = data.tipo_procedencia;

                        winnerCard.classList.remove('hidden');
                        
                        setTimeout(() => {
                            winnerCard.classList.remove('scale-95', 'opacity-0');
                            winnerCard.classList.add('scale-100', 'opacity-100');
                            
                            winnerCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            
                            triggerConfetti();
                        }, 50);

                        btn.disabled = false;
                        btn.classList.remove('hidden');
                        btn.innerHTML = "<span>🔄</span><span>Sacar Otro Ganador</span>";
                    }, 3000); 
                })
                .catch(error => {
                    console.error('Error:', error);
                    loading.classList.add('hidden');
                    loading.classList.remove('block');
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