<?php

namespace App\Http\Controllers;

use App\Models\Bupesta_User;
use App\Models\Bupesta_Datasimpeg; // Import Model Baru
use App\Imports\DatasimpegImport;  // Import Class Excel
use Maatwebsite\Excel\Facades\Excel; // Facade Excel
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function update(Request $request)
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
            'id_judul'     => 0,
            'simpeg'       => $simpeg,
            'list_versi'   => $listVersi,
            'filter_versi' => $request->filter_versi ?? ''
        ];

        return view('adminbupesta.admin', compact('data'));
    }

    // --- FITUR BARU: IMPORT DATA SIMPEG ---
    public function importDatasimpeg(Request $request)
    {
        // 1. Validasi pastikan file adalah Excel dan Tanggal sudah diisi
        $request->validate([
            'file_excel'        => 'required|mimes:xls,xlsx',
            'tanggal_versidata' => 'required|date'
        ]);

        try {
            // 2. Lakukan Impor. Kita melempar variabel tanggal ke DatasimpegImport
            Excel::import(
                new DatasimpegImport($request->tanggal_versidata),
                $request->file('file_excel')
            );

            return redirect()->back()->with('success', 'Data SIMPEG berhasil diimpor beserta versi tanggalnya!');
        } catch (\Exception $e) {
            // Jika struktur excel salah, munculkan errornya
            return redirect()->back()->with('error', 'Terjadi kesalahan impor: ' . $e->getMessage());
        }
    }

    // --- FITUR BARU: HAPUS SEMUA DATA PER VERSI TANGGAL ---
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
