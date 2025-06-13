@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $pegawai = $user->pegawai ?? null;
    $namaLengkap = $pegawai->nama_lengkap ?? $user->username;
    $foto = $pegawai->foto ?? null;
    $fotoUrl = $foto ? asset('storage/' . $foto) : asset('user.png');
@endphp

<div class="fixed top-16 left-0 w-64 h-[calc(100vh-4rem)] bg-gray-900 text-white shadow-lg z-40 border-r border-emerald-600">
    <div class="p-4 space-y-6 overflow-y-auto h-full font-[Verdana]">
        <!-- Profil -->
        <div class="text-center">
            <img src="{{ $fotoUrl }}" alt="Foto Profil" class="w-16 h-16 mx-auto rounded-full border-4 border-emerald-500 shadow-md object-cover">
            <h2 class="mt-2 font-semibold truncate text-sm">{{ $namaLengkap }}</h2>
            <p class="text-xs text-gray-400 italic">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>
        </div>

        <!-- Search -->
        <div class="relative">
            <input type="text" id="searchMenu" placeholder="Cari menu..."
                class="w-full py-2 pl-4 pr-10 rounded-lg bg-gray-800 border border-gray-700 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" />
            <img src="{{ asset('search.png') }}" alt="Search Icon"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 opacity-70 pointer-events-none">
        </div>

        <div id="notFoundMessage" class="hidden mt-2 px-4 py-3 rounded-md bg-red-500 text-sm animate-fade-in">
            <div class="font-bold">Menu tidak ditemukan</div>
            <div class="text-xs">Silakan coba kata kunci lain.</div>
        </div>

        <!-- Menu -->
        <nav class="space-y-2 text-sm">
            <a href="{{ route('DashboardYayasan') }}"
               data-label="dashboard yayasan"
               class="menu-link flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-emerald-600 transition">
                <img src="{{ asset('home.png') }}" class="w-5 h-5 opacity-80" alt="Dashboard">
                <span>Dashboard Yayasan</span>
            </a>

            <a href="{{ route('setting') }}"
               data-label="setting akun"
               class="menu-link flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-emerald-600 transition">
                <img src="{{ asset('setting.png') }}" class="w-5 h-5 opacity-80" alt="Setting">
                <span>Setting Akun Anda</span>
            </a>

            <!-- Lembaga SD -->
            <div class="pl-4 mt-2 font-bold text-emerald-400">LEMBAGA SD</div>
            <a href="{{ route('yayasan.data_siswa', ['jenjang' => 'sd']) }}" data-label="data siswa sd" class="menu-link px-4 py-2 hover:bg-emerald-600 rounded-lg block">📘 Data Siswa SD</a>
            <a href="{{ route('yayasan.nilai_siswa', ['jenjang' => 'sd']) }}" data-label="nilai siswa sd" class="menu-link px-4 py-2 hover:bg-emerald-600 rounded-lg block">📊 Nilai Siswa SD</a>
            <a href="{{ route('yayasan.absensi_siswa', ['jenjang' => 'sd']) }}" data-label="absensi siswa sd" class="menu-link px-4 py-2 hover:bg-emerald-600 rounded-lg block">🗓️ Absensi Siswa SD</a>
            <a href="{{ route('yayasan.hafalan_siswa', ['jenjang' => 'sd']) }}" data-label="hafalan siswa sd" class="menu-link px-4 py-2 hover:bg-emerald-600 rounded-lg block">📖 Hafalan Siswa SD</a>
            <a href="{{ route('yayasan.data_pegawai', ['jenjang' => 'sd']) }}" data-label="data pegawai sd" class="menu-link px-4 py-2 hover:bg-emerald-600 rounded-lg block">👨‍🏫 Data Pegawai SD</a>
            <a href="{{ route('yayasan.absensi_pegawai', ['jenjang' => 'sd']) }}" data-label="absensi pegawai sd" class="menu-link px-4 py-2 hover:bg-emerald-600 rounded-lg block">📅 Absensi Pegawai SD</a>

            <!-- Lembaga SMP -->
            <div class="pl-4 mt-4 font-bold text-emerald-400">LEMBAGA SMP</div>
            <a href="{{ route('yayasan.data_siswa', ['jenjang' => 'smp']) }}" data-label="data siswa smp" class="menu-link px-4 py-2 hover:bg-emerald-600 rounded-lg block">📘 Data Siswa SMP</a>
            <a href="{{ route('yayasan.nilai_siswa', ['jenjang' => 'smp']) }}" data-label="nilai siswa smp" class="menu-link px-4 py-2 hover:bg-emerald-600 rounded-lg block">📊 Nilai Siswa SMP</a>
            <a href="{{ route('yayasan.absensi_siswa', ['jenjang' => 'smp']) }}" data-label="absensi siswa smp" class="menu-link px-4 py-2 hover:bg-emerald-600 rounded-lg block">🗓️ Absensi Siswa SMP</a>
            <a href="{{ route('yayasan.hafalan_siswa', ['jenjang' => 'smp']) }}" data-label="hafalan siswa smp" class="menu-link px-4 py-2 hover:bg-emerald-600 rounded-lg block">📖 Hafalan Siswa SMP</a>
            <a href="{{ route('yayasan.data_pegawai', ['jenjang' => 'smp']) }}" data-label="data pegawai smp" class="menu-link px-4 py-2 hover:bg-emerald-600 rounded-lg block">👨‍🏫 Data Pegawai SMP</a>
            <a href="{{ route('yayasan.absensi_pegawai', ['jenjang' => 'smp']) }}" data-label="absensi pegawai smp" class="menu-link px-4 py-2 hover:bg-emerald-600 rounded-lg block">📅 Absensi Pegawai SMP</a>
        </nav>
    </div>
</div>

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
            if (label.includes(query) || query === '') {
                link.style.display = '';
                foundAny = true;
            } else {
                link.style.display = 'none';
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
    animation: fade-in 0.3s ease-in-out;
}
</style>
