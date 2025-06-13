<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('logo_yayasan.png') }}" type="image/png" />
    <title>Admin Yayasan Insan Madani Mulia</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    @vite('resources/css/app.css') <!-- Pastikan Tailwind sudah dikompilasi -->
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col">

    {{-- Konten Halaman --}}
    <main class="flex-grow ">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gradient-to-r from-emerald-600 to-emerald-400 text-white py-6 shadow-inner">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm font-[Verdana] italic drop-shadow-sm">
                2018 Yayasan Insan Madani Mulia didirikan & 2025 Website Resmi Diluncurkan
            </p>
        </div>
    </footer>

</body>
</html>
