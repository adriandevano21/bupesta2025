<?php

namespace App\Http\Controllers;

use App\Models\Bupesta_Kegiatan;
use App\Models\Bupesta_TimKerja;
use App\Models\UserActivity;
use App\Models\Bupesta_User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $data["user_active"] = Bupesta_User::where('nip_pegawai', '197405171996121001')->first();
        // dd($data["user_active"]);
        // $data["user_active"] = Bupesta_User::where('username', auth()->user()->username)->get();
        $data["id_judul"] = "1";
        $data["judul"] = "Tim Kerja";
        // Mengambil data tim kerja + join nama ketua + memuat relasi kegiatan
        $data["tim_kerja"] = Bupesta_TimKerja::select('bupesta_timkerja.*', 'bupesta_user.name as nama_ketua')
            ->leftJoin('bupesta_user', 'bupesta_timkerja.nip_ketua_tim', '=', 'bupesta_user.nip_pegawai')
            ->where('bupesta_timkerja.tahun', $tahun)
            // Memuat data kegiatan yang sesuai dengan tahun dan diurutkan berdasarkan nama
            ->with(['kegiatan' => function ($query) use ($tahun) {
                $query->where('tahun_kegiatan', $tahun)
                    ->orderBy('nama_kegiatan', 'asc');
            }])
            ->get();

        // dd($data);

        $data['pegawai_prov'] = DB::table('bupesta_user')
            ->where('kode_satker', '1100')
            ->get();

        // dd($data);
        return view('bupesta.timkerja', compact('data'));
    }

    public function store(Request $request)
    {
        // dd($request);

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
                // str_pad memastikan angkanya tetap 3 digit misal "001" kalau perlu, walau start 101 sudah aman
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
        // dd($request);
        try {
            DB::transaction(function () use ($request, $id) {

                // 1. Update data utama Tim Kerja
                $timKerja = Bupesta_TimKerja::findOrFail($id);
                // dd($timKerja);
                $timKerja->nama_tim_kerja = $request->nama_tim_kerja;
                $timKerja->nip_ketua_tim = $request->nama_ketua;
                // dd($timKerja);
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
}
