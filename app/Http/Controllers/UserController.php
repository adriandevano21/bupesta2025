<?php

namespace App\Http\Controllers;

use App\Models\Bupesta_User;
use App\Models\Bupesta_Datasimpeg;
use App\Imports\DatasimpegImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // =========================================================================
    // FITUR PROFIL USER (EXISTING)
    // =========================================================================

    // Diubah namanya jadi updateProfil agar tidak bentrok
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

    // =========================================================================
    // FITUR KELOLA PENGGUNA (BARU) - Gabung Simpeg & User Group by Satker
    // =========================================================================
    // =========================================================================
    // FITUR KELOLA PENGGUNA - Tampilan DataTables
    // =========================================================================

    // =========================================================================
    // FITUR KELOLA PENGGUNA - Tampilan DataTables dengan Sandingan SIMPEG
    // =========================================================================

    // =========================================================================
    // FITUR KELOLA PENGGUNA - Tampilan DataTables dengan Sandingan SIMPEG
    // =========================================================================

    // =========================================================================
    // FITUR KELOLA PENGGUNA - Tampilan DataTables
    // =========================================================================

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

    // FUNGSI BARU UNTUK MERESPON AJAX DARI INLINE EDITING
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
    // =========================================================================
    // FITUR ADMIN SIMPEG (EXISTING)
    // =========================================================================

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

    // =========================================================================
    // FITUR TAMBAH PEGAWAI OTOMATIS (SINKRONISASI DARI SIMPEG)
    // =========================================================================
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
}
