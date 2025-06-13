<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('logo_yayasan.png') }}" type="image/png" />
    <title>Sistem Informasi Akademik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const alert = document.querySelector('.animate-fade-in-down');
        if (alert) {
            setTimeout(() => {
                alert.classList.add('opacity-0', 'transition-opacity', 'duration-700');
                setTimeout(() => alert.remove(), 700);
            }, 3000); // Hilang dalam 3 detik
        }
    });
    
</script>
<!-- ... head tetap sama ... -->

<body class="bg-green-100 font-[Verdana]">

<!-- Alert -->
@if (session('success'))
    <div class="fixed top-20 left-1/2 transform -translate-x-1/2 bg-green-100 border border-green-400 text-green-700 px-6 py-3 rounded shadow-md z-40 animate-fade-in-down mt-20">
        {{ session('success') }}
    </div>
@endif

<!-- Navbar -->
<header class="fixed top-0 left-0 w-full text-white bg-[#328E6E] shadow-md z-30 font-[Verdana]">
    <div class="max-w-screen-xl mx-auto flex justify-between items-center py-4 px-6">
        <div class="flex items-center gap-2">
            <img src="{{ asset('logo_yayasan.png') }}" alt="Logo" class="w-10 h-10" />
            <div class="leading-tight">
                <div class="font-bold text-sm">Sistem Informasi Akademik</div>
                <div class="font-bold text-sm">Yayasan Insan Madani Mulia</div>
            </div>
        </div>
        <nav class="hidden md:flex gap-4 text-sm">
            <a href="{{ url('/HalamanAwalSIAKAD') }}" class="hover:underline">SIAKAD</a>
        </nav>
    </div>
</header>

<!-- Main Section -->
<section class="relative bg-cover bg-center h-screen pt-24 flex items-center justify-center" style="background-image: url('{{ asset('Banner1.png') }}');">
    <div class="relative z-10 bg-white/90 p-8 sm:p-10 rounded-3xl shadow-xl w-full max-w-md backdrop-blur-md border border-green-200">
        <div class="mb-6 text-center">
            <img src="{{ asset('SIYIMM.png') }}" alt="Logo" class="mx-auto w-20">
            <h2 class="text-3xl font-bold text-green-800 mt-3">Selamat Datang</h2>
            <p class="text-sm text-gray-600">Silakan login untuk melanjutkan</p>
        </div>

        <form action="{{ route('do.loginakademik') }}" method="POST" class="space-y-5">
            @csrf

            @if (session('error'))
                <div class="flex items-start gap-2 text-red-700 bg-red-100 border border-red-400 rounded-md px-4 py-2 animate-shake">
                    <svg class="w-5 h-5 mt-1 shrink-0 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-green-700 mb-1">Username</label>
                <div class="flex items-center border border-green-300 rounded-lg px-3 py-2 shadow-sm">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A11.954 11.954 0 0112 15c2.21 0 4.258.64 5.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" placeholder="Masukkan username"
                        class="w-full bg-transparent focus:outline-none" />
                </div>
                @error('username')
                    <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                        <svg class="w-4 h-4" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-green-700 mb-1">Password</label>
                <div class="flex items-center border border-green-300 rounded-lg px-3 py-2 shadow-sm">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0-1.104-.896-2-2-2s-2 .896-2 2 .896 2 2 2 2-.896 2-2zM2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z"/>
                    </svg>
                    <input id="password" type="password" name="password" placeholder="Masukkan password" class="w-full bg-transparent focus:outline-none" />
                    <button type="button" onclick="togglePassword()" class="ml-2 text-green-600 focus:outline-none">
                        <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition duration-200 shadow-md">
                Login
            </button>
        </form>
    </div>
</section>

<!-- Footer -->
<footer class="bg-[#328E6E] text-white py-4 text-center">
    <p class="italic font-[Verdana]">Copyright © 2025 | Yayasan Insan Madani Mulia</p>
</footer>

<!-- JS Toggle -->
<script>
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.getElementById("eyeIcon");
        const isPassword = passwordInput.type === "password";
        passwordInput.type = isPassword ? "text" : "password";
        eyeIcon.innerHTML = isPassword
            ? `<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.98 9.98 0 012.777-4.243M3 3l18 18" />`
            : `<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    }
</script>

</body>

</html>
