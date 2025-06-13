@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $namaLengkap = $user->pegawai->nama_lengkap ?? $user->username;
    $foto = $user->pegawai->foto ?? null;
    $fotoUrl = $foto ? asset('storage/' . $foto) : asset('/user.png');
@endphp

<!-- Sidebar Guru -->
<div class="fixed top-16 left-0 w-64 h-[calc(100vh-4rem)] bg-gray-900 p-4 overflow-y-auto z-40 font-[Verdana] shadow-xl border-r border-emerald-600 space-y-6">

    <!-- Profil Guru -->
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
    <div id="notFoundMessage" class="hidden mt-2 px-4 py-3 rounded-md bg-red-500 text-white text-sm animate-fade-in">
        <div class="font-bold">Menu tidak ditemukan</div>
        <div class="text-xs">Silakan coba kata kunci lain.</div>
    </div>

    <!-- Menu Navigasi Guru -->
    <nav class="flex flex-col space-y-2 text-sm text-white">
        <a href="{{ route('DashboardGuru') }}" data-label="dashboard guru" class="menu-link flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-emerald-600 transition">
            <img src="/home.png" class="w-5 h-5" alt="Dashboard">
            <span>Dashboard Guru</span>
        </a>

        <a href="{{ route('setting') }}" data-label="setting akun anda" class="menu-link flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-emerald-600 transition">
            <img src="/setting.png" class="w-5 h-5" alt="Setting">
            <span>Setting Akun Anda</span>
        </a>

        <!-- Menu Collapse: Manajemen -->
        <!-- Menu Collapse: Manajemen -->
<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-4 py-2 rounded-lg hover:bg-emerald-600 transition bg-gray-800 text-left">
        <div class="flex items-center gap-3">
            <!-- Ikon Folder/Manajemen -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
            </svg>
            <span>Manajemen</span>
        </div>
        <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" x-transition class="ml-6 flex flex-col space-y-1">
        <!-- Nilai Siswa -->
        <a href="{{ route('GuruNilai') }}" data-label="manajemen nilai siswa" class="menu-link flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-emerald-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6m2 4H7a2 2 0 01-2-2V5a2 2 0 012-2h5l2 2h5a2 2 0 012 2v12a2 2 0 01-2 2z" />
            </svg>
            <span>Nilai Siswa</span>
        </a>

        <!-- Hafalan Siswa -->
        <a href="{{ route('GuruHafalan') }}" data-label="manajemen hafal siswa" class="menu-link flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-emerald-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2m4 4H4a2 2 0 01-2-2V6a2 2 0 012-2h16a2 2 0 012 2v12a2 2 0 01-2 2z" />
            </svg>
            <span>Hafalan Siswa</span>
        </a>

        <!-- Absensi Siswa -->
        <a href="{{ route('GuruAbsensi') }}" data-label="manajemen absensi siswa" class="menu-link flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-emerald-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>Absensi Siswa</span>
        </a>
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
                link.closest('a')?.style.setProperty('display', '');
                foundAny = true;
            } else {
                link.closest('a')?.style.setProperty('display', 'none');
            }
        });

        notFoundMsg.classList.toggle('hidden', foundAny);
    });
});
</script>

<!-- Fade-In Animation -->
<style>
@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-in-out;
}
</style>
