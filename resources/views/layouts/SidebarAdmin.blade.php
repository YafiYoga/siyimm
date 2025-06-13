@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $namaLengkap = $user->username;
    $foto = null;

    if (in_array($user->role, ['admin', 'yayasan', 'lembaga_sd', 'lembaga_smp', 'staff_sd', 'staff_smp', 'guru_sd', 'guru_smp']) && $user->pegawai) {
        $namaLengkap = $user->pegawai->nama_lengkap ?? $user->username;
        $foto = $user->pegawai->foto ?? null;
    } elseif (str_starts_with($user->role, 'walimurid') && $user->siswa) {
        $namaLengkap = $user->siswa->nama_siswa ?? $user->username;
        $foto = $user->siswa->foto ?? null;
    }

    $fotoUrl = $foto ? asset('storage/' . $foto) : asset('/user.png');
@endphp

<!-- Sidebar Admin (Modern) -->
<div class="fixed top-16 left-0 w-64 h-[calc(100vh-4rem)] bg-gray-900 p-4 overflow-y-auto z-40 font-[Verdana] shadow-xl border-r border-emerald-600 space-y-6">

    <!-- Profil Pengguna -->
    <div class="text-center text-white">
        <img src="{{ $fotoUrl }}" alt="Foto Profil" class="w-16 h-16 mx-auto rounded-full border-4 border-emerald-500 shadow-lg object-cover">
        <h2 class="mt-2 font-semibold text-base truncate">{{ $namaLengkap }}</h2>
        <p class="italic text-sm text-gray-400">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>
    </div>

    <!-- Input Pencarian -->
    <div class="relative">
        <input type="text" id="searchMenu" placeholder="Cari menu..." 
            class="w-full pr-10 pl-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-sm text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all" />
        <img src="/search.png" alt="Search Icon" class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 opacity-70">
    </div>

    <!-- Pesan Tidak Ditemukan -->
    <div id="notFoundMessage" class="hidden mt-2 px-4 py-3 rounded-md bg-red-500 text-white text-sm animate-fade-in">
        <div class="font-bold">Menu tidak ditemukan</div>
        <div class="text-xs">Silakan coba kata kunci lain.</div>
    </div>

    <!-- Menu Navigasi -->
    <nav class="flex flex-col space-y-2 text-sm text-white">
        @if(Route::has('setting'))
        <a href="{{ route('setting') }}" data-label="setting akun anda" class="menu-link flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-emerald-600 transition">
            <img src="/setting.png" class="w-5 h-5" alt="Setting">
            <span>Setting Akun Anda</span>
        </a>
        @endif

        <a href="{{ url('/DashboardAdmin') }}" data-label="dashboard admin" class="menu-link flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-emerald-600 transition">
            <img src="/home.png" class="w-5 h-5" alt="Dashboard Admin">
            <span>Dashboard Admin</span>
        </a>

        <a href="{{ url('/LihatAkunPenggunaAdmin') }}" data-label="lihat akun yayasan" class="menu-link flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-emerald-600 transition">
            <img src="/Lihat.png" class="w-5 h-5" alt="Lihat Akun">
            <span>Lihat Seluruh Akun Pengguna</span>
        </a>

        <a href="{{ url('/TambahAkunAdmin') }}" data-label="tambah pengguna" class="menu-link flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-emerald-600 transition">
            <img src="/Tambah.png" class="w-5 h-5" alt="Tambah Pengguna">
            <span>Tambah Pengguna</span>
        </a>
    </nav>
</div>

<!-- Alpine.js -->
<script src="//unpkg.com/alpinejs" defer></script>

<!-- Script Pencarian Menu -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchMenu');
    const menuLinks = document.querySelectorAll('.menu-link');
    const notFoundMsg = document.getElementById('notFoundMessage');

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        let foundAny = false;

        menuLinks.forEach(link => {
            const label = link.getAttribute('data-label')?.toLowerCase() || '';
            if (label.includes(query)) {
                link.closest('a').style.display = '';
                foundAny = true;
            } else {
                link.closest('a').style.display = 'none';
            }
        });

        notFoundMsg.classList.toggle('hidden', foundAny);
    });
});
</script>
