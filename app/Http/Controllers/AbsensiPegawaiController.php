<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AbsensiPegawai;
use Carbon\Carbon;

class AbsensiPegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Auth::user()->pegawai;

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Data pegawai tidak ditemukan.');
        }

        $today = Carbon::today()->toDateString();

        $absensiToday = AbsensiPegawai::where('id_pegawai', $pegawai->niy)
            ->where('tanggal', $today)
            ->first();

        $absensiAll = AbsensiPegawai::where('id_pegawai', $pegawai->niy)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('AbsensiPegawai', compact('pegawai', 'absensiToday', 'absensiAll'));
    }

    public function absenMasuk(Request $request)
    {
        $pegawai = Auth::user()->pegawai;

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Data pegawai tidak ditemukan.');
        }

        $today = Carbon::today()->toDateString();

        $absensi = AbsensiPegawai::where('id_pegawai', $pegawai->niy)
            ->where('tanggal', $today)
            ->first();

        if ($absensi && $absensi->waktu_masuk) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absen masuk hari ini.');
        }

        $status = 'hadir';
        $now = Carbon::now();

        if ($now->gt(Carbon::parse($today . ' 07:30:00'))) {
            $status = 'terlambat';
        }

        if (!$absensi) {
            $absensi = new AbsensiPegawai();
            $absensi->id_pegawai = $pegawai->niy;
            $absensi->tanggal = $today;
        }

        $absensi->waktu_masuk = $now;
        $absensi->status = $status;
        $absensi->save();

        return redirect()->back()->with('success', 'Absen masuk berhasil.');
    }

    public function absenKeluar(Request $request)
    {
        $pegawai = Auth::user()->pegawai;

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Data pegawai tidak ditemukan.');
        }

        $today = Carbon::today()->toDateString();

        $absensi = AbsensiPegawai::where('id_pegawai', $pegawai->niy)
            ->where('tanggal', $today)
            ->first();

        if (!$absensi || !$absensi->waktu_masuk) {
            return redirect()->back()->with('error', 'Anda belum absen masuk hari ini.');
        }

        if ($absensi->waktu_keluar) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absen keluar hari ini.');
        }

        $absensi->waktu_keluar = Carbon::now();
        $absensi->save();

        return redirect()->back()->with('success', 'Absen keluar berhasil.');
    }
}
