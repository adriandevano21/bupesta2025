<?php

namespace App\Http\Controllers;

use App\Models\Bupesta_Kegiatan;
use App\Models\Bupesta_TimKerja;
use App\Models\UserActivity;
use App\Models\Bupesta_User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache; // Wajib ditambahkan untuk fitur Caching
use Illuminate\Support\Str;

class BupestaTimKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function timkerja(Request $request)
    {
        UserActivity::log("https://bupesta.web.bps.go.id/timkerja");
        $tahun = $request->input('tahun', '2026');
        $data = [];

        $data["user_active"] = Bupesta_User::where('nip_pegawai', '197307011995121001')->first();
        // $data["user_active"] = Bupesta_User::where('nip_pegawai', auth()->user()->nip_pegawai)->first();
        $data["id_judul"] = "1";
        $data["judul"] = "Tim Kerja";

        // =========================================================================
        // LANGKAH 2: EAGER LOADING & MENGHAPUS QUERY GANDA
        // =========================================================================
        // Query disatukan agar tidak eksekusi dua kali. Kita memuat Kegiatan beserta
        // relasi penanggungJawab-nya sekaligus (Mencegah N+1 Query Problem).
        $data["tim_kerja"] = Bupesta_TimKerja::with(['kegiatan' => function ($query) use ($tahun) {
            $query->where('tahun_kegiatan', $tahun)
                ->orderBy('nama_kegiatan', 'asc')
                ->with('penanggungJawab'); // Memuat PJK dari relasi model Kegiatan
        }])
            ->where('tahun', $tahun)
            ->get();


        // =========================================================================
        // LANGKAH 3: IMPLEMENTASI CACHING
        // =========================================================================
        // Data yang jarang berubah disimpan di RAM server (Cache) selama 1440 menit (24 Jam).
        // Database hanya akan dipanggil 1x dalam sehari untuk query ini!

        $data['pegawai_prov'] = Cache::remember('pegawai_prov_1100', 1440, function () {
            return DB::table('bupesta_user')
                ->where('kode_satker', '1100')
                ->get();
        });

        // Ambil data Satker untuk label PJK
        $data['satkers'] = Cache::remember('satkers_all', 1440, function () {
            return DB::table('satkers')->orderBy('kode_Satker', 'asc')->get();
        });

        // Ambil semua user dan kelompokkan berdasarkan kode_satker untuk dropdown
        $data['all_users'] = Cache::remember('all_users_grouped', 1440, function () {
            return \App\Models\Bupesta_User::all()->groupBy('kode_satker');
        });

        // dd($data['all_users']);

        return view('bupesta.timkerja', compact('data'));
    }

    public function getDetailKegiatan($kode_kegiatan)
    {
        // 1. Ambil data kegiatan berdasarkan ID
        $kegiatan = \App\Models\Bupesta_Kegiatan::where('kode_kegiatan', $kode_kegiatan)->first();

        if ($kegiatan) {
            // 2. Kumpulkan NIP yang ada isinya
            $nips = [];
            $pjks = ['1100', '1101', '1102', '1103', '1104', '1105', '1106', '1107', '1108', '1109', '1110', '1111', '1112', '1113', '1114', '1115', '1116', '1117', '1118', '1171', '1172', '1173', '1174', '1175'];

            foreach ($pjks as $kode) {
                $kolom = "pjk_" . $kode;
                if (!empty($kegiatan->$kolom)) {
                    $nips[] = $kegiatan->$kolom;
                }
            }

            // 3. Ambil data nama dari tabel User (Jika NIP kosong, tidak error)
            $users = DB::table('bupesta_user')
                ->whereIn('nip_pegawai', $nips)
                ->pluck('name', 'nip_pegawai')
                ->toArray(); // Pastikan jadi Array murni

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
                // Cari data terakhir di tahun tersebut berdasarkan kode_tim_kerja
                $lastTim = Bupesta_TimKerja::where('tahun', $tahun)
                    ->orderBy('kode_tim_kerja', 'desc')
                    ->first();

                if ($lastTim) {
                    // Ambil 3 karakter terakhir (misal dari "2026s101" diambil "101"), ubah ke integer, tambah 1
                    $lastNumber = (int) substr($lastTim->kode_tim_kerja, -3);
                    $nextNumber = $lastNumber + 1;
                } else {
                    // Jika belum ada data di tahun tersebut, mulai dari 101
                    $nextNumber = 101;
                }

                // Format kembali menjadi YYYYsXXX (contoh: 2026s102)
                $kodeTimKerjaBaru = $tahun . 's' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

                // --- 2. SIMPAN TIM KERJA BARU ---
                $timKerja = new Bupesta_TimKerja();
                $timKerja->kode_tim_kerja = $kodeTimKerjaBaru; // Gunakan kode hasil generate
                $timKerja->nama_tim_kerja = $request->nama_tim_kerja;
                $timKerja->nama_ketua = $request->nama_ketua;
                $timKerja->tahun = $tahun;
                $timKerja->save();

                // --- 3. SIMPAN KEGIATAN BARU ---
                if ($request->has('nama_kegiatan_baru')) {
                    foreach ($request->nama_kegiatan_baru as $index => $nama_kegiatan) {
                        if (!empty($nama_kegiatan)) {
                            $kegiatan = new Bupesta_Kegiatan();
                            // Generate random string 11 karakter untuk kode_kegiatan
                            $kegiatan->kode_kegiatan  = Str::random(11);
                            $kegiatan->kode_tim_kerja = $kodeTimKerjaBaru;
                            $kegiatan->nama_kegiatan  = $nama_kegiatan;
                            $kegiatan->nip_pegawai    = $request->nip_pegawai_baru[$index] ?? null;
                            $kegiatan->tahun_kegiatan = $tahun;
                            $kegiatan->save();
                        }
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
                $timKerja->nip_ketua_tim = $request->nama_ketua;
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
                            $kegiatan->nama_kegiatan = $request->nama_kegiatan[$index];
                            $kegiatan->pjk_1100 = $request->nip_pegawai[$index] ?? null;
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
                            // Generate random string 11 karakter untuk kegiatan yang baru ditambahkan
                            $kegiatan->kode_kegiatan  = Str::random(11);
                            $kegiatan->kode_tim_kerja = $id;
                            $kegiatan->nama_kegiatan  = $nama_kegiatan;
                            $kegiatan->pjk_1100    = $request->nip_pegawai_baru[$index] ?? null;
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

    public function updatePjk(Request $request)
    {
        // === DEBUGGING (Hapus garis miring ganda di bawah ini untuk mengaktifkan) ===
        // dd($request->all());
        // -------------------------------------------------------------------------
        // PENTING: Karena ini dikirim via AJAX/Fetch, hasil dd() TIDAK AKAN muncul di layar.
        // Cara melihatnya: Klik Kanan di browser -> Inspect -> tab "Network".
        // Lalu klik tombol "Simpan/Centang" di layar Anda, dan klik nama request "update-pjk"
        // yang muncul di tab Network tersebut. Hasil dd() ada di tab "Preview" atau "Response".

        try {
            // 1. Validasi Input
            $request->validate([
                'kode_kegiatan' => 'required',
                'kolom_pjk'     => 'required|string', // Contoh: pjk_1100
                'nip_pegawai'   => 'nullable'         // Bisa kosong jika PJK dihapus
            ]);

            // 2. Update HANYA kolom_pjk secara langsung
            // Cara ini jauh lebih presisi karena murni hanya mengeksekusi:
            // UPDATE bupesta_kegiatans SET pjk_1100 = 'nip...' WHERE kode_kegiatan = '...'
            $berhasil = \App\Models\Bupesta_Kegiatan::where('kode_kegiatan', $request->kode_kegiatan)
                ->update([
                    $request->kolom_pjk => $request->nip_pegawai
                ]);

            // Jika tidak ada baris yang terpengaruh (kode kegiatan salah/tidak ketemu)
            if ($berhasil === 0) {
                // Cek apakah datanya memang tidak ada
                $cekData = \App\Models\Bupesta_Kegiatan::where('kode_kegiatan', $request->kode_kegiatan)->exists();
                if (!$cekData) {
                    return response()->json(['success' => false, 'message' => 'Data Kegiatan tidak ditemukan'], 404);
                }
                // Jika datanya ada tapi $berhasil = 0, artinya datanya sama (tidak ada perubahan), kita anggap sukses saja.
            }

            return response()->json([
                'success' => true,
                'message' => 'PJK berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
