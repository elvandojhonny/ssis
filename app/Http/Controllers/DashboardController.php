<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isOperator()) {
            return $this->operatorDashboard();
        }

        if ($user->isGuru()) {
            return view('dashboard.guru');
        }

        if ($user->isSiswa()) {
            $user->load('siswa.kelas.tahunAjaran');

            return view('dashboard.siswa', compact('user'));
        }

        abort(403);
    }

    private function operatorDashboard()
    {
        $totalGuru = Guru::where('is_active', true)->count();

        $totalSiswa = Siswa::where('is_active', true)->count();

        $totalKelas = Kelas::where('is_active', true)->count();

        $tahunAjaranAktif = TahunAjaran::where(
            'is_active',
            true
        )->first();

        return view('dashboard.operator', compact(
            'totalGuru',
            'totalSiswa',
            'totalKelas',
            'tahunAjaranAktif'
        ));
    }
}