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

        // $data["user_active"] = Jazirah2_User::where('username', 'gunadi.subagia')->get();
        $data["user_active"] = Jazirah2_User::where('username', 'adrian.devano')->get();
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
        $data["tahun"] = $request->input('tahun') ?? '2026'; // contoh: "11" / "1100" / dll

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

        $id_indikator_me = null;

        // dd($request->input('task'));
        if ($request->input('task') !== null) {
            $id_indikator_me = DB::table('Jazirah2_Hasil')
                ->where('penanggungjawab', 'LIKE', '%' . $data["user_active"][0]->username . '%')
                ->orWhere('created_by_3', 'LIKE', '%' . $data["user_active"][0]->username . '%')
                ->pluck('id_indikator');
        };

        // dd($id_indikator_me);

        $data["indikator"] = Jazirah2_Indikator::query()
            // Menambahkan filter berdasarkan id dari tabel hasil (wajib)
            ->when(!is_null($id_indikator_me), function ($q) use ($id_indikator_me) {
                $q->whereIn('id', $id_indikator_me);
            })
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
                    WHERE TRIM(prev.satker) = TRIM(jazirah2_hasil.satker)
                    AND prev.tahun = ?
                    AND TRIM(prev.id_indikator) = TRIM(jazirah2_hasil.id_indikator)
                    -- AND prev.deleted_at IS NULL -- Buka komentar ini jika pakai soft deletes
                    ORDER BY prev.id DESC
                    LIMIT 1
                ) AS rencanaaksi_tahun_lalu", [$tahunLalu])

                    // 2. Ambil Output Tahun Lalu
                    ->selectRaw("(
                    SELECT prev.output
                    FROM jazirah2_hasil prev
                    WHERE TRIM(prev.satker) = TRIM(jazirah2_hasil.satker)
                    AND prev.tahun = ?
                    AND TRIM(prev.id_indikator) = TRIM(jazirah2_hasil.id_indikator)
                    -- AND prev.deleted_at IS NULL -- Buka komentar ini jika pakai soft deletes
                    ORDER BY prev.id DESC
                    LIMIT 1
                ) AS output_tahun_lalu", [$tahunLalu]); // Binding param ke-2;
            }])
            ->get();
        // dd($data["indikator"][155]);
        // dd($data);
        return view('jazirah2026.jazirah-lembarkerja', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // dd($request);

        $tahun = Jazirah2_Hasil::findOrFail($id)->tahun;
        $indikator = Jazirah2_Hasil::findOrFail($id)->id_indikator;
        $pilar_selected = Jazirah2_Indikator::findOrFail($indikator)->kode_3;
        $subpilar_selected = Jazirah2_Indikator::findOrFail($indikator)->kode_4;
        dd($subpilar_selected);
        $id_indikator = Jazirah2_Hasil::findOrFail($id)->id_indikator;
        $id_indikator = $id_indikator - 1;
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

            if (!empty($request->rencanaaksi) && !empty($request->output) && !empty($bulanTarget)) {
                $status_dokumen = "1";
            } else {
                $status_dokumen = "0";
            }

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
                'status_dokumen' => $status_dokumen,
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
            if (!empty($bulanRealisasi) && !empty($request->link_buktidukung)) {
                $status_dokumen = "2";
            } else {
                $status_dokumen = "1";
            }

            Jazirah2_Hasil::where('id', $id)->update([
                'bulan_realisasi' => $bulanRealisasi,
                'progres_tw1' => $progres_tw1,
                'progres_tw2' => $progres_tw2,
                'progres_tw3' => $progres_tw3,
                'progres_tw4' => $progres_tw4,
                'progres_th' => (string)$progres_th,
                'status_dokumen' => $status_dokumen,
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

        // dd($data["tahun"]);

        return redirect()->route('jazirah.lembarkerja', ['tahun' => $tahun])
            ->withFragment('baris-' . $id_indikator);
    }

    public function newlembarkerja(Request $request)
    {
        $request->validate([
            'pilar' => ['nullable', 'in:I,II,III,IV,V,VI'],
            'satker' => ['nullable', 'string', 'max:50'], // bisa kamu ketatkan jadi in/exists kalau perlu
        ]);

        $data["judul"] = "New Jazirah";

        $data["user_active"] = Jazirah2_User::where('username', 'alfisyah')->get();
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
        // dd($data["indikator"][4]->isian->penanggungjawab);
        // dd($data);
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
        // dd($id_evaluator);

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
