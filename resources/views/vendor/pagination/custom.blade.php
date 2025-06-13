@if ($paginator->hasPages())
    <div class="flex justify-center mt-8">
        <nav role="navigation" class="inline-flex gap-2 items-center">
            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 text-sm font-semibold text-gray-400 bg-gray-100 border border-gray-300 rounded-xl cursor-not-allowed shadow-sm">
                    ← Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="px-4 py-2 text-sm font-semibold text-gray-800 bg-white border border-green-500 rounded-xl hover:bg-green-500 hover:text-white transition duration-200 shadow-sm">
                    ← Sebelumnya
                </a>
            @endif

            {{-- Nomor Halaman --}}
            @foreach ($elements as $element)
                {{-- Separator --}}
                @if (is_string($element))
                    <span class="px-4 py-2 text-sm font-semibold text-gray-400">{{ $element }}</span>
                @endif

                {{-- Link halaman --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-4 py-2 text-sm font-semibold text-white bg-green-500 border border-green-500 rounded-xl shadow">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="px-4 py-2 text-sm font-semibold text-gray-800 bg-white border border-green-500 rounded-xl hover:bg-green-500 hover:text-white transition duration-200 shadow-sm">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Selanjutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="px-4 py-2 text-sm font-semibold text-gray-800 bg-white border border-green-500 rounded-xl hover:bg-green-500 hover:text-white transition duration-200 shadow-sm">
                    Selanjutnya →
                </a>
            @else
                <span class="px-4 py-2 text-sm font-semibold text-gray-400 bg-gray-100 border border-gray-300 rounded-xl cursor-not-allowed shadow-sm">
                    Selanjutnya →
                </span>
            @endif
        </nav>
    </div>
@endif
