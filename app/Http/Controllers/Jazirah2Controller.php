<?php

namespace App\Http\Controllers;

use App\Models\Jazirah2_Hasil;
use App\Models\Jazirah2_Indikator;
use App\Models\Jazirah2_User;
use App\Models\Satker;
use Carbon\Carbon;
// use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Google\Client;
use Google\Service\Drive;
// use Symfony\Component\HttpFoundation\JsonResponse;

// use function PHPUnit\Framework\isNull;

class Jazirah2Controller extends Controller
{
    public function index()
    {
        $data["judul"] = "Jazirah";
        $data["indikator"] = Jazirah2_Indikator::all();
        return view('jazirah2026.dashbooard', compact('data'));
    }

    public function lembarkerja(Request $request)
    {
        $request->validate([
            'pilar' => ['nullable', 'in:I,II,III,IV,V,VI'],
            'satker' => ['nullable', 'string', 'max:50'], // bisa kamu ketatkan jadi in/exists kalau perlu
        ]);

        $data["judul"] = "New Jazirah";

        $data["user_active"] = Jazirah2_User::where('username', 'ita.meriati')->get();
        // $data["user_active"] = Jazirah2_User::where('username', 'cut.amalia')->get();
        $data['pilars'] = ['I', 'II', 'III', 'IV', 'V', 'VI']; // Contoh data pilar
        $data["data_subpilar"] = Jazirah2_Indikator::query()
            ->select('kode_3', 'kode_4', 'rencana_kerja', 'level')
            ->where('level', 4)
            ->get();
        // dd($data["data_subpilar"]);

        // dd($data["user_active"]);

        if ($data["user_active"][0]->role === 'admin' or $data["user_active"][0]->kode_satker === '1100') {
            $data["satker_selected"] = $data["satker_selected"] = $request->input('satker') ?? '1100';; // contoh: "11" / "1100" / dll
        } else {
            $data["satker_selected"] = $data["user_active"][0]->kode_satker;
        };

        // dd($data["satker_selected"]);

        // nilai filter terpilih
        $data["pilar_selected"] = $request->input('pilar');   // "I".."VI" atau null
        $data['subpilar_selected'] = $request->input('subpilar');
        // $data["satker_selected"] = "1100"; // contoh: "11" / "1100" / dll
        $data["tahun"] = $request->input('tahun') ?? '2025'; // contoh: "11" / "1100" / dll

        $data["users"] = Jazirah2_User::query()
            ->select('name', 'username', 'kode_satker', 'role')
            ->where('kode_satker', $data["satker_selected"])
            ->orderBy('name')
            ->get();
        // dd($data["users"]);

        // daftar satker untuk dropdown
        $data["satker"] = Satker::query()
            ->select('kode_satker', 'nama_satker')
            ->orderBy('kode_satker')
            ->get();

        $tahunLalu = $data["tahun"] - 1;

        $data["indikator"] = Jazirah2_Indikator::query()
            ->when($request->filled('pilar'), function ($q) use ($data) {
                $q->where(function ($sub) use ($data) {
                    $sub->where('kode_3', $data["pilar_selected"])
                        ->orWhere('level', 2);
                });
            })
            // 2. Filter Subpilar (AND) - Hanya jalan jika input subpilar ada
            ->when(
                $request->filled('subpilar'),
                fn($q) =>
                $q->where(function ($group) use ($data) {

                    // 1. Kondisi Utama: Kode 4 sesuai Subpilar yang dipilih
                    $group->where('kode_4', $data['subpilar_selected'])

                        // 2. ATAU: Level 3 DAN Kode 3 sesuai Pilar (Grouping lagi di dalam)
                        ->orWhere(function ($sub) use ($data) {
                            $sub->where('level', 3)
                                ->where('kode_3', $data['pilar_selected']);
                        })

                        // 3. ATAU: Level 2
                        ->orWhere('level', 2);
                })
                // $q->where('kode_4', $data['subpilar_selected'])
            )
            ->with(['isian' => function ($q) use ($data, $tahunLalu) {

                $q->where('satker', $data["satker_selected"])
                    ->where('tahun', $data["tahun"])
                    ->select('jazirah2_hasil.*') // penting! jangan hilangkan
                    ->selectRaw("(
                                    SELECT GROUP_CONCAT(b.singkatan ORDER BY CAST(b.kode_bulan AS UNSIGNED) SEPARATOR ', ')
                                    FROM bulan b
                                    WHERE FIND_IN_SET(b.kode_bulan, jazirah2_hasil.bulan_target)
                                ) AS bulan_target_nama")
                    ->selectRaw("(
                                    SELECT GROUP_CONCAT(b.singkatan ORDER BY CAST(b.kode_bulan AS UNSIGNED) SEPARATOR ', ')
                                    FROM bulan b
                                    WHERE FIND_IN_SET(b.kode_bulan, jazirah2_hasil.bulan_realisasi)
                                ) AS bulan_realisasi_nama")
                    // 1. Ambil Rencana Aksi Tahun Lalu
                    ->selectRaw("(
                                    SELECT prev.rencanaaksi
                                    FROM jazirah2_hasil prev
                                    WHERE prev.satker = jazirah2_hasil.satker
                                    AND prev.tahun = ?
                                    AND prev.id_indikator = jazirah2_hasil.id_indikator
                                    LIMIT 1
                                ) AS rencanaaksi_tahun_lalu", [$tahunLalu]) // Binding param ke-1

                    // 2. Ambil Output Tahun Lalu (Copy paste query di atas, ubah kolomnya)
                    ->selectRaw("(
                                    SELECT prev.output
                                    FROM jazirah2_hasil prev
                                    WHERE prev.satker = jazirah2_hasil.satker
                                    AND prev.tahun = ?
                                    AND prev.id_indikator = jazirah2_hasil.id_indikator
                                    LIMIT 1
                                ) AS output_tahun_lalu", [$tahunLalu]); // Binding param ke-2;
            }])
            ->get();
        // dd($data["indikator"][4]);
        // dd($data);
        return view('jazirah2026.jazirah-lembarkerja', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $data = $request->validate([
            'penanggungjawab' => ['nullable', 'array'],
            'penanggungjawab.*' => ['string'],

            'bulan_target' => ['nullable', 'array'],
            'bulan_target.*' => ['integer', 'between:1,12'],

            'bulan_realisasi' => ['nullable', 'array'],
            'bulan_realisasi.*' => ['integer', 'between:1,12'],
        ]);

        if ($request->pengisian === "pertama") {
            if ($request->penanggungjawab) {
                $Penanggungjawab = implode(', ', $request->penanggungjawab);
            } else {
                $Penanggungjawab = $request->penanggungjawab;
            };

            $bulanTarget = collect($request->input('bulan_target', []))
                ->map(fn($v) => (int)$v)->unique()->sort()->values()->implode(',');

            $bulanArray = explode(',', $bulanTarget);

            $progres_tw1 = "Tidak Ada Target";
            $progres_tw2 = "Tidak Ada Target";
            $progres_tw3 = "Tidak Ada Target";
            $progres_tw4 = "Tidak Ada Target";

            // Cek TW 1 (Bulan 1, 2, 3)
            if (array_intersect([1, 2, 3], $bulanArray)) {
                $progres_tw1 = (string)0;
            };

            // Cek TW 2 (Bulan 4, 5, 6)
            if (array_intersect([4, 5, 6], $bulanArray)) {
                $progres_tw2 = (string)0; // Ambil data progres TW 2 Anda di sini
            };

            // Cek TW 3 (Bulan 7, 8, 9)
            if (array_intersect([7, 8, 9], $bulanArray)) {
                $progres_tw3 = (string)0; // Ambil data progres TW 3 Anda di sini
            };

            // Cek TW 4 (Bulan 10, 11, 12)
            if (array_intersect([10, 11, 12], $bulanArray)) {
                $progres_tw4 = (string)0; // Ambil data progres TW 4 Anda di sini
            };

            $semua_progres = [$progres_tw1, $progres_tw2, $progres_tw3, $progres_tw4];

            // Menghitung apakah semua isi array adalah "Tidak Ada Target"
            if (count(array_unique($semua_progres)) === 1 && end($semua_progres) === "Tidak Ada Target") {
                $progres_th = "Tidak Ada Target";
                // Jalankan kode
            } else {
                $progres_th = (string)0;
            }

            // dd($progres_tw1, $progres_tw2, $progres_tw3, $progres_tw4, $progres_th);


            Jazirah2_Hasil::where('id', $id)->update([
                'penanggungjawab' => $Penanggungjawab,
                'rencanaaksi' => $request->rencanaaksi,
                'output' => $request->output,
                'bulan_target' => $bulanTarget,
                'progres_tw1' => $progres_tw1,
                'progres_tw2' => $progres_tw2,
                'progres_tw3' => $progres_tw3,
                'progres_tw4' => $progres_tw4,
                'progres_th' => $progres_th,
                'status_dokumen' => "1",
                'created_by_1' => $request->updateby,
                'created_at_1' => now(),
            ]);
        }

        if ($request->pengisian === "kedua") {
            // dd($request);

            $bulanTarget = Jazirah2_Hasil::where('id', $id)->value('bulan_target');
            // dd($bulanTarget);

            $bulanRealisasi = collect($request->input('bulan_realisasi', []))
                ->map(fn($v) => (int)$v)->unique()->sort()->values()->implode(',');

            // dd($bulanRealisasi);

            // 2. Ubah menjadi array agar bisa dihitung
            $arrTarget = explode(',', $bulanTarget);
            $arrRealisasi = explode(',', $bulanRealisasi);

            // --- PERHITUNGAN TAHUNAN (TH) ---
            $jumlahTargetTH = count($arrTarget);
            // dd($bulanTarget);

            if ($bulanTarget > 0) {
                // dd("Besar dari 0");
                // Cari irisan bulan yang ada di target DAN ada di realisasi
                $realisasiTH = array_intersect($arrTarget, $arrRealisasi);
                $jumlahRealisasiTH = count($realisasiTH);

                // Hitung persentase Tahunan
                $progres_th = number_format(($jumlahRealisasiTH / $jumlahTargetTH) * 100, 2);

                // Output: "80.00%" (Hasil dari 4/5 * 100)
                $progres_th_label = number_format($progres_th, 2) . "%";
            } else {
                // dd("Kecil dari 0");
                $progres_th = "Target Belum diisi";
            }

            // --- PERHITUNGAN TRIWULAN (Sama seperti sebelumnya) ---
            $mappingTW = [
                'tw1' => [1, 2, 3],
                'tw2' => [4, 5, 6],
                'tw3' => [7, 8, 9],
                'tw4' => [10, 11, 12],
            ];

            foreach ($mappingTW as $key => $range) {
                $targetDiTWIni = array_intersect($range, $arrTarget);
                $jumlahTarget = count($targetDiTWIni);

                if ($jumlahTarget > 0) {
                    $realisasiDiTWIni = array_intersect($targetDiTWIni, $arrRealisasi);
                    $jumlahRealisasi = count($realisasiDiTWIni);
                    $persentase = ($jumlahRealisasi / $jumlahTarget) * 100;
                    ${"progres_" . $key} = number_format($persentase, 2);
                } else {
                    if ($progres_th === "Target Belum diisi") {
                        ${"progres_" . $key} = 0;
                    } else {
                        ${"progres_" . $key} = "Tidak Ada Target";
                    }
                }
            }

            // dd($progres_tw1, $progres_tw2, $progres_tw3, $progres_tw4, (string)$progres_th);

            Jazirah2_Hasil::where('id', $id)->update([
                'bulan_realisasi' => $bulanRealisasi,
                'progres_tw1' => $progres_tw1,
                'progres_tw2' => $progres_tw2,
                'progres_tw3' => $progres_tw3,
                'progres_tw4' => $progres_tw4,
                'progres_th' => (string)$progres_th,
                'status_dokumen' => "2",
                'link_buktidukung' => $request->link_buktidukung,
                'jumlah_dokumen' => $request->total_files2,
                'created_by_2' => $request->updateby,
                'created_at_2' => now(),
            ]);
        }

        if ($request->pengisian === "ketiga") {

            Jazirah2_Hasil::where('id', $id)->update([
                'komentar_evaluator1' => $request->komentar_evaluator1,
                'status_dokumen' => "3",
                'created_by_3' => $request->updateby,
                'created_at_3' => now(),
            ]);
        }

        if ($request->pengisian === "keempat") {
            // dd($request->updateby);
            Jazirah2_Hasil::where('id', $id)->update([
                'status_dokumen' => "4",
                'komentar_operator1' => $request->komentar_operator1,
                'created_by_4' => $request->updateby,
                'created_at_4' => now(),
            ]);
        }


        if ($request->pengisian === "kelima") {
            // dd($request->updateby);
            Jazirah2_Hasil::where('id', $id)->update([
                'status_dokumen' => "5",
                'created_by_5' => $request->updateby,
                'created_at_5' => now(),
            ]);
        }

        // if ($request->status_approval === "Sudah Upload" or $request->status_approval === "Dievaluasi" or $request->status_approval === "Sudah Sesuai") {
        //     if ($request->komentar_evaluator1 === null) {
        //         Jazirah2_Hasil::where('id', $id)->update([
        //             'status_approval' => $request->status_approval
        //         ]);
        //     } else {
        //         Jazirah2_Hasil::where('id', $id)->update([
        //             'status_approval' => $request->status_approval,
        //             'komentar_evaluator1' => $request->komentar_evaluator1
        //         ]);
        //     }
        // } elseif ($request->status_approval === "Sudah Tindak Lanjut") {
        //     Jazirah2_Hasil::where('id', $id)->update([
        //         'status_approval' => $request->status_approval,
        //         'komentar_operator1' => $request->komentar_operator1
        //     ]);
        // } else {
        //     if ($request->penanggungjawab) {
        //         $Penanggungjawab = implode(', ', $request->penanggungjawab);
        //     } else {
        //         $Penanggungjawab = $request->penanggungjawab;
        //     };

        //     $bulanTarget = collect($request->input('bulan_target', []))
        //         ->map(fn($v) => (int)$v)->unique()->sort()->values()->implode(',');

        //     $bulanRealisasi = collect($request->input('bulan_realisasi', []))
        //         ->map(fn($v) => (int)$v)->unique()->sort()->values()->implode(',');

        //     if ($request->komentar_evaluator1 === null) {
        //         if ($request->link_buktidukung) {
        //             Jazirah2_Hasil::where('id', $id)->update([
        //                 'status_approval' => "Sudah Upload",
        //             ]);
        //         };
        //     }

        //     // dd($request->link_buktidukung);
        //     Jazirah2_Hasil::where('id', $id)->update([
        //         'penanggungjawab' => $Penanggungjawab,
        //         'bulan_target' => $bulanTarget,       // "1,3,6"
        //         'bulan_realisasi' => $bulanRealisasi, // "..."
        //         'link_buktidukung' => $request->link_buktidukung
        //     ]);
        // }

        return back()->with('success', 'Isian berhasil diupdate.');
    }

    public function newlembarkerja(Request $request)
    {
        $data["judul"] = "New Jazirah";

        return view('jazirah2026.newjazirah-lembarkerja', compact('data'));
    }


    public function admin(Request $request)
    {
        $data["judul"] = "Admin Jazirah";
        $data["user_active"] = Jazirah2_User::where('username', 'adrian.devano')->get();
        $data["users"] = Jazirah2_User::query()
            ->select('name', 'username', 'kode_satker', 'role')
            ->where('kode_satker', '1100')
            ->orderBy('name')
            ->get();
        // daftar satker untuk dropdown
        $data["satker"] = Satker::query()
            ->select('kode_satker', 'nama_satker')
            ->orderBy('kode_satker')
            ->get();

        $data['pilars'] = ['I', 'II', 'III', 'IV', 'V', 'VI']; // Contoh data pilar

        return view('jazirah2026.admin-jazirah', compact('data'));
    }

    public function settingevaluator(Request $request)
    {
        // dd($request);
        // 1. Pastikan ID Evaluator konsisten
        $id_evaluator = $request->tahun . "." . $request->satker . "." . $request->pilar;

        try {
            // PERBAIKAN 1: Gunakan updateOrInsert untuk DB::table
            // Pastikan $request->kode_3 diganti $request->pilar jika form inputnya bernama 'pilar'
            DB::table('jazirah2_evaluator')->updateOrInsert(
                ['id_evaluator' => $id_evaluator], // Kondisi pencarian (WHERE)
                [
                    'tahun'        => $request->tahun,
                    'satker'       => $request->satker,
                    'kode_3'       => $request->pilar, // Asumsi: input form bernama 'pilar'
                    'evaluator'    => $request->penanggungjawab,
                    'updated_at'   => Carbon::now(),
                    // Opsional: tambahkan created_at jika ingin manual handle insert baru
                    // 'created_at' => Carbon::now(),
                ]
            );

            // PERBAIKAN 2: Update tabel Hasil dengan Join
            DB::table('jazirah2_hasil')
                ->join('jazirah2_indikator', 'jazirah2_hasil.id_indikator', '=', 'jazirah2_indikator.id')
                ->where('jazirah2_hasil.tahun', $request->tahun)
                ->where('jazirah2_hasil.satker', $request->satker)
                ->where('jazirah2_indikator.kode_3', $request->pilar)
                ->update([
                    'jazirah2_hasil.created_by_3' => $request->penanggungjawab
                ]);

            return redirect()->back()->with('success', 'Data kegiatan berhasil diupdate.');
        } catch (\Exception $e) {
            // TIPS DEBUGGING: Buka komentar di bawah untuk melihat pesan error asli
            // dd($e->getMessage());

            return redirect()->back()->with('gagal', 'Data kegiatan gagal diupdate. Error: ' . $e->getMessage());
        }
    }

    public function getFileList(Request $request)
    {
        // Ambil folder_id dari inputan user (dikirim via AJAX)
        $folderId = $request->input('folder_id');

        if (!$folderId) {
            return response()->json(['error' => 'Folder ID tidak ditemukan'], 400);
        }

        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Drive::DRIVE_METADATA_READONLY);
        $service = new Drive($client);

        // Ambil nama folder root untuk keperluan display
        try {
            $rootFolder = $service->files->get($folderId, ['fields' => 'name']);
            $rootName = $rootFolder->getName();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Folder tidak ditemukan atau akses ditolak'], 404);
        }

        // Panggil fungsi rekursif (sama seperti sebelumnya)
        $allFiles = $this->getFilesRecursive($service, $folderId, $rootName);

        return response()->json([
            'total_count' => count($allFiles),
            'files' => $allFiles
        ]);
    }
    private function getFilesRecursive($service, $folderId, $currentFolderName): array
    {
        $filesData = [];

        // ---------------------------------------------------------
        // BAGIAN 1: Ambil File (PDF/Image) di folder INI
        // ---------------------------------------------------------
        $optParamsFiles = [
            'q' => "'$folderId' in parents and trashed = false and (mimeType = 'application/pdf' or mimeType contains 'image/')",
            'fields' => 'nextPageToken, files(id, name, webViewLink, mimeType, createdTime, modifiedTime)',
            'pageSize' => 1000
        ];

        do {
            $results = $service->files->listFiles($optParamsFiles);
            foreach ($results->getFiles() as $file) {
                $filesData[] = [
                    'name' => $file->getName(),
                    'link' => $file->getWebViewLink(),
                    'type' => $file->getMimeType(),
                    'folder_name' => $currentFolderName,

                    // Format aslinya adalah ISO 8601 (contoh: 2023-10-27T10:00:00.000Z)
                    // Anda bisa langsung mengirimnya, atau memformatnya dengan Carbon:
                    'created_at' => $file->getCreatedTime(),
                    'updated_at' => $file->getModifiedTime(),

                    // Opsi: Jika ingin format yang lebih mudah dibaca (misal: "27 Oct 2023")
                    // 'created_readable' => \Carbon\Carbon::parse($file->getCreatedTime())->format('d M Y H:i'),
                ];
            }
            $optParamsFiles['pageToken'] = $results->getNextPageToken();
        } while ($optParamsFiles['pageToken']);


        // ---------------------------------------------------------
        // BAGIAN 2: Cari Sub-folder (Ambil ID DAN NAMANYA)
        // ---------------------------------------------------------
        $optParamsFolders = [
            'q' => "'$folderId' in parents and trashed = false and mimeType = 'application/vnd.google-apps.folder'",
            // PENTING: Kita minta field 'name' juga di sini
            'fields' => 'nextPageToken, files(id, name)',
            'pageSize' => 1000
        ];

        do {
            $folders = $service->files->listFiles($optParamsFolders);

            foreach ($folders->getFiles() as $subFolder) {
                // Rekursif: Kita kirimkan ID sub-folder DAN Nama sub-folder tersebut
                $subFolderFiles = $this->getFilesRecursive(
                    $service,
                    $subFolder->getId(),
                    $subFolder->getName() // Kirim nama folder anak ke fungsi berikutnya
                );

                // Gabungkan hasilnya
                $filesData = array_merge($filesData, $subFolderFiles);
            }

            $optParamsFolders['pageToken'] = $folders->getNextPageToken();
        } while ($optParamsFolders['pageToken']);

        return $filesData;
    }
}
