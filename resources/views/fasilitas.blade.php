<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="{{ asset('logo_yayasan.png') }}" type="image/png" />
    <title>Yayasan Insan Madani Mulia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
 <!-- Tampilan header -->
 <header class=" fixed top-0 left-0 w-full text-white bg-[#328E6E] shadow-md z-30 font-[Verdana]">
        <div class="max-w-screen-xl mx-auto flex justify-between items-center py-4 px-6">
          <div class="flex items-center gap-2 font-[Verdana]">
            <img src="{{ asset('logo_yayasan.png') }}" alt="Logo" class="w-10 h-10" />
            <div>
              <div class="font-bold text-sm leading-tight">Company Profile</div>
              <div class="font-bold text-sm">Yayasan Insan Madani Mulia</div>
            </div>
          </div>
          <nav class="hidden md:flex gap-4 text-sm font-[Verdana]">
            <a href="{{ url('/') }}" class="hover:underline">Home</a>
            <a href="{{ url('/#about') }}" class="hover:underline">About Us</a>
            <div class="relative group">
              <a href="#" class="hover:underline">Pengumuman</a>
            </div>
            <a href="#" class="hover:underline">Pendaftaran</a>
            <a href="{{ url('') }}" class="hover:underline">SIAKAD</a>
          </nav>
        </div>
      </header>


<div class="container mx-auto py-16 px-40 mt-16">
<div class="inline-block text-white text-center py-4 font-semibold h-[60px] w-[200px] mb-10"
          style="clip-path: polygon(10px 0%, 100% 0%, 100% 100%, 0% 100%); background-color: #328E6E;">
          Fasilitas
        </div>
  
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 font-[Verdana]">
        <a class="block">
        <img src="{{ asset('Fasilitas.jpg') }}" alt="Ruang Kelas" 
            class="h-60 w-60 rounded-tr-2xl rounded-bl-3xl object-cover sm:h-60 lg:h-72" />

        <div class="mt-4 sm:flex sm:items-center  sm:gap-2 font-[Verdana]">
            <strong class=" font-extrabold">Fasilitas yayasan</strong>

            <span class="hidden sm:block sm:h-px sm:w-8 sm:bg-[#328E6E]"></span>

            <p class="mt-0.5 opacity-50 sm:mt-0">masjid</p>
        </div>
        </a>
        <a class="block">
        <img src="{{ asset('Fasilitas.jpg') }}" alt="Ruang Kelas" 
            class="h-60 w-60 rounded-tr-2xl rounded-bl-3xl object-cover sm:h-60 lg:h-72" />

        <div class="mt-4 sm:flex sm:items-center  sm:gap-2 font-[Verdana]">
            <strong class=" font-extrabold">Fasilitas yayasan</strong>

            <span class="hidden sm:block sm:h-px sm:w-8 sm:bg-[#328E6E]"></span>

            <p class="mt-0.5 opacity-50 sm:mt-0">masjid</p>
        </div>
        </a>
        <a class="block">
        <img src="{{ asset('Fasilitas.jpg') }}" alt="Ruang Kelas" 
            class="h-60 w-60 rounded-tr-2xl rounded-bl-3xl object-cover sm:h-60 lg:h-72" />

        <div class="mt-4 sm:flex sm:items-center  sm:gap-2 font-[Verdana]">
            <strong class=" font-extrabold">Fasilitas yayasan</strong>

            <span class="hidden sm:block sm:h-px sm:w-8 sm:bg-[#328E6E]"></span>

            <p class="mt-0.5 opacity-50 sm:mt-0">masjid</p>
        </div>
        </a>
        <a class="block">
        <img src="{{ asset('Fasilitas.jpg') }}" alt="Ruang Kelas" 
            class="h-60 w-60 rounded-tr-2xl rounded-bl-3xl object-cover sm:h-60 lg:h-72" />

        <div class="mt-4 sm:flex sm:items-center  sm:gap-2 font-[Verdana]">
            <strong class=" font-extrabold">Fasilitas yayasan</strong>

            <span class="hidden sm:block sm:h-px sm:w-8 sm:bg-[#328E6E]"></span>

            <p class="mt-0.5 opacity-50 sm:mt-0">masjid</p>
        </div>
        </a>
        <a class="block">
        <img src="{{ asset('Fasilitas.jpg') }}" alt="Ruang Kelas" 
            class="h-60 w-60 rounded-tr-2xl rounded-bl-3xl object-cover sm:h-60 lg:h-72" />

        <div class="mt-4 sm:flex sm:items-center  sm:gap-2 font-[Verdana]">
            <strong class=" font-extrabold">Fasilitas yayasan</strong>

            <span class="hidden sm:block sm:h-px sm:w-8 sm:bg-[#328E6E]"></span>

            <p class="mt-0.5 opacity-50 sm:mt-0">masjid</p>
        </div>
        </a>
        <a class="block">
        <img src="{{ asset('Fasilitas.jpg') }}" alt="Ruang Kelas" 
            class="h-60 w-60 rounded-tr-2xl rounded-bl-3xl object-cover sm:h-60 lg:h-72" />

        <div class="mt-4 sm:flex sm:items-center  sm:gap-2 font-[Verdana]">
            <strong class=" font-extrabold">Fasilitas yayasan</strong>

            <span class="hidden sm:block sm:h-px sm:w-8 sm:bg-[#328E6E]"></span>

            <p class="mt-0.5 opacity-50 sm:mt-0">masjid</p>
        </div>
        </a>
        <a class="block">
        <img src="{{ asset('Fasilitas.jpg') }}" alt="Ruang Kelas" 
            class="h-60 w-60 rounded-tr-2xl rounded-bl-3xl object-cover sm:h-60 lg:h-72" />

        <div class="mt-4 sm:flex sm:items-center  sm:gap-2 font-[Verdana]">
            <strong class=" font-extrabold">Fasilitas yayasan</strong>

            <span class="hidden sm:block sm:h-px sm:w-8 sm:bg-[#328E6E]"></span>

            <p class="mt-0.5 opacity-50 sm:mt-0">masjid</p>
        </div>
        </a>
        <a class="block">
        <img src="{{ asset('Fasilitas.jpg') }}" alt="Ruang Kelas" 
            class="h-60 w-60 rounded-tr-2xl rounded-bl-3xl object-cover sm:h-60 lg:h-72" />

        <div class="mt-4 sm:flex sm:items-center  sm:gap-2 font-[Verdana]">
            <strong class=" font-extrabold">Fasilitas yayasan</strong>

            <span class="hidden sm:block sm:h-px sm:w-8 sm:bg-[#328E6E]"></span>

            <p class="mt-0.5 opacity-50 sm:mt-0">masjid</p>
        </div>
        </a>
            </div>
        </div>


<!-- Footer -->
<footer class="bg-[#328E6E] text-white py-4 text-center">
    <p class="italic font-[Verdana] ">Copyright © 2025 | Yayasan Insan Madani Mulia 2018</p>
    </footer>
  </body>
</html>
