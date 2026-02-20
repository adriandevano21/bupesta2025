<?php

namespace App\Http\Controllers;

use App\Models\angka_kinerja;
use App\Models\bulan;
use App\Models\IndikatorKinerja;
use App\Models\OperatorEntri;
use App\Models\satker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class DashboardkinerjaController extends Controller
{
    public function kinerja2025()
    {
        $data = [];
        $data["judul"] = "Dashboard Kinerja";
        return view('kinerja.kinerja', compact('data'));
    }
}
