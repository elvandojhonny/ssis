<?php

namespace App\Services\CBT;

use App\Models\Ujian;
use Carbon\Carbon;

class FinalisasiUjianService
{
    public function handle(): void
    {
        Ujian::where('status', 'dipublikasi')
            ->where('waktu_selesai', '<=', Carbon::now())
            ->update([
                'status' => 'selesai',
            ]);
    }
}