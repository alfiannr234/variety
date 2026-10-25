@props(['hideNavbar' => false])

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Variety' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased flex flex-col min-h-screen">
    <a href="#main-content"
        class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-full focus:translate-y-0 z-[9999] px-6 py-3 bg-slate-900 text-white font-bold rounded-b-xl shadow-lg transition-transform duration-300 focus:outline-none focus:ring-4 focus:ring-emerald-500">
        Kembali ke menu utama
    </a>

    @if(!$hideNavbar)
        <x-navbar />
    @endif

    {{-- @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 mt-4">
        <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-xl font-bold shadow-sm">
            {{ session('success') }}
        </div>
    </div>
    @endif --}}

    {{-- @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 mt-4">
        <div class="p-4 bg-rose-100 border border-rose-300 text-rose-800 rounded-xl font-bold shadow-sm">
            {{ session('error') }}
        </div>
    </div>
    @endif --}}

    <main class="flex-grow">
        {{ $slot }}
    </main>

</body>

</html>