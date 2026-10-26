<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Administración') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased bg-[#0f172a] text-gray-100 min-h-screen flex flex-col">
        
        <nav class="bg-[#1e293b] border-b border-gray-700 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center space-x-6">
                        
                        <div class="flex items-center space-x-4">
                            <img src="{{ asset('images/logo-inatec-blanco.png') }}" alt="INATEC" class="h-10 object-contain">
                            <img src="{{ asset('images/logo-gobierno-blanco.png') }}" alt="Gobierno" class="h-10 object-contain">
                        </div>
                        
                        <div class="hidden md:block">
                            <h1 class="text-white font-bold text-sm tracking-wide">Centro Nacional de Formación de Maestras y Maestros</h1>
                            <p class="text-[#00aeef] text-xs font-semibold">Sede Centro Tecnológico Olof Palme</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">Cerrar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow">
            {{ $slot }}
        </main>

    </body>
</html>