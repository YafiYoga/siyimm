<?php

namespace App\Http\Controllers;
use App\Models\Siswa;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Models\Pengumuman;
use App\Models\NilaiSiswa;
use App\Models\HafalanQuranSiswa;
use App\Models\AbsensiSiswa;
use App\Http\Controllers\LembagaController;
use Illuminate\Support\Facades\Validator;
use App\Models\RegisMapelSiswa;
use App\Models\KelasMapel;
use App\Models\Kelas;
use Carbon\Carbon;
class UserController extends Controller

{
    public function showLoginForm()
    {
        return view('loginAkademik'); // ganti dengan view kamu
    }
    // Fungsi loginakademik yang sudah disamakan
public function loginakademik(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = User::where('username', $request->username)->first();

    // Cek apakah user ditemukan
    if (!$user) {
        return back()->withErrors(['username' => 'Akun tidak terdaftar.'])->onlyInput('username');
    }

    // Cek status aktif
    if ($user->isDeleted == 1) {
        return back()->withErrors(['username' => 'Akun Anda tidak aktif, silakan hubungi admin.'])->onlyInput('username');
    }

    // Cek password
    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors(['password' => 'Password salah.'])->onlyInput('username');
    }

    // Login berhasil
    Auth::login($user);
    $request->session()->regenerate();

    switch ($user->role) {
        case 'admin':
            return redirect()->route('DashboardAdmin');
        case 'staff_sd':
        case 'staff_smp':
            return redirect()->route('DashboardStaff');
        case 'guru_sd':
        case 'guru_smp':
            return redirect()->route('DashboardGuru');
        case 'lembaga_sd':
        case 'lembaga_smp':
            return redirect()->route('DashboardLembaga');
        case 'yayasan':
            return redirect()->route('DashboardYayasan');
        default:
            Auth::logout();
            return redirect()->route('login')->withErrors(['username' => 'Role tidak dikenali.']);
    }
}

    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        
        return redirect('/loginAkademik')->with('success', 'Anda berhasil logout.');
    }
public function dashboardLembaga()
{
    // Total siswa aktif
    $jumlahSiswa = Siswa::count();

    // Total pegawai aktif
    $jumlahPegawai = Pegawai::count();

    // Jumlah absensi hadir hari ini
    $absensiHariIni = AbsensiSiswa::whereDate('tanggal', Carbon::today())
                        ->where('status', 'hadir')
                        ->count();

    // Ambil 5 nilai terbaru
   $nilaiTerbaru = NilaiSiswa::with('regisMapelSiswa.siswa')
                ->latest()
                ->take(5)
                ->get();

    // Ambil 5 hafalan terbaru
    $hafalanTerbaru = HafalanQuranSiswa::with([
    'regisMapelSiswa.siswa',
    'surat',
    'guru'
    ])
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get();


    return view('DashboardLembaga', compact(
        'jumlahSiswa',
        'jumlahPegawai',
        'absensiHariIni',
        'nilaiTerbaru',
        'hafalanTerbaru'
    ));
}
public function dashboardYayasan()
{
    $totalSiswaSD = Siswa::where('lembaga', Siswa::LEMBAGA_SD)->count();
    $totalSiswaSMP = Siswa::where('lembaga', Siswa::LEMBAGA_SMP)->count();

    $totalAbsensiSDSiswa = AbsensiSiswa::whereHas('regisMapelSiswa.siswa', function ($query) {
        $query->where('lembaga', Siswa::LEMBAGA_SD);
    })->count();

    $totalAbsensiSMPSiswa = AbsensiSiswa::whereHas('regisMapelSiswa.siswa', function ($query) {
        $query->where('lembaga', Siswa::LEMBAGA_SMP);
    })->count();

    return view('DashboardYayasan', compact(
        'totalSiswaSD', 'totalSiswaSMP',
        'totalAbsensiSDSiswa', 'totalAbsensiSMPSiswa'
    ));
}


    public function dashboardAdmin(Request $request)
{
    $search = $request->input('search');
    $status = $request->input('status');
    $role = $request->input('role');

    $currentUserId = Auth::id();

    // Hitung total akun TANPA filter user login
    $counterQuery = User::query();

    if ($search) {
        $counterQuery->where(function ($q) use ($search) {
            $q->where('username', 'like', "%{$search}%")
              ->orWhereHas('pegawai', function ($q2) use ($search) {
                  $q2->where('nama_lengkap', 'like', "%{$search}%");
              })
              ->orWhereHas('walimurid.siswa', function ($q3) use ($search) {
                  $q3->where('nama_siswa', 'like', "%{$search}%");
              });
        });
    }

    if ($status !== null && $status !== '') {
        $counterQuery->where('isDeleted', (int)$status);
    }

    if ($role) {
        $counterQuery->where('role', $role);
    }

    $counter = $counterQuery->count(); // total seluruh akun termasuk yang login

    // Query untuk menampilkan daftar akun TANPA akun login
    $query = User::query()->where('id', '!=', $currentUserId);

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('username', 'like', "%{$search}%")
              ->orWhereHas('pegawai', function ($q2) use ($search) {
                  $q2->where('nama_lengkap', 'like', "%{$search}%");
              })
              ->orWhereHas('walimurid.siswa', function ($q3) use ($search) {
                  $q3->where('nama_siswa', 'like', "%{$search}%");
              });
        });
    }

    if ($status !== null && $status !== '') {
        $query->where('isDeleted', (int)$status);
    }

    if ($role) {
        $query->where('role', $role);
    }

    $users = $query->with(['pegawai', 'walimurid.siswa'])
                   ->orderBy('created_at', 'desc')
                   ->paginate(10);

    $noResults = $users->isEmpty();

    return view('DashboardAdmin', compact('users', 'counter', 'noResults'));
}




   



public function dashboardStaff()
{
    $user = auth()->user();
    $role = $user->role;

    $lembagaArr = match ($role) {
        'staff_sd' => [Siswa::LEMBAGA_SD],    // ['sd']
        'staff_smp' => [Siswa::LEMBAGA_SMP],  // ['smp']
        default => [],
    };

    if (empty($lembagaArr)) {
        abort(403, 'Tidak memiliki akses ke dashboard ini.');
    }

    $lembagaOptions = $lembagaArr;

    if ($role === 'staff_sd') {
        $lembaga = 'sd';

        $totalSiswa = Siswa::where('asal_sekolah', 'like', 'TK%')
            ->whereIn('lembaga', $lembagaArr)
            ->count();

        $totalPegawai = Pegawai::where('unit_kerja', 'like', 'sd%')->count();

    } elseif ($role === 'staff_smp') {
        $lembaga = 'smp';

        $totalSiswa = Siswa::where('asal_sekolah', 'like', 'SD%')
            ->whereIn('lembaga', $lembagaArr)
            ->count();

        $totalPegawai = Pegawai::where('unit_kerja', 'like', 'smp%')->count();

    } else {
        abort(403, 'Tidak memiliki akses ke dashboard ini.');
    }

    $pengumumen = Pengumuman::where('ditujukan_kepada', 'like', "%walimurid_$lembaga%")
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    return view('DashboardStaff', compact('totalSiswa', 'totalPegawai', 'pengumumen', 'lembagaOptions'));
}



public function dashboardGuru()
{
    $user = auth()->user();

    if ($user->role == 'guru_sd') {
        $kelasPrefixes = ['1', '2', '3', '4', '5', '6'];
        $lembaga = 'sd';
    } elseif ($user->role == 'guru_smp') {
        $kelasPrefixes = ['7', '8', '9'];
        $lembaga = 'smp';
    } else {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
    }

    $kelasFilter = function ($query) use ($kelasPrefixes) {
        $query->where(function ($q) use ($kelasPrefixes) {
            foreach ($kelasPrefixes as $prefix) {
                $q->orWhere('nama_kelas', 'like', $prefix . '%');
            }
        });
    };

    $jumlahSiswa = Siswa::whereHas('regisMapelSiswas.kelasMapel.kelas', $kelasFilter)->count();

    $jumlahNilai = NilaiSiswa::whereHas('regisMapelSiswa.siswa.regisMapelSiswas.kelasMapel.kelas', $kelasFilter)->count();

    $jumlahHafalan = HafalanQuranSiswa::whereHas('regisMapelSiswa.siswa.regisMapelSiswas.kelasMapel.kelas', $kelasFilter)->count();

    $jumlahAbsensi = AbsensiSiswa::whereHas('regisMapelSiswa.siswa.regisMapelSiswas.kelasMapel.kelas', $kelasFilter)->count();


    $pengumuman = \App\Models\Pengumuman::where('ditujukan_kepada', 'like', "%walimurid_{$lembaga}%")
        ->latest()
        ->take(5)
        ->get();

    return view('DashboardGuru', compact(
        'jumlahSiswa',
        'jumlahNilai',
        'jumlahHafalan',
        'jumlahAbsensi',
        'pengumuman'
    ));
}




    public function settingAkun()
{
    $user = Auth::user();

    $layout = match ($user->role) {
        'admin' => 'layouts.MainAdmin',
        'staff_sd', 'staff_smp' => 'layouts.MainStaff',
        'guru_sd', 'guru_smp' => 'layouts.MainGuru',
        default => 'layouts.MainGuru', // fallback
    };

    $sidebar = match ($user->role) {
        'admin' => 'layouts.SidebarAdmin',
        'staff_sd', 'staff_smp' => 'layouts.SidebarStaff',
        'guru_sd', 'guru_smp' => 'layouts.SidebarGuru',
        default => 'layouts.SidebarGuru', // fallback
    };

    return view('settingAkun', compact('user', 'layout', 'sidebar'));
}


  public function update(Request $request)
{
    $user = Auth::user();

    // Validasi input
    $request->validate([
        'username' => 'required|string|max:255|unique:users,username,' . $user->id,
        'password' => 'nullable|string|min:6|confirmed',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Update username
    $user->username = $request->username;

    // Update password jika diisi
    if ($request->filled('password')) {
        $user->password = $request->password;
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    // Cek role untuk update data foto
    if ($request->hasFile('foto')) {
        $path = $request->file('foto')->store('foto_user', 'public');

        if (in_array($user->role, ['admin', 'yayasan', 'lembaga_sd', 'lembaga_smp', 'staff_sd', 'staff_smp', 'guru_sd', 'guru_smp'])) {
            // Jika pegawai
            if ($user->pegawai) {
                // Hapus foto lama
                if ($user->pegawai->foto) {
                    Storage::delete('public/' . $user->pegawai->foto);
                }
                $user->pegawai->foto = $path;
                $user->pegawai->save();
            } else {
                return redirect()->route('settingAkun')->withErrors(['pegawai' => 'Data pegawai tidak ditemukan.']);
            }
        } elseif (str_starts_with($user->role, 'walimurid')) {
            // Jika wali murid
            if ($user->siswa) {
                if ($user->siswa->foto) {
                    Storage::delete('public/' . $user->siswa->foto);
                }
                $user->siswa->foto = $path;
                $user->siswa->save();
            } else {
                return redirect()->route('settingAkun')->withErrors(['siswa' => 'Data siswa tidak ditemukan.']);
            }
        }
    }

    // Simpan user
    $user->save();

    // Jika password diubah, arahkan ke login ulang
    if ($request->filled('password')) {
        return redirect('/loginAkademik')->with('success', 'Data berhasil diperbarui, silakan login kembali.');
    }

    return redirect()->route('settingAkun')->with('success', 'Akun berhasil diperbarui!');
}







    
  
}

