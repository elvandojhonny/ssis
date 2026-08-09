<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\SesiAbsensi;
use App\Models\Ujian;
use Carbon\Carbon;

class LandingController extends Controller
{
    public function index()
    {
        $ujianAktif = Ujian::where('status', 'dipublikasi')->count();

        $stokBuku = Buku::sum('jumlah_tersedia');

        $absensiAktif = SesiAbsensi::whereDate('tanggal', today())
            ->where('status', 'aktif')
            ->count();

        return view('landing.index', compact(
            'ujianAktif',
            'stokBuku',
            'absensiAktif'
        ));
    }
}