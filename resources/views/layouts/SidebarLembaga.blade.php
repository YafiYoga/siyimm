@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $pegawai = $user->pegawai ?? null;
    $namaLengkap = $pegawai->nama_lengkap ?? $user->username;
    $foto = $pegawai->foto ?? null;
    $fotoUrl = $foto ? asset('storage/' . $foto) : asset('user.png');
    $lembagaId = $user->id;
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
        <nav class="space-y-1 text-sm">
            @php
                $menuItems = [
                    ['label' => 'Dashboard Lembaga', 'route' => route('DashboardLembaga'), 'icon' => 'home.png'],
                    ['label' => 'Setting Akun Anda', 'route' => route('setting'), 'icon' => 'setting.png'],
                    ['label' => 'Data Siswa', 'route' => route('lembaga.data_siswa', ['id' => $lembagaId]), 'svg' => '<path d="M5 13l4 4L19 7" />'],
                    ['label' => 'Nilai Siswa', 'route' => route('lembaga.nilai_siswa', ['id' => $lembagaId]), 'svg' => '<path d="M9 17v-6h6v6h5V4H4v13h5z" />'],
                    ['label' => 'Absensi Siswa', 'route' => route('lembaga.absensi_siswa', ['id' => $lembagaId]), 'svg' => '<path d="M8 7V3m8 4V3m-9 8h10m-6 4h.01M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />'],
                    ['label' => 'Hafalan Siswa', 'route' => route('lembaga.hafalan_siswa', ['id' => $lembagaId]), 'svg' => '<path d="M12 4v16m8-8H4" />'],
                    ['label' => 'Data Pegawai', 'route' => route('lembaga.data_pegawai'), 'svg' => '<path d="M17 20h5v-2a3 3 0 00-3-3h-2M9 20H4v-2a3 3 0 013-3h2m0-6h6m-6 4h6m-6 4h6" />'],
                    ['label' => 'Absensi Pegawai', 'route' => route('lembaga.absensi_pegawai'), 'svg' => '<path d="M8 7V3m8 4V3m-9 8h10m-6 4h.01M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />'],
                ];
            @endphp

            @foreach ($menuItems as $item)
                <a href="{{ $item['route'] }}" data-label="{{ strtolower($item['label']) }}"
                    class="menu-link flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-emerald-600 hover:text-white transition-all duration-200">
                    @if (isset($item['icon']))
                        <img src="{{ asset($item['icon']) }}" class="w-5 h-5 opacity-80" alt="{{ $item['label'] }}">
                    @elseif (isset($item['svg']))
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            {!! $item['svg'] !!}
                        </svg>
                    @endif
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>

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
