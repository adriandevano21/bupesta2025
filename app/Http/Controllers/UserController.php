<?php

namespace App\Http\Controllers;

use App\Models\Bupesta_User;
use App\Models\Bupesta_Datasimpeg;
use App\Imports\DatasimpegImport;
use App\Models\Bupesta_Hukdis;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function updateProfil(Request $request)
    {
        Bupesta_User::where('nip_pegawai', $request->nip_pegawai)->update([
            'no_hp' => $request->no_hp
        ]);
        return redirect()->back()->with('success', 'Terima kasih, profil Anda telah lengkap!');
    }

    public function getProfilPegawai($nip)
    {
        $user = DB::table('bupesta_user')->where('nip_pegawai', $nip)->first();
        if ($user) {
            return response()->json($user);
        }
        return response()->json(['error' => 'Data pegawai tidak ditemukan'], 404);
    }

    public function kelolaUserIndex()
    {
        $users = DB::table('bupesta_user')
            ->leftJoin('bupesta_datasimpeg', 'bupesta_user.nip_pegawai', '=', 'bupesta_datasimpeg.nip')
            ->select(
                'bupesta_user.*',
                'bupesta_datasimpeg.nama as nama_simpeg',
                'bupesta_datasimpeg.wilayah as satker_simpeg',
                'bupesta_datasimpeg.jabatan as jabatan_simpeg',
                'bupesta_datasimpeg.gol_akhir as golongan_simpeg',
                // MAPPING NAMA WILAYAH MENJADI KODE SATKER
                DB::raw("CASE
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%simeulue%' THEN '1101'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%aceh singkil%' THEN '1102'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%aceh selatan%' THEN '1103'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%aceh tenggara%' THEN '1104'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%aceh timur%' THEN '1105'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%aceh tengah%' THEN '1106'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%aceh barat daya%' THEN '1112'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%aceh barat%' THEN '1107'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%aceh besar%' THEN '1108'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%pidie jaya%' THEN '1118'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%pidie%' THEN '1109'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%bireuen%' THEN '1110'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%aceh utara%' THEN '1111'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%gayo lues%' THEN '1113'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%aceh tamiang%' THEN '1114'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%nagan raya%' THEN '1115'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%aceh jaya%' THEN '1116'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%bener meriah%' THEN '1117'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%banda aceh%' THEN '1171'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%sabang%' THEN '1172'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%langsa%' THEN '1173'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%lhokseumawe%' THEN '1174'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%subulussalam%' THEN '1175'
                    WHEN LOWER(bupesta_datasimpeg.wilayah) LIKE '%prov. aceh%' OR LOWER(bupesta_datasimpeg.wilayah) LIKE '%provinsi aceh%' THEN '1100'
                    ELSE NULL
                END AS kode_wilayah")
            )
            ->orderBy('bupesta_user.kode_satker', 'asc')
            ->get();

        $listSatker = DB::table('bupesta_user')->whereNotNull('kode_satker')->where('kode_satker', '!=', '')->distinct()->pluck('kode_satker');

        $data = [
            'judul'       => 'Kelola Pengguna',
            'user_active' => Bupesta_User::where('nip_pegawai', '199906212022011001')->first(),
            // 'user_active' => Bupesta_User::where('nip_pegawai', auth()->user()->nip_pegawai)->first(),
            'id_judul'    => '0',
            'users'       => $users,
            'list_satker' => $listSatker
        ];

        return view('adminbupesta.kelolauser', compact('data'));
    }

    public function updateInline(Request $request)
    {
        // Parameter dari Javascript: nip_pegawai, kolom, value_baru
        try {
            DB::table('bupesta_user')
                ->where('nip_pegawai', $request->nip_pegawai)
                ->update([
                    $request->kolom => $request->value_baru,
                    'updated_at'    => now()
                ]);

            return response()->json(['success' => true, 'message' => 'Data berhasil disimpan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }

    public function updateKelolaUser(Request $request)
    {
        $request->validate([
            'nip_pegawai' => 'required'
        ]);

        DB::table('bupesta_user')
            ->where('nip_pegawai', $request->nip_pegawai)
            ->update([
                'tahun'       => $request->tahun,
                'no_hp'       => $request->no_hp,
                'kode_satker' => $request->kode_satker, // Update manual oleh Anda
                'golongan'    => $request->golongan,    // Update manual oleh Anda
                'jabatan'     => $request->jabatan,     // Update manual oleh Anda
                'urlfoto'     => $request->urlfoto,
                'bupesta'     => $request->bupesta,
                'jazirah'     => $request->jazirah,
                'cinema'      => $request->cinema,
                'kinerja'     => $request->kinerja,
                'ist'         => $request->ist,
                'updated_at'  => now()
            ]);

        return redirect()->back()->with('success', 'Data & Hak akses pengguna berhasil diperbarui secara manual!');
    }

    public function adminbupesta(Request $request)
    {
        // 1. Ambil daftar tanggal versi yang unik untuk isi dropdown filter
        $listVersi = Bupesta_Datasimpeg::select('tanggal_versidata')
            ->whereNotNull('tanggal_versidata')
            ->distinct()
            ->orderBy('tanggal_versidata', 'desc')
            ->pluck('tanggal_versidata');

        // 2. Query data SIMPEG
        $query = Bupesta_Datasimpeg::query();

        // Filter jika user memilih tanggal versi
        if ($request->filled('filter_versi')) {
            $query->where('tanggal_versidata', $request->filter_versi);
        }

        $simpeg = $query->orderBy('id', 'desc')->get();

        $data = [
            'judul'        => 'Data SIMPEG Pegawai',
            'user_active' => Bupesta_User::where('nip_pegawai', '199906212022011001')->first(),
            // 'user_active' => Bupesta_User::where('nip_pegawai', auth()->user()->nip_pegawai)->first(),
            'id_judul'     => 0,
            'simpeg'       => $simpeg,
            'list_versi'   => $listVersi,
            'filter_versi' => $request->filter_versi ?? ''
        ];

        return view('adminbupesta.admin', compact('data'));
    }

    public function importDatasimpeg(Request $request)
    {
        // 1. Validasi pastikan file adalah Excel dan Tanggal sudah diisi
        $request->validate([
            'file_excel'        => 'required|mimes:xls,xlsx',
            'tanggal_versidata' => 'required|date'
        ]);

        try {
            // 2. Lakukan Impor
            Excel::import(
                new DatasimpegImport($request->tanggal_versidata),
                $request->file('file_excel')
            );

            return redirect()->back()->with('success', 'Data SIMPEG berhasil diimpor beserta versi tanggalnya!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan impor: ' . $e->getMessage());
        }
    }

    public function destroyByVersiData(Request $request)
    {
        $request->validate([
            'tanggal_versidata' => 'required|date'
        ]);

        $jumlahTerhapus = Bupesta_Datasimpeg::where('tanggal_versidata', $request->tanggal_versidata)->delete();

        if ($jumlahTerhapus > 0) {
            return redirect()->route('simpeg.index')->with('success', "Berhasil menghapus {$jumlahTerhapus} baris data SIMPEG untuk versi tanggal " . \Carbon\Carbon::parse($request->tanggal_versidata)->format('d-m-Y') . "!");
        }

        return redirect()->back()->with('error', 'Gagal menghapus data atau tidak ada data pada versi tanggal tersebut.');
    }

    public function syncPegawaiBaru()
    {
        // 1. Ambil semua NIP yang saat ini sudah terdaftar di Bupesta User
        $userNips = DB::table('bupesta_user')->pluck('nip_pegawai')->toArray();

        // 2. Ambil data SIMPEG yang NIP-nya BELUM ADA di Bupesta User
        $simpegBaru = DB::table('bupesta_datasimpeg')
            ->whereNotIn('nip', $userNips)
            ->whereNotNull('nip')
            ->where('nip', '!=', '')
            ->get();

        $insertData = [];
        foreach ($simpegBaru as $simpeg) {

            // PEMETAAN WILAYAH KE KODE SATKER (Sama seperti logika SQL sebelumnya)
            $wil = strtolower($simpeg->wilayah);
            $kodeSatker = null;
            if (strpos($wil, 'simeulue') !== false) $kodeSatker = '1101';
            elseif (strpos($wil, 'aceh singkil') !== false) $kodeSatker = '1102';
            elseif (strpos($wil, 'aceh selatan') !== false) $kodeSatker = '1103';
            elseif (strpos($wil, 'aceh tenggara') !== false) $kodeSatker = '1104';
            elseif (strpos($wil, 'aceh timur') !== false) $kodeSatker = '1105';
            elseif (strpos($wil, 'aceh tengah') !== false) $kodeSatker = '1106';
            elseif (strpos($wil, 'aceh barat daya') !== false) $kodeSatker = '1112';
            elseif (strpos($wil, 'aceh barat') !== false) $kodeSatker = '1107';
            elseif (strpos($wil, 'aceh besar') !== false) $kodeSatker = '1108';
            elseif (strpos($wil, 'pidie jaya') !== false) $kodeSatker = '1118';
            elseif (strpos($wil, 'pidie') !== false) $kodeSatker = '1109';
            elseif (strpos($wil, 'bireuen') !== false) $kodeSatker = '1110';
            elseif (strpos($wil, 'aceh utara') !== false) $kodeSatker = '1111';
            elseif (strpos($wil, 'gayo lues') !== false) $kodeSatker = '1113';
            elseif (strpos($wil, 'aceh tamiang') !== false) $kodeSatker = '1114';
            elseif (strpos($wil, 'nagan raya') !== false) $kodeSatker = '1115';
            elseif (strpos($wil, 'aceh jaya') !== false) $kodeSatker = '1116';
            elseif (strpos($wil, 'bener meriah') !== false) $kodeSatker = '1117';
            elseif (strpos($wil, 'banda aceh') !== false) $kodeSatker = '1171';
            elseif (strpos($wil, 'sabang') !== false) $kodeSatker = '1172';
            elseif (strpos($wil, 'langsa') !== false) $kodeSatker = '1173';
            elseif (strpos($wil, 'lhokseumawe') !== false) $kodeSatker = '1174';
            elseif (strpos($wil, 'subulussalam') !== false) $kodeSatker = '1175';
            elseif (strpos($wil, 'prov. aceh') !== false || strpos($wil, 'provinsi aceh') !== false) $kodeSatker = '1100';

            // Siapkan data untuk di-insert (menggunakan NIP sebagai default username sementara)
            $insertData[] = [
                'nip_pegawai' => $simpeg->nip,
                'name'        => $simpeg->nama,
                'username'    => $simpeg->nip,
                'kode_satker' => $kodeSatker,
                'jabatan'     => $simpeg->jabatan,
                'golongan'    => $simpeg->gol_akhir,
                'bupesta'     => 'user', // Set default role
                'created_at'  => now(),
                'updated_at'  => now()
            ];
        }

        // 3. Masukkan ke database (Menggunakan array_chunk agar aman dari over-memory jika datanya ribuan)
        if (count($insertData) > 0) {
            foreach (array_chunk($insertData, 100) as $chunk) {
                DB::table('bupesta_user')->insert($chunk);
            }
        }

        // 4. Hitung juga berapa User yang tidak ada di SIMPEG (sebagai laporan)
        $simpegNips = DB::table('bupesta_datasimpeg')->pluck('nip')->toArray();
        $userTidakAda = DB::table('bupesta_user')->whereNotIn('nip_pegawai', $simpegNips)->count();

        return redirect()->back()->with('success', count($insertData) . ' Pegawai baru berhasil di-insert! Dan ditemukan ' . $userTidakAda . ' pegawai yang tidak ada di data SIMPEG.');
    }

    public function hukdispegawai()
    {
        $data['judul'] = 'Hukuman Disiplin Pegawai';
        $data['user_active'] = Bupesta_User::where('nip_pegawai', '199906212022011001')->first();
        // 'user_active' => Bupesta_User::where('nip_pegawai', auth()->user()->nip_pegawai)->first(),
        $data['id_judul'] = 0;
        $data['hukdis'] = Bupesta_Hukdis::orderBy('created_at', 'desc')->get();

        return view('adminbupesta.hukdispegawai', compact('data'));
    }

    public function storeHukdis(Request $request)
    {
        $pastedData = $request->input('excel_data');

        if (empty($pastedData)) {
            return back()->with('error', 'Data tidak boleh kosong!');
        }

        // Pisahkan data per baris (Enter)
        $rows = explode("\n", trim($pastedData));
        $insertData = [];

        foreach ($rows as $row) {
            // Pisahkan data per kolom (Tab) dari Excel
            $cols = explode("\t", trim($row));

            // Pastikan baris yang di-paste setidaknya punya 6 kolom
            if (count($cols) >= 6) {
                $insertData[] = [
                    'nip_bps'    => $cols[0],
                    'nama'       => $cols[1],
                    'satker'     => $cols[2],
                    'jenis'      => $cols[3],
                    'hukuman'    => $cols[4],
                    'tmt_mulai'  => $cols[5],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (count($insertData) > 0) {
            Bupesta_Hukdis::insert($insertData);
            return back()->with('success', count($insertData) . ' Data Hukdis Berhasil Ditambahkan!');
        }

        return back()->with('error', 'Gagal! Format baris tidak sesuai (Pastikan copy persis 6 kolom dari Excel).');
    }

    public function deleteHukdis(Request $request)
    {
        // Jika tombol "Hapus Semua" yang ditekan
        if ($request->action === 'all') {
            Bupesta_Hukdis::truncate(); // truncate() jauh lebih cepat & me-reset ID ke 1
            return back()->with('success', 'Seluruh data Riwayat Hukdis berhasil dikosongkan!');
        }

        // Jika tombol "Hapus Terpilih" yang ditekan
        if ($request->action === 'selected' && !empty($request->ids)) {
            Bupesta_Hukdis::whereIn('id', $request->ids)->delete();
            return back()->with('success', count($request->ids) . ' Data Hukdis terpilih berhasil dihapus!');
        }

        return back()->with('error', 'Tidak ada aksi atau data yang dipilih.');
    }

    public function dataSimpeg()
    {
        $data['judul'] = 'Data Pegawai (SIMPEG)';
        $data['user_active'] = Bupesta_User::where('nip_pegawai', '199906212022011001')->first();
        // 'user_active' => Bupesta_User::where('nip_pegawai', auth()->user()->nip_pegawai)->first(),
        $data['id_judul'] = 0;
        $data['simpeg'] = \App\Models\Bupesta_Datasimpeg::orderBy('created_at', 'desc')->get();

        return view('adminbupesta.admin', compact('data')); // Sesuaikan dengan nama file blade Anda
    }

    public function storeDataSimpeg(Request $request)
    {
        $pastedData = $request->input('excel_data');
        $tanggalVersi = $request->input('tanggal_versidata'); // Ambil input tanggal tunggal

        if (empty($pastedData) || empty($tanggalVersi)) {
            return back()->with('error', 'Data Excel dan Tanggal Versi Data tidak boleh kosong!');
        }

        $rows = explode("\n", trim($pastedData));
        $insertData = [];

        foreach ($rows as $row) {
            // Bersihkan \r jika copy-paste dari Windows, lalu pecah berdasarkan Tab
            $cols = explode("\t", rtrim($row, "\r"));

            // Pastikan baris memiliki minimal 14 kolom dari Excel
            if (count($cols) >= 14) {
                $insertData[] = [
                    'nip_bps'           => $cols[0],
                    'nip'               => $cols[1],
                    'nama'              => $cols[2],
                    'kode_org'          => $cols[3],
                    'jabatan'           => $cols[4],
                    'wilayah'           => $cols[5],
                    'tmt_jab'           => $cols[6],
                    'gol_akhir'         => $cols[7],
                    'tmt_gol'           => $cols[8],
                    'status'            => $cols[9],
                    'pend_sk'           => $cols[10],
                    'mks_thn'           => $cols[11],
                    'mks_bln'           => $cols[12],
                    'tempat_lahir'      => $cols[13],
                    'tanggal_versidata' => $tanggalVersi, // Disuntikkan ke semua baris
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];
            }
        }

        if (count($insertData) > 0) {
            \App\Models\Bupesta_Datasimpeg::insert($insertData);
            return back()->with('success', count($insertData) . ' Data SIMPEG Berhasil Ditambahkan untuk versi tanggal ' . $tanggalVersi);
        }

        return back()->with('error', 'Gagal! Format baris tidak sesuai (Pastikan copy persis 14 kolom dari Excel).');
    }

    public function updateDataSimpeg(Request $request)
    {
        $request->validate(['id_edit' => 'required']);

        \App\Models\Bupesta_Datasimpeg::where('id', $request->id_edit)->update([
            'nip_bps'           => $request->nip_bps,
            'nip'               => $request->nip,
            'nama'              => $request->nama,
            'kode_org'          => $request->kode_org,
            'jabatan'           => $request->jabatan,
            'wilayah'           => $request->wilayah,
            'tmt_jab'           => $request->tmt_jab,
            'gol_akhir'         => $request->gol_akhir,
            'tmt_gol'           => $request->tmt_gol,
            'status'            => $request->status,
            'pend_sk'           => $request->pend_sk,
            'mks_thn'           => $request->mks_thn,
            'mks_bln'           => $request->mks_bln,
            'tempat_lahir'      => $request->tempat_lahir,
            'tanggal_versidata' => $request->tanggal_versidata,
        ]);

        return back()->with('success', 'Data Pegawai atas nama ' . $request->nama . ' berhasil diperbarui!');
    }

    public function deleteDataSimpeg(Request $request)
    {
        // 1. Jika tombol "Kosongkan Tabel" ditekan
        if ($request->action === 'all') {
            \App\Models\Bupesta_Datasimpeg::truncate();
            return back()->with('success', 'Seluruh Data SIMPEG berhasil dikosongkan!');
        }

        // 2. Jika tombol "Hapus Terpilih" ditekan
        if ($request->action === 'selected' && !empty($request->ids)) {
            \App\Models\Bupesta_Datasimpeg::whereIn('id', $request->ids)->delete();
            return back()->with('success', count($request->ids) . ' Data SIMPEG terpilih berhasil dihapus!');
        }

        // 3. Jika tombol "Hapus Satuan" di baris tabel ditekan
        if ($request->action === 'single' && !empty($request->id_delete)) {
            \App\Models\Bupesta_Datasimpeg::where('id', $request->id_delete)->delete();
            return back()->with('success', 'Data pegawai berhasil dihapus!');
        }

        return back()->with('error', 'Tidak ada data atau aksi yang dipilih.');
    }

    public function aktivitasuser(Request $request)
    {
        $data['judul'] = 'Log Aktivitas Pengguna';
        $data['user_active'] = Bupesta_User::where('nip_pegawai', '199906212022011001')->first();
        // 'user_active' => Bupesta_User::where('nip_pegawai', auth()->user()->nip_pegawai)->first(),
        $data['id_judul'] = "3";
        
        // 1. Parameter Filter
        $tahun   =$request->input('tahun', '2026');
        $tanggal =$request->input('tanggal', Carbon::today()->toDateString()); // Default hari ini

        // Sorting Tabel
        $sortBy  =$request->input('sort_by', 'created_at');
        $sortDir = strtolower($request->input('sort_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSorts = ['created_at', 'user_id', 'activity', 'ip_address', 'user_agent'];
        if (!in_array($sortBy, $allowedSorts)) {$sortBy = 'created_at';
        }

        $data['judul']         = 'Log Aktivitas Pengguna';
        $data['tahun_pilihan'] =$tahun;
        $data['tanggal_pilihan'] =$tanggal;
        $data['sort_by']       =$sortBy;
        $data['sort_dir']      =$sortDir;

        // =========================================================================
        // BARIS 1: REKAP HARI INI / TANGGAL TERPILIH (1 HARI)
        // =========================================================================

        // A. Jumlah Pegawai yang Login pada Tanggal Terpilih
        $data['total_login_hari_ini'] = DB::table('user_activities')
            ->whereDate('created_at', $tanggal)
            ->whereRaw('LOWER(activity) LIKE ?', ['%login%'])
            ->selectRaw('COUNT(DISTINCT IFNULL(user_id, "Anon")) as total')
            ->value('total') ?? 0;

        // B. Top 5 Aktivitas Hari Ini / Terpilih (Kecuali Login)
        $data['top_aktivitas_hari_ini'] = DB::table('user_activities')
            ->select('activity', DB::raw('COUNT(DISTINCT CONCAT(DATE(created_at), IFNULL(user_id, "Anon"))) as total'))
            ->whereDate('created_at', $tanggal)
            ->whereRaw('LOWER(activity) NOT LIKE ?', ['%login%'])
            ->groupBy('activity')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // C. Aktivitas Terbanyak Hari Ini / Terpilih (Elemen Pertama)
        $data['aktivitas_terbanyak_hari_ini'] =$data['top_aktivitas_hari_ini']->first()->activity ?? '-';

        // D. Top 5 User Agent / Browser Hari Ini / Terpilih
        $data['top_user_agent_hari_ini'] = DB::table('user_activities')
            ->select('user_agent', DB::raw('COUNT(DISTINCT CONCAT(DATE(created_at), IFNULL(user_id, "Anon"))) as total'))
            ->whereDate('created_at', $tanggal)
            ->groupBy('user_agent')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // =========================================================================
        // BARIS 2: REKAPAN TREN (30 HARI & 12 BULAN)
        // =========================================================================

        // A. Tren Pegawai yang Akses Harian (30 Hari Terakhir s/d Tanggal Terpilih)
        $startDate30 = Carbon::parse($tanggal)->subDays(29)->toDateString();$harian30Raw = DB::table('user_activities')
            ->selectRaw('DATE(created_at) as tgl, COUNT(DISTINCT IFNULL(user_id, "Anon")) as total')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate30,$tanggal])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tgl', 'asc')
            ->pluck('total', 'tgl')
            ->toArray();

        $chartHarianLabels = [];$chartHarianData   = [];
        for ($i = 29; $i >= 0; $i--) {
            $dt = Carbon::parse($tanggal)->subDays($i);$dateKey = $dt->toDateString();$chartHarianLabels[] = $dt->translatedFormat('d M');$chartHarianData[]   = $harian30Raw[$dateKey] ?? 0;
        }

        $data['chart_harian_30'] = [             'labels' =>$chartHarianLabels,
            'data'   => $chartHarianData,
        ];

        // B. Tren Pegawai yang Akses Per Bulan (12 Bulan dalam Tahun Terpilih)
        $bulananRaw = DB::table('user_activities')
            ->selectRaw('MONTH(created_at) as bulan, COUNT(DISTINCT CONCAT(DATE(created_at), IFNULL(user_id, "Anon"))) as total')
            ->whereYear('created_at', $tahun)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'bulan')
            ->toArray();

        $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $data['chart_bulanan_12'] = [             'labels' =>$namaBulan,
            'data'   => array_map(fn($m) => $bulananRaw[$m] ?? 0, range(1, 12))
        ];

        // =========================================================================
        // BARIS 3: DATA LOG KESELURUHAN (TABEL & PAGINATION)
        // =========================================================================

        $data['aktivitas'] = DB::table('user_activities')
            ->whereYear('created_at', $tahun)
            ->orderBy($sortBy,$sortDir)
            ->paginate(50)
            ->appends([
                'tahun'    => $tahun,
                'tanggal'  => $tanggal,
                'sort_by'  => $sortBy,
                'sort_dir' => $sortDir,
            ]);

        return view('adminbupesta.aktivitas', compact('data'));
    }
}
