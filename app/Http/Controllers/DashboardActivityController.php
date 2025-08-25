<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserActivity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardActivityController extends Controller
{
    public function index(Request $request)
    {
        UserActivity::log("https://bupesta.web.bps.go.id/user-activity");

        $data = [];

        $data["judul"] = "Dashboard Aktivitas User";

        $tanggal = $request->input('date') ?? now()->format('Y-m-d');
        $all = $request->boolean('all');

        // Data login user
        $data["dailyLogins"] = DB::table('user_activities as ua')
            ->join('users as u', 'ua.user_id', '=', 'u.nip_pegawai')
            ->select(
                DB::raw('DATE(ua.created_at) as login_date'),
                'u.name',
                'u.username',
                'u.kode_satker',
                DB::raw('COUNT(*) as total_activity')
            )
            ->when(!$all, fn($q) => $q->whereDate('ua.created_at', $tanggal))
            ->where('ua.activity', 'login')
            ->groupBy('login_date', 'u.name', 'u.username', 'u.kode_satker')
            ->orderByDesc('total_activity')
            ->get();

        // Rekap aktivitas
        $data["aktivitasSummary"] = DB::table('user_activities')
            ->select('activity', DB::raw('COUNT(*) as total'))
            ->when(!$all, fn($q) => $q->whereDate('created_at', $tanggal))
            ->groupBy('activity')
            ->orderByDesc('total')
            ->get();

        $data['chartData'] = DB::table('user_activities')
            ->selectRaw('DATE(created_at) as date, COUNT(DISTINCT user_id) as total')
            ->where('activity', 'login')
            ->whereDate('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($row) => ['date' => $row->date, 'total' => (int)$row->total])
            ->toArray();

        if ($request->ajax()) {
            return view('aktivitasuser.partials.wrapper', compact('data'))->render();
        }

        return view('aktivitasuser.dashboardaktivitas', compact('data'));
    }

    public function store(Request $request)
    {
        $existing = DB::table('user_activities')
            ->where('user_id', '340060473')
            ->where('url', $request->url)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Already logged']);
        }

        DB::table('user_activities')->insert([
            'user_id' => '340060473',
            'activity' => $request->url,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Access logged']);
    }
    public function api_useractivities()
    {
        return response()->json(UserActivity::orderBy('created_at', 'desc')->get());
    }
}
