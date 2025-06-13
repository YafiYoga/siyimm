<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Yayasan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Sesuaikan jika pakai Vite --}}
</head>
<body class="bg-gray-100 font-[Verdana] text-gray-800 min-h-screen flex flex-col">

    {{-- Header --}}
    @include('layouts.Header')

    {{-- Wrapper Flex: Sidebar + Content --}}
    <div class="flex flex-1 min-h-0 overflow-hidden">

        {{-- Sidebar --}}
        <div class="w-64 bg-white shadow-md border-r border-gray-200 overflow-y-auto">
            @include('layouts.SidebarYayasan')
        </div>

        {{-- Main Content --}}
        <main class="flex-1 p-6 sm:p-10 overflow-y-auto mt-20">
            @yield('content')
        </main>

    </div>

</body>
</html>
