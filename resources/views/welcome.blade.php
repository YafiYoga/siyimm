<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="{{ asset('logo_yayasan.png') }}" type="image/png" />
    <title>Yayasan Insan Madani Mulia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="bg-white font-sans">
    
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
            <a href="{{ url('/') }}" class="hover:underline rounded hover:bg-green-600">Home</a>
            <a href="{{ url('/#about') }}" class="hover:underline">About Us</a>
            <div class="relative group">
              <a href="#" class="hover:underline">Pengumuman</a>
            </div>
            <a href="#" class="hover:underline">Pendaftaran</a>
            <a href="{{ url('/HalamanAwalSIAKAD') }}" class="hover:underline">SIAKAD</a>
          </nav>
        </div>
      </header>

        <!-- tampilan gambar -->
        <section class="relative w-full h-[600px] overflow-hidden mt-16">
      <!-- gambar banner 1 -->
      <img src="{{ asset('Banner1.png') }}"
          class="absolute inset-0 w-full h-full object-cover animate-fadeSlide opacity-0 animation-delay-0" />

      <!-- gambar banner 2 -->
      <img src="{{ asset('Banner2.jpg') }}"
          class="absolute inset-0 w-full h-full object-cover animate-fadeSlide opacity-0 animation-delay-[5s]" />

      <!-- tampilan text -->
      <div class="absolute top-0 left-0 w-full h-full bg-black/30 flex items-center px-29 z-10 ">
        <div class="text-white  px-4 font-[Verdana] animate-zoom-in">
          <h1 class="text-3xl sm:text-4xl font-bold">Yayasan Insan Madani Mulia</h1>
          <p class="mt-2 text-base sm:text-lg max-w-xl mx-auto">
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s...
          </p>
          <p class="mt-1 italic text-sm flex   gap-1">
          <img src="{{ asset('lokasi.png') }}" alt="Location Icon" class="w-4 h-4 object-contain" />
          Kecamatan Geger, Kabupaten Madiun, Provinsi Jawa Timur
        </p>
        </div>
      </div>
    </section>
    <!-- Pendaftaran -->
    <section class="py-16 bg-white py-16 px-4 sm:px-8 lg:px-24 animate-zoom-in">
      <div class="max-w-6xl mx-auto font-[Verdana]">
        <div class="inline-block  text-white  text-center py-3 font-semibold h-[60] w-[200px]" style="clip-path: polygon(10px 0%, 100% 0%, 100% 100%, 0% 100%); background-color: #328E6E;">Pendaftaran</div>
        <p class="mt-4 text-gray-700 text-center">Informasi pendaftaran SD & SMP di Yayasan Insan Madani Mulia periode 2025-2026</p>
        <div class="my-8 flex justify-center">
          <img src="{{ asset('PPDB.png') }}" alt="PPDB SMP" class="w-72 shadow-md rounded" />
        </div>
        <p class="text-black-600 ">
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
            when an unknown printer took a galley of type and scrambled  it to make a type 
            specimen book.  Lorem Ipsum has been the industry's standard dummy text ever
            since the 1500s, when an unknown printer took a galley of type and scrambled 
            it to make a type specimen book.
        </p>
        <!-- Tombol Daftar Sekarang -->
        <div class="my-8 flex justify-center">
            <a class="group flex items-center justify-between gap-4 rounded-lg border border-current px-5 py-3 text-[#328E6E] 
            transition-colors hover:bg-[#328E6E] focus:ring-3 focus:outline-hidden" href="#">
        <span class="font-extrabold transition-colors group-hover:text-white font-[Verdana]"> Daftar Sekarang </span>
        <span class="shrink-0 rounded-full border border-[#328E6E] bg-white p-2">
          <svg
            class="size-5 shadow-sm rtl:rotate-180"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M17 8l4 4m0 0l-4 4m4-4H3"
            />
          </svg>
        </span>
      </a>
      </div>
    </section>

    <!-- Fasilitas -->
    <section class="py-16 px-4 sm:px-8 lg:px-24 animate-zoom-in">
  <div class="max-w-6xl mx-auto">
  <div class="inline-block  text-white  text-center py-3 font-semibold h-[60] w-[200px] font-[Verdana] bg-[#328E6E]"
   style="clip-path: polygon(10px 0%, 100% 0%, 100% 100%, 0% 100%); ">
        Fasilitas
      </div>
      <div class="justify-center text-center py-10">
    <div class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-8 font-[Verdana]">
      <div>
        <img src="{{ asset('library.png') }}" alt="Perpustakaan" class="mx-auto h-40" />
        <p class="mt-2 font-semibold">Perpustakaan</p>
      </div>
      <div>
        <img src="{{ asset('school.png') }}" alt="Gedung Sekolah" class="mx-auto h-40" />
        <p class="mt-2 font-semibold">Gedung Sekolah</p>
      </div>
      <div>
        <img src="{{ asset('field.png') }}" alt="Lapangan" class="mx-auto h-40" />
        <p class="mt-2 font-semibold">Lapangan Sekolah</p>
      </div>
      </div>
    </div>
  </div>
  <div class="my-8 flex justify-center">
            <a href="{{ url('/fasilitas') }}" class="group flex items-center justify-between gap-4 rounded-lg border border-current px-5 py-3 text-[#328E6E] 
            transition-colors hover:bg-[#328E6E] focus:ring-3 focus:outline-hidden" >
        <span class="font-semibold transition-colors group-hover:text-white font-[Verdana]">Selengkapnya </span>
        <span class="shrink-0 rounded-full border border-[#328E6E] bg-white p-2">
          <svg
            class="size-5 shadow-sm rtl:rotate-180"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M17 8l4 4m0 0l-4 4m4-4H3"
            />
          </svg>
        </span>
      </a>
      </div>
        </div>
      </div>
</section>

<!-- Spacer between Fasilitas and About Us -->


<section id="about" class=" text-white py-16 px-4 sm:px-8 lg:px-24 animate-zoom-in" style="background-color: #328E6E;">
  <div class="max-w-6xl mx-auto">
    <div class="mb-8">
      <span class="inline-block  bg-white  text-center py-3 font-semibold font-[Verdana]  h-[60] w-[200px]" style="clip-path: polygon(10px 0%, 100% 0%, 100% 100%, 0% 100%); color: #328E6E;"  >About Us</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8  font-[Verdana]">
      <div>
        <h3 class="font-semibold">Profile Yayasan</h3>
        <p class="mt-2 ">Pimpinan Yayasan : Moh. Husaini, S.Pd</p>
        <p class="mt-2">No. Pendirian Yayasan : 14</p>
        <p class="mt-2">No. Pengesahan PN LN : AHU-2320.AH.01.04.2012</p>
      </div>
      <div>
        <p class="mt-8">Operator Yayasan : Arif Wahyudiono</p>
        <p class="mt-2">Tgl Pendirian Yayasan : 10 Feb 2011</p>
        <p class="mt-2">Tgl SK Pengesahan Badan Hukum Kemenkumham : 2012-05-07</p>
      </div>
    </div>

    <div class="mt-12">
      <h3 class="font-semibold text-white  font-[Verdana] text-2xl">Visi Yayasan</h3>
      <p class="mt-2 text-justify font-[Verdana]">
        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
        when an unknown printer took a galley of type and scrambled it to make a type
        specimen book. Lorem Ipsum has been the industry's standard dummy text ever
        since the 1500s, when an unknown printer took a galley of type and scrambled
        it to make a type specimen book.
      </p>

      <h3 class="font-semibold text-white mt-8 font-[Verdana] text-2xl">Misi Yayasan</h3>
      <p class="mt-2 text-justify font-[Verdana]">
        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
        when an unknown printer took a galley of type and scrambled it to make a type
        specimen book. Lorem Ipsum has been the industry's standard dummy text ever
        since the 1500s, when an unknown printer took a galley of type and scrambled
        it to make a type specimen book.
      </p>
    </div>
  </div>
</section>


    <!-- halaman alumni -->
        <section class="bg-green-50 py-16 px-4 sm:px-8 lg:px-24 font-[Verdana]">
      <div class="max-w-6xl mx-auto ">
        <div class="inline-block text-white text-center py-4 font-semibold h-[60px] w-[200px]"
          style="clip-path: polygon(10px 0%, 100% 0%, 100% 100%, 0% 100%); background-color: #328E6E;">
          Jumlah Alumni
        </div>
        <p class="mt-4 font-reguler  font-[Verdana] text-[#328E6E] text-center py-8"><span class="font-bold">700</span> Siswa telah menempuh Pendidikan di Yayasan Insan Madani Mulia.</p>
        <div class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-10 max-w-4xl mx-auto justify-center items-center py-7">
          <!-- card data alumni -->
          <div class="flex items-center space-x-2">
            <div class=" flex items-center justify-center ">
            <img src="{{ asset('iconalumni.png') }}" alt="Location Icon" class="w-16 h-16 object-contain" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M5.121 17.804A7.972 7.972 0 0112 15c2.042 0 3.899.76 5.293 2.004M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <div>
              <p class="font-extrabold text-2xl font-[Verdana] text-[#328E6E]">Alumni SMP</p>
              <p class="text-sm text-black text-2xl font-[Verdana]">400 Siswa</p>
            </div>
          </div>
          <div class="flex items-center space-x-2">
            <div class=" flex items-center justify-center ">
            <img src="{{ asset('iconalumni.png') }}" alt="Location Icon" class="w-16 h-16 object-contain" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M5.121 17.804A7.972 7.972 0 0112 15c2.042 0 3.899.76 5.293 2.004M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <div>
              <p class="font-extrabold text-2xl font-[Verdana] text-[#328E6E]" >Alumni SMP</p>
              <p class="text-sm text-black-600 font-[Verdana]">400 Siswa</p>
            </div>
          </div>
          <div class="flex items-center space-x-2">
            <div class=" flex items-center justify-center ">
            <img src="{{ asset('iconalumni.png') }}" alt="Location Icon" class="w-16 h-16 object-contain" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M5.121 17.804A7.972 7.972 0 0112 15c2.042 0 3.899.76 5.293 2.004M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <div>
              <p class="font-extrabold text-2xl font-[Verdana] text-[#328E6E]" >Alumni SMP</p>
              <p class="text-sm text-black-600 font-[Verdana]">400 Siswa</p>
            </div>
          </div>
        </div>
        <div class="text-center">
          <p class="font-semibold font-[Verdana] text-[#328E6E] py-8">Pastikan anda telah menjadi bagian dari kami</p>
          <div class="my-8 flex justify-center">
            <a class="group flex items-center justify-between gap-4 rounded-lg border border-current px-5 py-3 text-[#328E6E] 
            transition-colors hover:bg-[#328E6E] focus:ring-3 focus:outline-hidden" href="#">
        <span class="font-semibold transition-colors group-hover:text-white font-[Verdana]"> Daftar Sekarang </span>
        <span class="shrink-0 rounded-full border border-[#328E6E] bg-white p-2">
          <svg
            class="size-5 shadow-sm rtl:rotate-180"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M17 8l4 4m0 0l-4 4m4-4H3"
            />
          </svg>
        </span>
      </a>
      </div>
        </div>
      </div>
    </section>


    <!-- Footer -->
    <footer class="bg-[#328E6E] text-white py-4 text-center">
    <p class="italic font-[Verdana] ">Copyright © 2025 | Yayasan Insan Madani Mulia 2018</p>
    </footer>
  </body>
</html>
