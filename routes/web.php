<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\HalamanAwalSiakadController;
use App\Http\Controllers\LoginAkademikController;
use App\Http\Controllers\DashboardadminController;
use App\Http\Controllers\TambahAkunAdminController;
use App\Http\Controllers\LihatAkunPenggunaController;
use App\Http\Controllers\LihatAkunLembagaSDController;
use App\Http\Controllers\LihatAkunLembagaSMPAdminController;
use App\Http\Controllers\LihatAkunGuruSMPAdminController;
use App\Http\Controllers\LihatAkunGuruSDAdminController;
use App\Http\Controllers\LihatAkunStaffSDAdminController;
use App\Http\Controllers\LihatAkunStaffSMPAdminController;
use App\Http\Controllers\TambahAkunPenggunaController;
use App\Http\Controllers\CariController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AbsensiPegawaiController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\LembagaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\MasterSuratController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\kelasMapelController;
use App\Http\Controllers\RegisMapelSiswaController;
use App\Http\Controllers\YayasanController;


// Halaman awal
Route::get('/', function () {
    return view('welcome');
});

// Login web dan Login Akademik (form & aksi)
Route::get('/login', function () {
    return view('loginAkademik'); // tampilkan form login
})->name('login');
Route::get('/loginAkademik', [UserController::class, 'showLoginForm'])->name('loginAkademik.form');
Route::post('/loginAkademik', [UserController::class, 'login'])->name('loginAkademik');
Route::post('/loginakademik', [UserController::class, 'loginakademik'])->name('do.loginakademik');


// Admin
Route::get('/DashboardAdmin', [UserController::class, 'dashboardAdmin'])->name('DashboardAdmin');
Route::get('/LihatAkunPenggunaAdmin', [LihatAkunPenggunaController::class, 'lihatAkunPenggunaAdmin'])->name('LihatAkunPenggunaAdmin');

//staff
Route::get('/DashboardStaff', [UserController::class, 'dashboardStaff'])->name('DashboardStaff');
Route::get('/LihatPegawaiStaff', [PegawaiController::class, 'lihatPegawaiStaff'])->name('LihatPegawaiStaff');

//Guru
Route::get('/dashboard-guru', [UserController::class, 'dashboardGuru'])->name('DashboardGuru');
Route::get('/dashboardLembaga', [UserController::class, 'dashboardLembaga'])->name('DashboardLembaga');
Route::get('/dashboardYayasan', [UserController::class, 'dashboardYayasan'])->name('DashboardYayasan');




//Route::resource('users', CariController::class);

//halamanEditAkun
Route::get('/admin/users/{id}/edit', [LihatAkunPenggunaController::class, 'edit'])->name('users.edit');
Route::delete('/users/{id}', [LihatAkunPenggunaController::class, 'destroy'])->name('users.destroy');
Route::put('/admin/users/{id}', [LihatAkunPenggunaController::class, 'update'])->name('users.update');

// Halaman-halaman lainnya
Route::get('/fasilitas', [FasilitasController::class, 'index']);
Route::get('/HalamanAwalSIAKAD', [HalamanAwalSiakadController::class, 'index']);
Route::get('/TambahAkunAdmin', [TambahAkunPenggunaController::class, 'TambahAkunAdmin'])->name('TambahAkunAdmin');



// Logout
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::post('/users/store', [TambahAkunPenggunaController::class, 'tambah_akun_pengguna'])->name('users.store');

//Fungsi Cari
Route::get('/users/search-admin', [CariController::class, 'cariAdmin'])->name('users.cariadmin');
Route::get('/users/search-pengguna', [CariController::class, 'cariPengguna'])->name('users.caripengguna');

Route::prefix('staff')->middleware('auth')->group(function () {
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
});





Route::prefix('lembaga')->middleware(['auth'])->name('lembaga.')->group(function () {
    Route::get('data-siswa/{id}', [LembagaController::class, 'dataSiswa'])->name('data_siswa');
    Route::get('siswa/cetak', [SiswaController::class, 'cetak'])->name('cetak_siswa');

    Route::get('nilai-siswa/{id}', [LembagaController::class, 'showNilai'])->name('nilai_siswa');
    Route::get('nilai-siswa/{id}/cetak', [LembagaController::class, 'cetakNilai'])->name('cetak_nilai_siswa');
    Route::get('absensi-siswa/{id}', [LembagaController::class, 'showAbsensi'])->name('absensi_siswa');
    Route::get('absensi-siswa/{id}/cetak', [LembagaController::class, 'cetakAbsensiSiswa'])->name('absensi_siswa_cetak');

    Route::get('hafalan-siswa/{id}', [LembagaController::class, 'showHafalan'])->name('hafalan_siswa');
    Route::get('hafalan-siswa/{id}/cetak', [LembagaController::class, 'cetakHafalan'])->name('hafalan_cetak');

    Route::get('data-pegawai', [LembagaController::class, 'dataPegawai'])->name('data_pegawai');
    Route::get('data-pegawai/cetak', [LembagaController::class, 'cetakPegawai'])->name('cetak_pegawai');

    Route::get('absensi-pegawai', [LembagaController::class, 'absensiPegawai'])->name('absensi_pegawai');
    Route::get('absensi-pegawai/cetak', [LembagaController::class, 'cetakAbsensiPegawai'])->name('cetak_absensi_pegawai');

    Route::get('cetak-data', [LembagaController::class, 'cetakData'])->name('cetak_data');
});
Route::prefix('yayasan')->middleware(['auth'])->name('yayasan.')->group(function () {
Route::get('data-siswa/{jenjang}', [YayasanController::class, 'dataSiswa'])->name('data_siswa');
Route::get('data-siswa/{jenjang}/cetak', [YayasanController::class, 'cetakDataSiswa'])->name('cetak_siswa');
Route::get('nilai_siswa/{jenjang}', [YayasanController::class, 'showNilai'])->name('nilai_siswa');
Route::get('cetak_nilai/{jenjang}', [YayasanController::class, 'cetakNilai'])->name('cetak_nilai');
Route::get('absensi-siswa/{jenjang}', [YayasanController::class, 'showAbsensi'])->name('absensi_siswa');
Route::get('cetak-absensi-siswa/{jenjang}', [YayasanController::class, 'cetakAbsensiSiswa'])->name('cetak_absensi_siswa');
Route::get('hafalan-siswa/{jenjang}', [YayasanController::class, 'showHafalan']) ->name('hafalan_siswa');
Route::get('data-pegawai/{jenjang}', [YayasanController::class, 'dataPegawai'])->name('data_pegawai');
Route::get('data-pegawai/{jenjang}/cetak', [YayasanController::class, 'cetakPegawai'])->name('cetak_pegawai');
Route::get('absensi-pegawai/{jenjang}', [YayasanController::class, 'absensiPegawai'])->name('absensi_pegawai');
Route::get('absensi-pegawai/{jenjang}/cetak', [YayasanController::class, 'cetakAbsensiPegawai'])->name('cetak_absensi_pegawai');



});








Route::prefix('guru')->middleware('auth')->group(function () {
    // Routes untuk GuruController (CRUD nilai, absensi, hafalan, raport)
    Route::get('nilai', [GuruController::class, 'nilai'])->name('GuruNilai');
    Route::post('nilai', [GuruController::class, 'storenilai'])->name('guru.nilai.store');
    Route::get('nilai/create', [GuruController::class, 'createNilai'])->name('guru.nilai.create');
    Route::put('nilai/{id}', [GuruController::class, 'updatenilai'])->name('guru.nilai.update');
    Route::delete('nilai/{id}', [GuruController::class, 'deletenilai'])->name('guru.nilai.destroy');
    Route::get('/get-mapel-by-kelas/{kelasId}', [GuruController::class, 'getMapelByKelas']);
    Route::get('/get-siswa-by-kelas-mapel/{kelasId}/{mapelId}', [GuruController::class, 'getSiswaByKelasMapel']);


    Route::get('hafalan', [GuruController::class, 'hafalan'])->name('GuruHafalan');
    Route::post('hafalan', [GuruController::class, 'storeHafalan'])->name('guru.hafalan.store');
    Route::put('hafalan/{id}', [GuruController::class, 'updateHafalan'])->name('guru.hafalan.update');
    Route::delete('hafalan/{id}', [GuruController::class, 'destroyHafalan'])->name('guru.hafalan.destroy');

    Route::get('absensi', [GuruController::class, 'absensi'])->name('GuruAbsensi');
    Route::post('absensi', [GuruController::class, 'storeAbsensi'])->name('guru.absensi.store');
    Route::put('absensi/{id}', [GuruController::class, 'updateAbsensi'])->name('guru.absensi.update');
    Route::delete('absensi/{id}', [GuruController::class, 'destroyAbsensi'])->name('guru.absensi.destroy');
    Route::get('absensi/get-siswa-by-kelas/{kelasId}', [GuruController::class, 'getSiswaByKelas']);

    Route::get('raport', [GuruController::class, 'raport'])->name('guru.raport.index');

    
});


   




    


Route::middleware(['auth'])->group(function () {
   
    Route::get('/setting-akun', [UserController::class, 'settingAkun'])->name('setting'); // HANYA satu nama route
    Route::post('/setting-akun', [UserController::class, 'update'])->name('setting.update');
    Route::get('/setting-akun-alias', [UserController::class, 'settingAkun'])->name('settingAkun');



    //Pengumuman Staff
    // Route::get('staff/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    // Route::post('staff/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::get('/pengumuman/{id}/edit', [PengumumanController::class, 'edit'])->name('pengumuman.edit');
    Route::put('/pengumuman/{id}', [PengumumanController::class, 'update'])->name('pengumuman.update');
    Route::delete('/pengumuman/{id}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');



    // Daftar route Pegawai
    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('TambahPegawaiStaff', [PegawaiController::class, 'TambahPegawaiStaff'])->name('TambahPegawaiStaff');
    Route::post('/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::get('/pegawai/{niy}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
    Route::put('/pegawai/{niy}', [PegawaiController::class, 'update'])->name('pegawai.update');
    Route::delete('/pegawai/{niy}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
    Route::get('/LihatPegawaiStaff', [PegawaiController::class, 'index'])->name('LihatPegawaiStaff');

    //absensi pegawai
    Route::get('/absensi-pegawai', [AbsensiPegawaiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi-pegawai/masuk', [AbsensiPegawaiController::class, 'absenMasuk'])->name('absensi.masuk');
    Route::post('/absensi-pegawai/keluar', [AbsensiPegawaiController::class, 'absenKeluar'])->name('absensi.keluar');
    
    
    
    // --- Tambahan: Route CRUD untuk siswa ---
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index'); // lihat semua siswa
    Route::get('TambahSiswaStaff', [SiswaController::class, 'TambahSiswa'])->name('TambahSiswaStaff'); // form tambah siswa
    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store'); // proses simpan siswa
    Route::get('/siswa/{id}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
    Route::put('/siswa/{id}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/siswa/{id}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    Route::get('/LihatDataSiswaStaff', [SiswaController::class, 'index'])->name('LihatDataSiswaStaff');
    Route::post('import-datasiswa-staff', [SiswaController::class, 'import'])->name('ImportDatasiswaStaff');
    Route::get('/import-datasiswa-staff', [SiswaController::class, 'showImportForm'])->name('ImportDataSiswaStaff');
    Route::post('/pegawai/import', [PegawaiController::class, 'import'])->name('pegawai.import');
    Route::get('/pegawai/import', [PegawaiController::class, 'showImportForm'])->name('ImportPegawaiStaff');
   // Rute Kelas
    Route::get('/kelas', [KelasController::class, 'index'])->name('StaffKelasSiswa');
    Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
    Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
    Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');

    // Rute Mapel
    Route::get('/mapel', [MapelController::class, 'index'])->name('StaffMapelSIswa');
    Route::post('/mapel', [MapelController::class, 'store'])->name('mapel.store');
    Route::put('/mapel/{id}', [MapelController::class, 'update'])->name('mapel.update');
    Route::delete('/mapel/{id}', [MapelController::class, 'destroy'])->name('mapel.destroy');

    // Rute Tahun Ajaran
    Route::get('/tahun-ajaran', [TahunAjaranController::class, 'index'])->name('StaffTahunAjaranSiswa');
    Route::post('/tahun-ajaran', [TahunAjaranController::class, 'store'])->name('tahun-ajaran.store');
    Route::put('/tahun-ajaran/{id}', [TahunAjaranController::class, 'update'])->name('tahun-ajaran.update');
    Route::delete('/tahun-ajaran/{id}', [TahunAjaranController::class, 'destroy'])->name('tahun-ajaran.destroy');

    // Rute Master Surat
    Route::get('/master-surat', [MasterSuratController::class, 'index'])->name('StaffMasterSuratSiswa');
    Route::post('/master-surat', [MasterSuratController::class, 'store'])->name('master-surat.store');
    Route::put('/master-surat/{id}', [MasterSuratController::class, 'update'])->name('master-surat.update');
    Route::delete('/master-surat/{id}', [MasterSuratController::class, 'destroy'])->name('master-surat.destroy');
    Route::get('/kelas-mapel-siswa', [KelasMapelController::class, 'index'])->name('StaffKelasMapelSiswa');
    Route::post('/kelas-mapel-siswa', [KelasMapelController::class, 'store'])->name('kelas-mapel.store');
    Route::put('/kelas-mapel-siswa/{id}', [KelasMapelController::class, 'update'])->name('kelas-mapel.update');
    Route::delete('/kelas-mapel-siswa/{id}', [KelasMapelController::class, 'destroy'])->name('kelas-mapel.destroy');
    Route::get('/regis-mapel-siswa', [RegisMapelSiswaController::class, 'index'])->name('StaffRegisMapelSiswa');
    Route::post('/regis-mapel-siswa', [RegisMapelSiswaController::class, 'store'])->name('regis-mapel.store');
    Route::put('/regis-mapel-siswa/{id}', [RegisMapelSiswaController::class, 'update'])->name('regis-mapel.update');
    Route::delete('/regis-mapel-siswa/{id}', [RegisMapelSiswaController::class, 'destroy'])->name('regis-mapel.destroy');

});





