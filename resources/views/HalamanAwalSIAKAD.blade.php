    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="{{ asset('logo_yayasan.png') }}" type="image/png" />
        <title>Sistem Informasi Akademik</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-green-100 font-sans">

        <!-- Navbar -->
        <header class=" fixed top-0 left-0 w-full text-white bg-[#328E6E] shadow-md z-30 font-[Verdana]">
            <div class="max-w-screen-xl mx-auto flex justify-between items-center py-4 px-6">
              <div class="flex items-center gap-2 font-[Verdana]">
                <img src="{{ asset('logo_yayasan.png') }}" alt="Logo" class="w-10 h-10" />
                <div>
                  <div class="font-bold text-sm leading-tight"> Sistem Informasi Akademik</div>
                  <div class="font-bold text-sm">Yayasan Insan Madani Mulia</div>
                </div>
              </div>
              <nav class="hidden md:flex gap-4 text-sm font-[Verdana]">
                <a href="{{ url('/') }}" class="hover:underline">Company Profile</a>
                <a href="{{ url('/loginAkademik') }}" class="hover:underline">Login SIAKAD</a>
              </nav>
            </div>
          </header>

        <!-- Main content -->
        <main class="relative bg-cover bg-center h-screen " style="background-image: url('banner1.png');">
            <div class="absolute inset-0 bg-black opacity-50"></div>
            <div class="relative z-10 flex justify-center items-center h-full text-center font-[Verdana]">
                <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full">
                    <div class="flex justify-center items-center ">
                    <img src="{{ asset('logo_yayasan.png') }}" alt="Logo" class="w-24 h-24 animate-zoom-in" />
                  </div>
                    <h1 class="text-3xl font-semibold text-green-700 mb-4 animate-zoom-in">Sistem Informasi Akademik</h1>
                    <p class="text-green-700 mb-6 animate-zoom-in">Untuk mengakses sistem informasi akademik ini dapat memilih menu login pada menu diatas pojok kanan</p>
                    <footer class="text-sm text-green-600">
                        Copyright &copy; 2025 Yayasan Insan Madani Mulia
                    </footer>
                </div>
            </div>
        </main>

        <footer class="bg-[#328E6E] text-white py-4 text-center">
        <p class="italic font-[Verdana] ">Copyright © 2025 | Yayasan Insan Madani Mulia 2018</p>
        </footer>
      </body>

    </body>
    </html>
