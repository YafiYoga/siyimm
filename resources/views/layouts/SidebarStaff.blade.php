@php
 

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

<!-- Sidebar Modern -->
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
            class="w-full pr-10 pl-4 py-3 rounded-xl bg-gray-800 border border-gray-700 text-white placeholder-white/70
                   focus:outline-none focus:ring-2 focus:ring-white focus:border-white transition-shadow duration-300 shadow-md" />
        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
            </svg>
        </span>
    </div>

    <!-- Pesan Tidak Ditemukan -->
    <div id="notFoundMessage" class="hidden mt-3 px-4 py-3 rounded-lg bg-red-600 text-white text-sm font-semibold shadow-md animate-fade-in">
        <div>Menu tidak ditemukan</div>
        <div class="text-xs font-normal">Silakan coba kata kunci lain.</div>
    </div>

    <!-- Menu Navigasi -->
    <nav class="flex flex-col space-y-2 text-sm text-white">
        @if(Route::has('setting'))
        <a href="{{ route('setting') }}" data-label="setting akun anda" class="menu-link flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-emerald-600 transition">
            <img src="/setting.png" class="w-5 h-5" alt="Setting">
            <span>Setting Akun Anda</span>
        </a>
        @endif

        <a href="{{ url('/DashboardStaff') }}" data-label="dashboard staff" class="menu-link flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-emerald-600 transition">
            <img src="/home.png" class="w-5 h-5" alt="Dashboard Staff">
            <span>Dashboard Staff</span>
        </a>

        <!-- Dropdown: Lihat Data -->
        <div x-data="{ open: false }" class="space-y-1">
            <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 rounded-lg hover:bg-emerald-600 transition">
                <span class="flex items-center gap-3">
                    <img src="/Lihat.png" class="w-5 h-5" alt="Lihat Data">
                    Data 
                </span>
                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-cloak class="ml-8 space-y-1">
                <a href="{{ route('siswa.index') }}" data-label="data siswa" class="menu-link block px-3 py-2 rounded-md hover:bg-emerald-500">Data Siswa</a>
                <a href="{{ route('pegawai.index') }}" data-label="data pegawai" class="menu-link block px-3 py-2 rounded-md hover:bg-emerald-500">Data Pegawai</a>
            </div>
        </div>

        <!-- Dropdown: Tambah Data -->
        <div x-data="{ open: false }" class="space-y-1">
            <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 rounded-lg hover:bg-emerald-600 transition">
                <span class="flex items-center gap-3">
                    <img src="/Tambah.png" class="w-5 h-5" alt="Tambah Data">
                    Pengaturan
                </span>
                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-cloak class="ml-8 space-y-1">
               <a href="{{ route('StaffKelasSiswa') }}" data-label="tambah kelas" class="menu-link block px-3 py-2 rounded-md hover:bg-emerald-500">Kelas</a>
                <a href="{{ route('StaffTahunAjaranSiswa') }}" data-label="tambah tahun ajaran" class="menu-link block px-3 py-2 rounded-md hover:bg-emerald-500">Tahun Ajaran</a>
                <a href="{{ route('StaffMapelSIswa') }}" data-label="tambah mapel" class="menu-link block px-3 py-2 rounded-md hover:bg-emerald-500">Tambah Mapel</a>
                <a href="{{ route('StaffMasterSuratSiswa') }}" data-label="master surat" class="menu-link block px-3 py-2 rounded-md hover:bg-emerald-500">Master Surat</a>
                <a href="{{ route('StaffRegisMapelSiswa') }}" data-label="pengaturan registrasi mapel" class="menu-link block px-3 py-2 rounded-md hover:bg-emerald-500">Regis Mapel</a>
                 <a href="{{ route('StaffKelasMapelSiswa') }}" data-label="pengaturan kelas mapel" class="menu-link block px-3 py-2 rounded-md hover:bg-emerald-500">Kelas Mapel</a>
             <a href="{{ route('pengumuman.index') }}" data-label="tambah pengumuman" class="menu-link block px-3 py-2 rounded-md hover:bg-emerald-500">Pengumuman</a>
                </div>

        </div>
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
                link.closest('div, a').style.display = '';
                foundAny = true;
            } else {
                link.closest('div, a').style.display = 'none';
            }
        });

        notFoundMsg.classList.toggle('hidden', foundAny);
    });
});
</script>

<style>
@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

.animate-fade-in {
    animation: fade-in 0.4s ease-in-out;
}
</style>
