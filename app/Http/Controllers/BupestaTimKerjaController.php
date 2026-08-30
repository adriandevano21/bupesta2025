<?php

namespace App\Http\Controllers;

use App\Models\Bupesta_Kegiatan;
use App\Models\Bupesta_TimKerja;
use App\Models\UserActivity;
use App\Models\Bupesta_User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class BupestaTimKerjaController extends Controller
{
    /**
     * Mengambil data user yang sedang aktif.
     */
    private function getUserActive()
    {
        // --- MODE LOKAL (development) ---
        return Bupesta_User::where('nip_pegawai', '198605172008012002')->first();

        // --- MODE PRODUKSI (aktifkan baris ini & nonaktifkan baris di atas setelah deploy) ---
        // return Bupesta_User::where('nip_pegawai', auth()->user()->nip_pegawai)->first();
    }

    /**
     * Display a listing of the resource.
     */
    public function timkerja(Request $request)
    {
        UserActivity::log("https://bupesta.web.bps.go.id/timkerja");
        
        $tahun = $request->input('tahun', '2026');
        $data = [];

        // Menggunakan fungsi private getUserActive()
        $data["user_active"] = $this->getUserActive();
        $data["id_judul"]    = "2";
        $data["judul"]       = "Tim Kerja";

        // =========================================================================
        // 1. EAGER LOADING & MENGHAPUS QUERY GANDA
        // =========================================================================
        $data["tim_kerja"] = Bupesta_TimKerja::with(['kegiatan' => function ($query) use ($tahun) {
            $query->where('tahun_kegiatan', $tahun)
                  ->orderBy('nama_kegiatan', 'asc')
                  ->with('penanggungJawab');
        }])
        ->where('tahun', $tahun)
        ->get();

        // =========================================================================
        // 2. IMPLEMENTASI CACHING (1440 menit = 24 Jam)
        // =========================================================================
        $data['pegawai_prov'] = Cache::remember('pegawai_prov_1100', 1440, function () {
            return DB::table('bupesta_user')
                ->where('kode_satker', '1100')
                ->orderBy('name', 'asc')
                ->get();
        });

        $data['satkers'] = Cache::remember('satkers_all', 1440, function () {
            return DB::table('satkers')->orderBy('kode_Satker', 'asc')->get();
        });

        $data['all_users'] = Cache::remember('all_users_grouped', 1440, function () {
            return DB::table('bupesta_user')
                ->orderBy('name', 'asc')
                ->get()
                ->groupBy('kode_satker');
        });

        return view('bupesta.timkerja', compact('data'));
    }

    public function getDetailKegiatan($kode_kegiatan)
    {
        // 1. Ambil data kegiatan berdasarkan ID
        $kegiatan = Bupesta_Kegiatan::where('kode_kegiatan', $kode_kegiatan)->first();

        if ($kegiatan) {
            // 2. Kumpulkan NIP yang ada isinya
            $nips = [];
            $pjks = [
                '1100', '1101', '1102', '1103', '1104', '1105', '1106', '1107', 
                '1108', '1109', '1110', '1111', '1112', '1113', '1114', '1115', 
                '1116', '1117', '1118', '1171', '1172', '1173', '1174', '1175'
            ];

            foreach ($pjks as $kode) {
                $kolom = "pjk_" . $kode;
                if (!empty($kegiatan->$kolom)) {
                    $nips[] = $kegiatan->$kolom;
                }
            }

            // 3. Ambil data nama dari tabel User
            $users = DB::table('bupesta_user')
                ->whereIn('nip_pegawai', $nips)
                ->pluck('name', 'nip_pegawai')
                ->toArray(); 

            // 4. Titipkan kamus nama ke dalam respon JSON
            $kegiatan->nama_pegawai = $users;

            return response()->json($kegiatan);
        }

        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }

    public function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $tahun = $request->input('tahun', '2026');

                // --- 1. GENERATE KODE TIM KERJA ---
                $lastKode = Bupesta_TimKerja::where('tahun', $tahun)->max('kode_tim_kerja');
                $nextNumber = $lastKode ? ((int) substr($lastKode, -3)) + 1 : 101;
                $kodeTimKerjaBaru = $tahun . 's' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

                // --- 2. SIMPAN TIM KERJA BARU ---
                $timKerja = new Bupesta_TimKerja();
                $timKerja->kode_tim_kerja = $kodeTimKerjaBaru; 
                $timKerja->nama_tim_kerja = $request->nama_tim_kerja;
                $timKerja->nip_ketua_tim  = $request->nama_ketua;
                $timKerja->tahun          = $tahun;
                $timKerja->status         = 2;
                $timKerja->save();

                // --- 3. SIMPAN KEGIATAN BARU ---
                if ($request->has('nama_kegiatan_baru')) {
                    $kegiatanData = [];
                    $now = now();

                    foreach ($request->nama_kegiatan_baru as $index => $nama_kegiatan) {
                        if (!empty($nama_kegiatan)) {
                            $kegiatanData[] = [
                                'kode_kegiatan'  => Str::random(11),
                                'kode_tim_kerja' => $kodeTimKerjaBaru,
                                'nama_kegiatan'  => $nama_kegiatan,
                                'nip_pegawai'    => $request->nip_pegawai_baru[$index] ?? null,
                                'tahun_kegiatan' => $tahun,
                                'created_at'     => $now,
                                'updated_at'     => $now
                            ];
                        }
                    }

                    if (!empty($kegiatanData)) {
                        Bupesta_Kegiatan::insert($kegiatanData);
                    }
                }
            });

            return redirect()->back()->with('success', 'Tim Kerja berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                // 1. Update data utama Tim Kerja
                $timKerja = Bupesta_TimKerja::findOrFail($id);
                $timKerja->nama_tim_kerja = $request->nama_tim_kerja;
                $timKerja->nip_ketua_tim  = $request->nama_ketua;
                $timKerja->save();

                // 2. Menghapus Bupesta_Kegiatan yang dihapus user dari UI
                $submitted_ids = $request->id_kegiatan ?? [];
                Bupesta_Kegiatan::where('kode_tim_kerja', $id)
                    ->whereNotIn('kode_kegiatan', $submitted_ids)
                    ->delete();

                // 3. Update Bupesta_Kegiatan Lama
                if ($request->has('id_kegiatan')) {
                    foreach ($request->id_kegiatan as $index => $id_kegiatan) {
                        $kegiatan = Bupesta_Kegiatan::find($id_kegiatan);
                        if ($kegiatan && !empty($request->nama_kegiatan[$index])) {
                            $kegiatan->nama_kegiatan  = $request->nama_kegiatan[$index];
                            $kegiatan->pjk_1100       = $request->nip_pegawai[$index] ?? null;
                            $kegiatan->tahun_kegiatan = $timKerja->tahun;
                            $kegiatan->save();
                        }
                    }
                }

                // 4. Tambah Bupesta_Kegiatan Baru dari form Edit
                if ($request->has('nama_kegiatan_baru')) {
                    foreach ($request->nama_kegiatan_baru as $index => $nama_kegiatan) {
                        if (!empty($nama_kegiatan)) {
                            $kegiatan = new Bupesta_Kegiatan();
                            $kegiatan->kode_kegiatan  = Str::random(11);
                            $kegiatan->kode_tim_kerja = $id;
                            $kegiatan->nama_kegiatan  = $nama_kegiatan;
                            $kegiatan->pjk_1100       = $request->nip_pegawai_baru[$index] ?? null;
                            $kegiatan->tahun_kegiatan = $timKerja->tahun;
                            $kegiatan->save();
                        }
                    }
                }
            });

            return redirect()->back()->with('success', 'Tim Kerja dan kegiatan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateKetuaKabKota(Request $request)
    {
        $request->validate([
            'kode_tim_kerja' => 'required',
            'kode_satker'    => 'required|numeric',
            'nip_pegawai'    => 'nullable' 
        ]);

        try {
            $namaKolom = 'ketuatim_' . $request->kode_satker;

            DB::table('bupesta_timkerja')
                ->where('kode_tim_kerja', $request->kode_tim_kerja)
                ->update([$namaKolom => $request->nip_pegawai]);

            return response()->json([
                'success' => true,
                'message' => 'Ketua Tim berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePjk(Request $request)
    {
        try {
            // 1. Validasi Input
            $request->validate([
                'kode_kegiatan' => 'required',
                'kolom_pjk'     => 'required|string', 
                'nip_pegawai'   => 'nullable'         
            ]);

            // 2. Update HANYA kolom_pjk secara langsung
            $berhasil = Bupesta_Kegiatan::where('kode_kegiatan', $request->kode_kegiatan)
                ->update([$request->kolom_pjk => $request->nip_pegawai]);

            if ($berhasil === 0) {
                $cekData = Bupesta_Kegiatan::where('kode_kegiatan', $request->kode_kegiatan)->exists();
                if (!$cekData) {
                    return response()->json(['success' => false, 'message' => 'Data Kegiatan tidak ditemukan'], 404);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'PJK berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateInfoUmum(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'kode_kegiatan'            => 'required|string',
            'nama_kegiatan'            => 'required|string|max:150',
            'tahun_kegiatan'           => 'required|string|max:50',
            'detail_anggota_tim'       => 'nullable|array', 
            'detail_informasi_penting' => 'nullable|string',
            'detail_jadwal'            => 'nullable|string',
        ]);

        try {
            // 2. Cari Data Kegiatan
            $kegiatan = Bupesta_Kegiatan::where('kode_kegiatan', $request->kode_kegiatan)->firstOrFail();

            // 3. Gabungkan Array NIP
            $nipAnggotaTim = '';
            if ($request->has('detail_anggota_tim') && !empty($request->detail_anggota_tim)) {
                $nipAnggotaTim = implode(',', $request->detail_anggota_tim);
            }

            // 4. Lakukan Update Data
            $kegiatan->update([
                'nama_kegiatan'            => $request->nama_kegiatan,
                'tahun_kegiatan'           => $request->tahun_kegiatan,
                'detail_anggota_tim'       => $nipAnggotaTim,
                'detail_informasi_penting' => $request->detail_informasi_penting,
                'detail_jadwal'            => $request->detail_jadwal,
            ]);

            return redirect()->back()->with('success', 'Informasi Umum Kegiatan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}