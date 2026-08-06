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
}
