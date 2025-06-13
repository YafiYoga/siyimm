@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!-- Navbar -->
<div class="fixed top-0 left-0 w-full h-16 bg-gradient-to-r from-emerald-600 to-emerald-400 flex items-center justify-between px-6 shadow-md font-[Verdana] text-white">
    
    <!-- Kiri: Menu, Logo, dan Menu Absensi -->
    <div class="flex items-center gap-4 font-[Verdana] text-white">
        <!-- Tombol Menu -->
        <!-- Bisa kamu tambahkan di sini jika ada -->

        <!-- Logo dan Judul -->
        <div class="flex items-center gap-3">
            <img src="/SIYIMM2.png" alt="SYIMM Logo" class="h-7">
            <span class="text-lg font-semibold italic tracking-wide">Dashboard</span>
        </div>

        <!-- Menu Absensi Pegawai (DIPERBARUI) -->
        <a href="{{ route('absensi.index') }}" 
           class="ml-6 flex items-center gap-2 text-white hover:text-emerald-200 font-semibold transition duration-200 transform hover:scale-105">
            <i class="fas fa-calendar-check text-lg"></i>
            <span>Absensi Pegawai</span>
        </a>
    </div>

    <!-- Kanan: Tanggal, User, Logout -->
    <div class="flex items-center gap-6 text-sm">
        <!-- Tanggal -->
        <div class="flex items-center gap-2">
            <i class="fas fa-calendar-alt text-white"></i>
            <span class="italic text-white">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
        </div>

        <!-- User Info -->
        <div class="flex items-center gap-3">
            <i class="fas fa-user text-white"></i>
            @if (Auth::user()->foto)
                <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto Profil" class="w-7 h-7 rounded-full object-cover border-2 border-white shadow-md">
            @endif
            <span class="font-medium">{{ Auth::user()->username }}</span>
        </div>

        <!-- Logout Button -->
        <button onclick="confirmLogout(event)" class="flex items-center gap-2 text-white hover:text-emerald-200 hover:scale-105 transition duration-200">
            <i class="fas fa-sign-out-alt"></i>
            <span class="font-medium">Log Out</span>
        </button>
    </div>
</div>

<!-- Form Logout -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script Konfirmasi Logout -->
<script>
    function confirmLogout(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Yakin ingin logout?',
            text: "Anda akan diarahkan ke halaman login.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981', // hijau emerald
            cancelButtonColor: '#ef4444',  // merah
            confirmButtonText: 'Ya, logout!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>

<!-- Font Awesome CDN (pastikan ini sudah dimasukkan di layout utama jika belum) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
