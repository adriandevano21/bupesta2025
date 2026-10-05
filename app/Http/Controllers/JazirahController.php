<?php

namespace App\Http\Controllers;

use App\Models\Bupesta_User;
use App\Models\Jazirah2_Hasil;
use App\Models\Jazirah2_Indikator;
use App\Models\Jazirah2_Komentar;
use App\Models\Jazirah2_User;
use App\Models\Jazirah_Kritiksaran;
use App\Models\UserActivity;
use App\Models\Satker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class JazirahController extends Controller
{
    private function getUserActive()
    {
        // --- MODE LOKAL (development) ---
        return Bupesta_User::where('nip_pegawai', '199906212022011001')->first();

        // --- MODE PRODUKSI (aktifkan baris ini & nonaktifkan baris di atas setelah deploy) ---
        // return Bupesta_User::where('nip_pegawai', auth()->user()->nip_pegawai)->first();
    }

    public function dashboard(Request $request)
    {
        UserActivity::log("https://bupesta.web.bps.go.id/jazirah-dashboard");

        // 1. Ambil Parameter dari Request (atau nilai default)
        $tahun          = $request->input('tahun', '2026');
        $mode           = $request->input('mode', 'rekap_satker');
        $periode        = $request->input('periode', 'bulan_berjalan');
        $jenisDataParam = $request->input('jenis_data', 'persentase_penetapan_target');
        
        $userActive = $this->getUserActive();
        $userSatker = $userActive->kode_satker ?? null;

        $viewName = 'monitoring_jazirah_' . $periode;
        $sufX     = ($periode === 'bulan_berjalan') ? '_bb' : '_' . $periode;

        // 2. Ambil List Satker secara Kondisional
        $satkerQuery = DB::table($viewName)
            ->select('satker')
            ->where('tahun', $tahun);

        // Batasi list satker HANYA JIKA mode-nya rekap_satker dan user bukan admin (1100)
        if ($mode === 'rekap_satker' && $userSatker !== '1100') {
            $satkerQuery->where('satker', $userSatker);
        }

        $satkers = $satkerQuery
            ->distinct()
            ->pluck('satker')
            ->sort()
            ->values();

        // 3. Tentukan Selected Satker dengan Proteksi
        if ($mode === 'rekap_satker' && $userSatker !== '1100') {
            $selectedSatker = $userSatker; // Paksa kunci ke satker user sendiri
        } else {
            // Jika 1100 (Bebas milih) ATAU mode lintas_satker
            $selectedSatker = $request->input('selected_satker');
            
            // Proteksi: Jika request kosong atau mencoba inject satker di luar daftar
            if (!$selectedSatker || !$satkers->contains($selectedSatker)) {
                $selectedSatker = $satkers->first();
            }
        }

        // 4. Siapkan Array Data Global (Dikirim ke View)
        $data = [
            'judul'           => 'New Jazirah - Dashboard',
            'id_judul'        => '3',
            'user_active'     => $userActive,
            'role'            => $userActive->jazirah ?? null,
            'menus'           => DB::table('jazirah_menus')->orderBy('urutan', 'asc')->get(),
            'mode'            => $mode,
            'periode'         => $periode,
            'satkers'         => $satkers,
            'jenis_data'      => $jenisDataParam,
            'selected_satker' => $selectedSatker,
        ];

        // 5. Olah Data Berdasarkan Mode
        if ($mode === 'lintas_satker') {
            
            $jenisDataKolom = match ($jenisDataParam) {
                'persentase_penetapan_target', 'target_setahun' => $jenisDataParam,
                'target_periode'    => ($periode === 'bulan_berjalan') ? 'target_bulan_berjalan' : 'target_triwulan_' . $periode,
                'realisasi_periode' => ($periode === 'bulan_berjalan') ? 'realisasi_bulan_berjalan' : 'realisasi_triwulan_' . $periode,
                'perlu_di_periksa', 'perlu_tindak_lanjut', 'sudah_validasi', 
                'persentase_realisasi', 'persentase_evaluasi', 
                'persentase_tindaklanjut', 'persentase_dokumen_selesai' => $jenisDataParam . $sufX,
                default => 'persentase_penetapan_target',
            };

            $rawData = DB::table($viewName)->where('tahun', $tahun)->get();
            $pivotData = [];

            foreach ($rawData as $item) {
                $key = $item->kode_2 . '|' . $item->kode_3;

                if (!isset($pivotData[$key])) {
                    $pivotData[$key] = [
                        'indikator' => $item->kode_2,
                        'pilar'     => $item->kode_3,
                    ];
                    
                    foreach ($satkers as $satker) {
                        $pivotData[$key][$satker] = null;
                    }
                }
                
                $pivotData[$key][$item->satker] = $item->$jenisDataKolom ?? null;
            }
            $data['pivotData'] = array_values($pivotData);

        } elseif ($mode === 'rekap_satker') {
            
            $rawData = DB::table($viewName)
                ->where('tahun', $tahun)
                ->where('satker', $selectedSatker) 
                ->get();

            $tCol = ($periode === 'bulan_berjalan') ? 'target_bulan_berjalan' : 'target_triwulan_' . $periode;
            $rCol = ($periode === 'bulan_berjalan') ? 'realisasi_bulan_berjalan' : 'realisasi_triwulan_' . $periode;
            
            $pDiperiksa = 'perlu_di_periksa' . $sufX;
            $pTL        = 'perlu_tindak_lanjut' . $sufX;
            $sValid     = 'sudah_validasi' . $sufX;
            
            $pReal   = 'persentase_realisasi' . $sufX;
            $pEval   = 'persentase_evaluasi' . $sufX;
            $pTL_pct = 'persentase_tindaklanjut' . $sufX;
            $pDok    = 'persentase_dokumen_selesai' . $sufX;

            // Pastikan kode_2 (indikator) terbawa di Object Collection
            $data['rekapData'] = $rawData->map(function ($item) use (
                $tCol, $rCol, $pDiperiksa, $pTL, $sValid, $pReal, $pEval, $pTL_pct, $pDok
            ) {
                return (object) [
                    'kode_2'                      => $item->kode_2,
                    'kode_3'                      => $item->kode_3,
                    'target_setahun'              => $item->target_setahun ?? 0,
                    'target_periode'              => $item->$tCol ?? 0,
                    'realisasi_periode'           => $item->$rCol ?? 0,
                    'perlu_di_periksa'            => $item->$pDiperiksa ?? 0,
                    'perlu_tindak_lanjut'         => $item->$pTL ?? 0,
                    'sudah_validasi'              => $item->$sValid ?? 0,
                    'persentase_penetapan_target' => $item->persentase_penetapan_target ?? null,
                    'persentase_realisasi'        => $item->$pReal ?? null,
                    'persentase_evaluasi'         => $item->$pEval ?? null,
                    'persentase_tindaklanjut'     => $item->$pTL_pct ?? null,
                    'persentase_dokumen_selesai'  => $item->$pDok ?? null,
                ];
            });
        }
        
        return view('jazirah.dashboard-jazirah', compact('data'));
    }

    public function storeMenu(Request $request)
    {
        DB::table('jazirah_menus')->insert([
            'title' => $request->title,
            'url'   => $request->url,
            'bg'    => $request->bg,
            'icon'  => $request->icon,
            'urutan'=> $request->urutan ?? 0,
        ]);
        return redirect()->back()->with('success', 'Menu berhasil ditambahkan!');
    }

    public function updateMenu(Request $request, $id)
    {
        DB::table('jazirah_menus')->where('id', $id)->update([
            'title' => $request->title,
            'url'   => $request->url,
            'bg'    => $request->bg,
            'icon'  => $request->icon,
            'urutan'=> $request->urutan ?? 0,
        ]);
        return redirect()->back()->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroyMenu($id)
    {
        DB::table('jazirah_menus')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Menu berhasil dihapus!');
    }

    public function lembarkerja(Request $request)
    {
        UserActivity::log("https://bupesta.web.bps.go.id/jazirah-lembarkerja");

        // 1. Validasi Input Parameter
        $request->validate([
            'kode_2'         => ['nullable', 'string'], // Menangkap parameter kode_2 (misal: "I." atau "II.")
            'pilar'          => ['nullable', 'in:I,II,III,IV,V,VI'],
            'satker'         => ['nullable', 'string', 'max:50'],
            'tugas_saya'     => ['nullable', 'in:0,1'],
            'status_dokumen' => ['nullable', 'string'] // Menangkap parameter "2,4" atau "4"
        ]);

        // 2. Setup Data User Active & Variabel Global
        $userActive = $this->getUserActive();
        $userRole   = $userActive->jazirah ?? '';
        $username   = $userActive->username ?? '';
        
        $tahun      = $request->input('tahun', '2026');
        $tahunLalu  = $tahun - 1;

        // 3. Penentuan Satker Selected
        $satkerSelected = ($userRole === 'admin' || $userActive->kode_satker === '1100') 
            ? $request->input('satker', '1100') 
            : $userActive->kode_satker;

        // 4. Inisialisasi Filter
        $kode2Selected    = $request->input('kode_2');
        $pilarSelected    = $request->input('pilar');
        $subpilarSelected = $request->input('subpilar');
        $filterTugasSaya  = $request->input('tugas_saya');
        $statusDokumen    = $request->input('status_dokumen');

        // Pecah string status dokumen menjadi array agar bisa menggunakan whereIn()
        $statusArray = $statusDokumen ? explode(',', $statusDokumen) : [];

        // 5. Query Builder Indikator
        $indikator = Jazirah2_Indikator::query()
            // --- A. Filter Kode 2 (Pemenuhan / Reform) ---
            ->when($kode2Selected, function ($query) use ($kode2Selected) {
                $query->where('kode_2', $kode2Selected);
            })

            // --- B. Filter Tugas Saya ---
            ->when($filterTugasSaya == '1' && !empty($username), function ($query) use ($username, $satkerSelected, $tahun) {
                $query->whereHas('isian', function ($q) use ($username, $satkerSelected, $tahun) {
                    $q->where('satker', $satkerSelected)
                    ->where('tahun', $tahun)
                    ->where(function($subQ) use ($username) {
                        $subQ->where('penanggungjawab', 'LIKE', "%{$username}%")
                            ->orWhere('created_by_3', 'LIKE', "%{$username}%");
                    });
                });
            })
            
            // --- C. Filter Pilar Utama ---
            ->when($pilarSelected, function ($query) use ($pilarSelected) {
                $query->where(function($sub) use ($pilarSelected) {
                    $sub->where('kode_3', $pilarSelected)->orWhere('level', 2);
                });
            })
            
            // --- D. Filter Sub-Pilar ---
            ->when($subpilarSelected, function ($query) use ($subpilarSelected, $pilarSelected) {
                $query->where(function($group) use ($subpilarSelected, $pilarSelected) {
                    $group->where('kode_4', $subpilarSelected)
                        ->orWhere(function($sub) use ($pilarSelected) {
                            $sub->where('level', 3)->where('kode_3', $pilarSelected);
                        })
                        ->orWhere('level', 2);
                });
            })

            // --- E. Filter Status Dokumen (Dari Rekap Dashboard) ---
            ->when(!empty($statusArray), function ($query) use ($statusArray, $satkerSelected, $tahun) {
                $query->whereHas('isian', function ($q) use ($statusArray, $satkerSelected, $tahun) {
                    $q->where('satker', $satkerSelected)
                    ->where('tahun', $tahun)
                    ->whereIn('status_dokumen', $statusArray);
                });
            })
            
            // --- F. Relasi & Eager Loading ---
            ->with(['isian' => function ($q) use ($satkerSelected, $tahun, $tahunLalu, $statusArray) {
                $q->where('satker', $satkerSelected)
                ->where('tahun', $tahun)
                ->select('jazirah2_hasil.*')
                
                // Memastikan data isian yang diload ke dalam relasi juga difilter berdasarkan status dokumen
                ->when(!empty($statusArray), function ($query) use ($statusArray) {
                    $query->whereIn('status_dokumen', $statusArray);
                })
                
                // Subquery untuk mapping nama bulan target
                ->selectRaw("(SELECT GROUP_CONCAT(b.singkatan ORDER BY CAST(b.kode_bulan AS UNSIGNED) SEPARATOR ', ') FROM bulan b WHERE FIND_IN_SET(b.kode_bulan, jazirah2_hasil.bulan_target)) AS bulan_target_nama")
                
                // Subquery untuk mapping nama bulan realisasi
                ->selectRaw("(SELECT GROUP_CONCAT(b.singkatan ORDER BY CAST(b.kode_bulan AS UNSIGNED) SEPARATOR ', ') FROM bulan b WHERE FIND_IN_SET(b.kode_bulan, jazirah2_hasil.bulan_realisasi)) AS bulan_realisasi_nama")
                
                // Subquery data tahun lalu (Rencana Aksi & Output)
                ->selectRaw("(SELECT prev.rencanaaksi FROM jazirah2_hasil prev WHERE TRIM(prev.satker) = TRIM(jazirah2_hasil.satker) AND prev.tahun = ? AND TRIM(prev.id_indikator) = TRIM(jazirah2_hasil.id_indikator) ORDER BY prev.id DESC LIMIT 1) AS rencanaaksi_tahun_lalu", [$tahunLalu])
                ->selectRaw("(SELECT prev.output FROM jazirah2_hasil prev WHERE TRIM(prev.satker) = TRIM(jazirah2_hasil.satker) AND prev.tahun = ? AND TRIM(prev.id_indikator) = TRIM(jazirah2_hasil.id_indikator) ORDER BY prev.id DESC LIMIT 1) AS output_tahun_lalu", [$tahunLalu])
                
                // Eager load history chat (komentar)
                ->with(['komentars' => function ($k) {
                    $k->orderBy('created_at', 'asc')->with('pegawai:nip_pegawai,name,urlfoto,username,jazirah');
                }]);
            }])
            ->get();

        // 6. Return Data Array
        $data = [
            'judul'                   => "New Jazirah",
            'id_judul'                => "3",
            'pilars'                  => ['I', 'II', 'III', 'IV', 'V', 'VI'],
            'user_active'             => $userActive,
            'data_subpilar'           => Jazirah2_Indikator::select('kode_3', 'kode_4', 'rencana_kerja', 'level')->where('level', 4)->get(),
            'satker_selected'         => $satkerSelected,
            'kode_2_selected'         => $kode2Selected, // Dikirim ke view untuk indikator filter aktif
            'pilar_selected'          => $pilarSelected,
            'subpilar_selected'       => $subpilarSelected,
            'tugas_saya_selected'     => $filterTugasSaya, 
            'status_dokumen_selected' => $statusDokumen, 
            'tahun'                   => $tahun,
            'satker'                  => Satker::select('kode_satker', 'nama_satker')->orderBy('kode_satker')->get(),
            'all_users'               => Cache::remember('all_users_grouped', 1440, fn() => DB::table('bupesta_user')->orderBy('name', 'asc')->get()->groupBy('kode_satker')),
            'indikator'               => $indikator,
        ];

        return view('jazirah.lembarkerja-jazirah', compact('data'));
    }

    public function updatelke(Request $request)
    {
        $validated = $request->validate([
            'id_isian'         => 'required',
            'penanggungjawab'  => 'nullable|string',
            'rencanaaksi'      => 'nullable|string',
            'output'           => 'nullable|string',
            'bulan_target'     => 'nullable|string',
            'bulan_realisasi'  => 'nullable|string',
            'link_buktidukung' => 'nullable|url',
        ]);

        // Mengambil data user yang sedang aktif
        $userActive = $this->getUserActive();
        $username = $userActive->username ?? null;

        $isBasicComplete = !empty($validated['penanggungjawab']) && 
                        !empty($validated['rencanaaksi']) && 
                        !empty($validated['output']) && 
                        !empty($validated['bulan_target']);
                        
        $isEvidenceComplete = !empty($validated['bulan_realisasi']) && 
                            !empty($validated['link_buktidukung']);

        $statusDokumen = 0;
        $additionalData = []; // Array untuk menampung data tracking

        if ($isBasicComplete && $isEvidenceComplete) {
            $statusDokumen = 2;
            $additionalData['created_by_1'] = $username;
            $additionalData['created_at_1'] = now();
            $additionalData['created_by_2'] = $username;
            $additionalData['created_at_2'] = now();
        } elseif ($isBasicComplete) {
            $statusDokumen = 1;
            $additionalData['created_by_1'] = $username;
            $additionalData['created_at_1'] = now();
            $additionalData['created_by_2'] = NULL;
            $additionalData['created_at_2'] = NULL;
        }

        $lke = Jazirah2_Hasil::findOrFail($validated['id_isian']);
        
        // Simpan id_isian untuk keperluan redirect
        $id_isian = $validated['id_isian'];
        
        // Hapus id_isian dari array agar tidak ikut di-update ke database
        unset($validated['id_isian']);
        
        // Menggabungkan hasil validasi, status dokumen, dan data tracking
        $updateData = array_merge($validated, ['status_dokumen' => $statusDokumen], $additionalData);
        $lke->update($updateData);

        return redirect()->back()
            ->with('success', 'Data LKE berhasil diperbarui.')
            ->with('updated_id', $id_isian);
    }

    public function storeKomentar(Request $request)
    {
        $request->validate([
            'id_jazirah2_hasil' => 'required|numeric',
            'komentar'          => 'required|string|max:1000',
        ]);

        $userActive = $this->getUserActive(); 
        $nip = $userActive->nip_pegawai ?? null;
        $username = $userActive->username ?? null;
        $role = $userActive->jazirah ?? null;

        if (!$nip) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak valid.'], 403);
        }

        $hasil = \App\Models\Jazirah2_Hasil::find($request->id_jazirah2_hasil);
        if (!$hasil) {
            return response()->json(['success' => false, 'message' => 'Dokumen tidak ditemukan.'], 404);
        }

        $rolesAllowed = ['admin', 'sekretariat', 'kepala', 'kepala-kako', 'kabag-umum', 'kasubbag', 'sekretariat-kako'];
        $array_pj = $hasil->penanggungjawab ? array_map('trim', explode(',', $hasil->penanggungjawab)) : [];
        $array_creator = $hasil->created_by_3 ? array_map('trim', explode(',', $hasil->created_by_3)) : [];

        $isEvaluator = in_array($username, $array_creator) || in_array($role, $rolesAllowed);
        $isPenanggungJawab = in_array($username, $array_pj);

        if (!$isEvaluator && !$isPenanggungJawab) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak. Anda tidak berhak membalas di diskusi ini.'], 403);
        }

        $komentar = \App\Models\Jazirah2_Komentar::create([
            'id_jazirah2_hasil' => $request->id_jazirah2_hasil,
            'nip'               => $nip,
            'komentar'          => $request->komentar,
        ]);

        if ($isPenanggungJawab) {
            $hasil->update([
                'status_dokumen'     => '4',
                'komentar_operator1' => $request->komentar,
                'created_by_4'       => $username,
                'created_at_4'       => now(),
            ]);
        } elseif ($isEvaluator) {
            $hasil->update([
                'status_dokumen'      => '3',
                'komentar_evaluator1' => $request->komentar,
                'created_by_3'        => $username,
                'created_at_3'        => now(),
                'created_by_4'        => NULL,
                'created_at_4'        => NULL
            ]);
        }

        // Relasi duplikat dihapus, langsung memanggil parameter relasi paling lengkap
        $komentar->load('pegawai:nip_pegawai,name,urlfoto,username,jazirah');

        return response()->json([
            'success'        => true,
            'data'           => $komentar,
            'status_dokumen' => $hasil->status_dokumen
        ]);
    }

    public function destroyKomentar($id)
    {
        $komentar = \App\Models\Jazirah2_Komentar::find($id);

        if (!$komentar) {
            return response()->json(['success' => false, 'message' => 'Pesan tidak ditemukan.'], 404);
        }

        $nip = $this->getUserActive()->nip_pegawai ?? null;

        if ($komentar->nip !== $nip) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak. Anda hanya bisa menghapus pesan Anda sendiri.'], 403);
        }

        $komentar->delete();

        return response()->json(['success' => true, 'message' => 'Pesan dihapus.']);
    }

    public function validasiDokumen(Request $request)
    {
        $request->validate(['id_jazirah2_hasil' => 'required|numeric']);

        $userActive = $this->getUserActive(); 
        $username = $userActive->username ?? null;
        $role = $userActive->jazirah ?? null;

        $hasil = \App\Models\Jazirah2_Hasil::find($request->id_jazirah2_hasil);
        if (!$hasil) {
            return response()->json(['success' => false, 'message' => 'Dokumen tidak ditemukan.'], 404);
        }

        $array_creator = $hasil->created_by_3 ? array_map('trim', explode(',', $hasil->created_by_3)) : [];
        
        if ($role !== 'admin' && !in_array($username, $array_creator)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak. Anda tidak berhak memvalidasi dokumen ini.'], 403);
        }

        // Tambahan update created_by_5 dan created_at_5
        $hasil->update([
            'status_dokumen' => '5',
            'created_by_5'   => $username,
            'created_at_5'   => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Dokumen berhasil divalidasi.']);
    }

    public function batalValidasiDokumen(Request $request)
    {
        $request->validate(['id_jazirah2_hasil' => 'required|numeric']);

        $userActive = $this->getUserActive(); 
        $role = $userActive->jazirah ?? null;
        $nip = $userActive->nip_pegawai ?? null;

        if (!in_array($role, ['admin', 'sekretariat'])) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak. Hanya Admin/Sekretariat yang bisa membatalkan validasi.'], 403);
        }

        if (!$nip) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak valid.'], 403);
        }

        $hasil = \App\Models\Jazirah2_Hasil::find($request->id_jazirah2_hasil);
        if (!$hasil) {
            return response()->json(['success' => false, 'message' => 'Dokumen tidak ditemukan.'], 404);
        }

        $hasil->update([
                'status_dokumen'      => '3',
                'created_by_4'        => NULL,
                'created_at_4'        => NULL,
                'created_by_5'        => NULL,
                'created_at_5'        => NULL
            ]);

        \App\Models\Jazirah2_Komentar::create([
            'id_jazirah2_hasil' => $request->id_jazirah2_hasil,
            'nip'               => $nip,
            'komentar'          => 'Validasi Dibatalkan',
        ]);

        return response()->json(['success' => true, 'message' => 'Validasi berhasil dibatalkan.']);
    }

    public function storeKritikSaran(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nip_pegawai' => 'required|string',
            'jenis'       => 'required|in:kritik,saran,masukan',
            'pesan'       => 'required|string|max:2500',
        ], [
            'nip_pegawai.required' => 'Identitas pegawai (NIP) tidak terdeteksi.',
            'jenis.required'       => 'Pilih jenis masukan terlebih dahulu.',
            'pesan.required'       => 'Isi pesan tidak boleh kosong.',
            'pesan.max'            => 'Pesan maksimal 2500 karakter.',
        ]);

        // 2. Simpan ke database menggunakan Model Jazirah_Kritiksaran
        Jazirah_Kritiksaran::create([
            'nip_pegawai' => $request->nip_pegawai,
            'jenis'       => $request->jenis,
            'pesan'       => $request->pesan,
        ]);

        // 3. Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Terima kasih! Kritik, saran, atau masukan Anda berhasil dikirim.');
    }
}