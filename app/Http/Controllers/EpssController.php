<?php

namespace App\Http\Controllers;

use App\Models\Bupesta_User;
use App\Models\UserActivity;
use App\Models\Epss_Kegiatan;
use App\Models\Epss_UsulanKegiatan;
use App\Models\Epss_TahapanKegiatan;
use App\Models\Epss_Nilai;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EpssController extends Controller
{
    private function getUserActive()
    {
        return Bupesta_User::where('nip_pegawai', '199906212022011001')->first();
        // return Bupesta_User::where('nip_pegawai', auth()->user()->nip_pegawai)->first();
    }

    public function index(Request $request)
    {
        UserActivity::log("https://bupesta.web.bps.go.id/epss");
        
        // 1. Parameter Utama dan Sorting
        $tahun = $request->input('tahun', date('Y'));
        $sortBy = $request->input('sort_by', 'nilai_epss');
        $sortOrder = $request->input('sort_order', 'desc');
        
        // Mengambil daftar tahun utama
        $list_tahun = DB::table('epss_kegiatan')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Mengambil daftar tahapan utama
        $list_tahapan = DB::table('epss_tahapan_kegiatan')
            ->join('epss_kegiatan', 'epss_tahapan_kegiatan.id_kegiatan', '=', 'epss_kegiatan.id')
            ->where('epss_kegiatan.tahun', $tahun)
            ->select('epss_tahapan_kegiatan.id', 'epss_tahapan_kegiatan.tahapan')
            ->distinct()
            ->get();

        // Set default tahapan ke tahapan pertama jika kosong
        $tahapan = $request->input('tahapan');
        if (empty($tahapan) && $list_tahapan->isNotEmpty()) {
            $tahapan = $list_tahapan->first()->id;
        }

        // 2. Parameter Pembanding
        $is_compare = $request->input('compare_aktif') == '1';
        $compare_tahun = $request->input('compare_tahun');
        $compare_tahapan = $request->input('compare_tahapan');
        
        $list_compare_tahapan = [];
        $compare_tahapan_nama = '';

        if ($is_compare && $compare_tahun) {
            $list_compare_tahapan = DB::table('epss_tahapan_kegiatan')
                ->join('epss_kegiatan', 'epss_tahapan_kegiatan.id_kegiatan', '=', 'epss_kegiatan.id')
                ->where('epss_kegiatan.tahun', $compare_tahun)
                ->select('epss_tahapan_kegiatan.id', 'epss_tahapan_kegiatan.tahapan')
                ->distinct()
                ->get();
                
            if ($compare_tahapan) {
                $ct = collect($list_compare_tahapan)->firstWhere('id', $compare_tahapan);
                if($ct) $compare_tahapan_nama = $ct->tahapan;
            }
        }

        // 3. Query Data Utama 
        $query = DB::table('epss_view_nilai_epss')
            ->join('epss_usulan_kegiatan', 'epss_view_nilai_epss.id_usulan_kegiatan', '=', 'epss_usulan_kegiatan.id')
            ->join('satkers', 'epss_usulan_kegiatan.kode_satker', '=', 'satkers.kode_Satker')
            ->join('epss_kegiatan', 'epss_view_nilai_epss.id_kegiatan', '=', 'epss_kegiatan.id')
            ->join('epss_tahapan_kegiatan', 'epss_view_nilai_epss.id_tahapan', '=', 'epss_tahapan_kegiatan.id')
            ->select(
                'epss_usulan_kegiatan.kode_satker', 
                'satkers.nama_satker', 
                'epss_tahapan_kegiatan.tahapan', 
                'epss_view_nilai_epss.nilai_epss'
            )
            ->where('epss_kegiatan.tahun', $tahun)
            ->where('epss_tahapan_kegiatan.id', $tahapan);

        // Pemetaan nama parameter Sort ke nama kolom tabel Database sebenarnya
        $dbSortColumns = [
            'kode_satker' => 'epss_usulan_kegiatan.kode_satker',
            'nama_satker' => 'satkers.nama_satker',
            'tahapan'     => 'epss_tahapan_kegiatan.tahapan',
            'nilai_epss'  => 'epss_view_nilai_epss.nilai_epss',
        ];

        // Eksekusi Sorting SQL jika parameter Sort masuk ke dalam array Database
        if (array_key_exists($sortBy, $dbSortColumns)) {
            $query->orderBy($dbSortColumns[$sortBy], $sortOrder);
        }

        $nilai_epss = $query->get();

        // 4. Query Data Pembanding (Mapping)
        if ($is_compare && $compare_tahun && $compare_tahapan) {
            $compare_data = DB::table('epss_view_nilai_epss')
                ->join('epss_usulan_kegiatan', 'epss_view_nilai_epss.id_usulan_kegiatan', '=', 'epss_usulan_kegiatan.id')
                ->join('epss_kegiatan', 'epss_view_nilai_epss.id_kegiatan', '=', 'epss_kegiatan.id')
                ->where('epss_kegiatan.tahun', $compare_tahun)
                ->where('epss_view_nilai_epss.id_tahapan', $compare_tahapan)
                ->pluck('epss_view_nilai_epss.nilai_epss', 'epss_usulan_kegiatan.kode_satker');
            
            $nilai_epss->map(function ($item) use ($compare_data) {
                $item->nilai_compare = $compare_data[$item->kode_satker] ?? null;
                $item->selisih = $item->nilai_compare !== null ? ($item->nilai_epss - $item->nilai_compare) : null;
                return $item;
            });

            // Eksekusi Sorting Laravel Collection khusus untuk kolom Pembanding & Selisih
            if (in_array($sortBy, ['nilai_compare', 'selisih'])) {
                $nilai_epss = $sortOrder === 'desc' 
                    ? $nilai_epss->sortByDesc($sortBy)->values() 
                    : $nilai_epss->sortBy($sortBy)->values();
            }
        }

        $data = [];
        $data["user_active"] = $this->getUserActive();
        $data["id_judul"] = "6"; 
        $data["judul"] = "EPSS";
        
        // Melempar variabel ke Blade
        $data["nilai_epss"] = $nilai_epss;
        $data["tahun_dipilih"] = $tahun;
        $data["tahapan_dipilih"] = $tahapan;
        $data["sort_by"] = $sortBy;
        $data["sort_order"] = $sortOrder;
        $data["list_tahun"] = $list_tahun;
        $data["list_tahapan"] = $list_tahapan;
        
        $data["is_compare"] = $is_compare;
        $data["compare_tahun"] = $compare_tahun;
        $data["compare_tahapan"] = $compare_tahapan;
        $data["list_compare_tahapan"] = $list_compare_tahapan;
        $data["compare_tahapan_nama"] = $compare_tahapan_nama;

        return view('epss.epss', compact('data'));
    }

    public function satkerView(Request $request)
    {
        // Dapatkan data user aktif 
        $user_active = $this->getUserActive(); 
        
        // Cek Hak Akses: Apakah user termasuk admin/level provinsi
        $hak_akses_bebas = in_array($user_active->epss, ['admin', 'pembina-prov', 'tpk-prov', 'tpb-prov']);

        // 1. Parameter Utama
        $tahun = $request->input('tahun', date('Y'));
        
        // Dropdown Tahun
        $list_tahun = DB::table('epss_kegiatan')->select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        // Dropdown Tahapan berdasarkan Tahun
        $list_tahapan = DB::table('epss_tahapan_kegiatan')
            ->join('epss_kegiatan', 'epss_tahapan_kegiatan.id_kegiatan', '=', 'epss_kegiatan.id')
            ->where('epss_kegiatan.tahun', $tahun)
            ->select('epss_tahapan_kegiatan.id', 'epss_tahapan_kegiatan.tahapan')
            ->distinct()->get();

        $tahapan = $request->input('tahapan');
        if (empty($tahapan) && $list_tahapan->isNotEmpty()) {
            $tahapan = $list_tahapan->first()->id;
        }

        // 2. Validasi Keamanan Akses Satker via URL Parameter
        $requested_satker = $request->input('kode_satker');
        
        if (!$hak_akses_bebas) {
            // Jika user biasa memaksa memasukkan kode_satker lain via URL
            if (!empty($requested_satker) && $requested_satker !== $user_active->kode_satker) {
                return redirect()->to(url()->current() . '?tahun=' . $tahun . '&tahapan=' . $tahapan)
                    ->with('error', 'Anda tidak memiliki akses untuk mengakses data satker tersebut.');
            }
            $kode_satker = $user_active->kode_satker;
        } else {
            $kode_satker = $requested_satker;
        }

        // 3. Query Dropdown Satker Berdasarkan Hak Akses
        $query_satker = DB::table('epss_usulan_kegiatan')
            ->join('satkers', 'epss_usulan_kegiatan.kode_satker', '=', 'satkers.kode_satker')
            ->join('epss_kegiatan', 'epss_usulan_kegiatan.id_kegiatan', '=', 'epss_kegiatan.id')
            ->where('epss_kegiatan.tahun', $tahun)
            ->select('satkers.kode_satker', 'satkers.nama_satker')
            ->distinct();

        if (!$hak_akses_bebas) {
            $query_satker->where('satkers.kode_satker', $user_active->kode_satker);
        }
        
        $list_satker = $query_satker->get();

        if (empty($kode_satker) && $list_satker->isNotEmpty()) {
            $kode_satker = $list_satker->first()->kode_satker;
        }

        // 4. Parameter Pembanding
        $is_compare = $request->input('compare_aktif') == '1';
        $compare_tahun = $request->input('compare_tahun');
        $compare_tahapan = $request->input('compare_tahapan');
        
        $list_compare_tahapan = [];
        $compare_tahapan_nama = '';

        if ($is_compare && $compare_tahun) {
            $list_compare_tahapan = DB::table('epss_tahapan_kegiatan')
                ->join('epss_kegiatan', 'epss_tahapan_kegiatan.id_kegiatan', '=', 'epss_kegiatan.id')
                ->where('epss_kegiatan.tahun', $compare_tahun)
                ->select('epss_tahapan_kegiatan.id', 'epss_tahapan_kegiatan.tahapan')
                ->distinct()->get();
                
            if ($compare_tahapan) {
                $ct = collect($list_compare_tahapan)->firstWhere('id', $compare_tahapan);
                if($ct) $compare_tahapan_nama = $ct->tahapan;
            }
        }

        // 5. Tarik Seluruh Referensi
        $referensi = DB::table('epss_referensi')->get()->keyBy('kode');

        // 6. Query Data Utama 
        $query = DB::table('epss_nilai')
            ->join('epss_view_nilai_peraspek', 'epss_nilai.id', '=', 'epss_view_nilai_peraspek.id')
            ->join('epss_view_nilai_perdomain', 'epss_nilai.id', '=', 'epss_view_nilai_perdomain.id')
            ->join('epss_view_nilai_epss', 'epss_nilai.id', '=', 'epss_view_nilai_epss.id')
            ->join('epss_usulan_kegiatan', 'epss_nilai.id_usulan_kegiatan', '=', 'epss_usulan_kegiatan.id')
            ->join('satkers', 'epss_usulan_kegiatan.kode_satker', '=', 'satkers.kode_satker')
            ->join('epss_kegiatan', 'epss_nilai.id_kegiatan', '=', 'epss_kegiatan.id')
            ->join('epss_tahapan_kegiatan', 'epss_nilai.id_tahapan', '=', 'epss_tahapan_kegiatan.id')
            ->select(
                'satkers.nama_satker', 'epss_usulan_kegiatan.kode_satker',
                'epss_kegiatan.tahun', 'epss_tahapan_kegiatan.tahapan',
                'epss_view_nilai_epss.nilai_epss',
                'epss_view_nilai_perdomain.*', 
                'epss_view_nilai_peraspek.*', 
                'epss_nilai.*' 
            )
            ->where('epss_kegiatan.tahun', $tahun)
            ->where('epss_tahapan_kegiatan.id', $tahapan)
            ->where('epss_usulan_kegiatan.kode_satker', $kode_satker);

        $data_utama = $query->first();

        // 7. Query Data Pembanding 
        $data_compare = null;
        if ($is_compare && $compare_tahun && $compare_tahapan && $data_utama) {
            $data_compare = DB::table('epss_nilai')
                ->join('epss_view_nilai_peraspek', 'epss_nilai.id', '=', 'epss_view_nilai_peraspek.id')
                ->join('epss_view_nilai_perdomain', 'epss_nilai.id', '=', 'epss_view_nilai_perdomain.id')
                ->join('epss_view_nilai_epss', 'epss_nilai.id', '=', 'epss_view_nilai_epss.id')
                ->join('epss_usulan_kegiatan', 'epss_nilai.id_usulan_kegiatan', '=', 'epss_usulan_kegiatan.id')
                ->join('epss_kegiatan', 'epss_nilai.id_kegiatan', '=', 'epss_kegiatan.id')
                ->where('epss_kegiatan.tahun', $compare_tahun)
                ->where('epss_nilai.id_tahapan', $compare_tahapan)
                ->where('epss_usulan_kegiatan.kode_satker', $kode_satker)
                ->select(
                    'epss_view_nilai_epss.nilai_epss',
                    'epss_view_nilai_perdomain.*',
                    'epss_view_nilai_peraspek.*',
                    'epss_nilai.*'
                )
                ->first();
        }

        $struktur = [
            '1' => ['101' => ['10101'], '102' => ['10201'], '103' => ['10301'], '104' => ['10401']],
            '2' => ['201' => ['20101', '20102'], '202' => ['20201'], '203' => ['20301', '20302'], '204' => ['20401', '20402', '20403'], '205' => ['20501', '20502']],
            '3' => ['301' => ['30101', '30102', '30103'], '302' => ['30201'], '303' => ['30301', '30302'], '304' => ['30401']],
            '4' => ['401' => ['40101', '40102', '40103', '40104'], '402' => ['40201', '40202'], '403' => ['40301', '40302', '40303', '40304']],
            '5' => ['501' => ['50101', '50102', '50103'], '502' => ['50201'], '503' => ['50301', '50302', '50303']]
        ];

        $data = [
            "user_active" => $user_active,
            "id_judul" => "6", 
            "judul" => "EPSS Satker Detail",
            "tahun_dipilih" => $tahun,
            "tahapan_dipilih" => $tahapan,
            "kode_satker_dipilih" => $kode_satker,
            "list_tahun" => $list_tahun,
            "list_tahapan" => $list_tahapan,
            "list_satker" => $list_satker,
            "is_compare" => $is_compare,
            "compare_tahun" => $compare_tahun,
            "compare_tahapan" => $compare_tahapan,
            "list_compare_tahapan" => $list_compare_tahapan,
            "compare_tahapan_nama" => $compare_tahapan_nama,
            "data_utama" => $data_utama,
            "data_compare" => $data_compare,
            "referensi" => $referensi,
            "struktur" => $struktur
        ];

        return view('epss.epsssatker', compact('data'));
    }

    // =========================================================================
    // KHUSUS HALAMAN ADMIN EPSS
    // =========================================================================
    public function adminepss(Request $request)
    {
        UserActivity::log("https://bupesta.web.bps.go.id/adminepss");

        $data = [];
        $data["user_active"] = $this->getUserActive();
        $data["id_judul"] = "4"; 
        $data["judul"] = "Admin EPSS";
        $data['satkers'] = \App\Models\Satker::all();
        // dd($data['satkers']);

        // Cek Role Admin
        $userRole = strtolower(trim($data["user_active"]->epss ?? ''));
        if ($userRole !== 'admin') {
            // Jika bukan admin, tendang kembali ke halaman index EPSS
            return redirect()->route('epss.index')->with('error', 'Akses ditolak! Anda bukan Admin EPSS.');
        }

        // Ambil Semua Data Tabel Untuk View CRUD
        $data['kegiatan'] = Epss_Kegiatan::all();
        $data['tahapan'] = Epss_TahapanKegiatan::all();
        $data['usulan'] = Epss_UsulanKegiatan::all();
        $data['nilai'] = Epss_Nilai::all();

        return view('epss.adminepss', compact('data'));
    }

    // --- CRUD KEGIATAN ---
    public function storeKegiatan(Request $request) {
        Epss_Kegiatan::create($request->except('_token'));
        return back()->with('success', 'Kegiatan berhasil ditambahkan');
    }
    public function updateKegiatan(Request $request, $id) {
        Epss_Kegiatan::find($id)->update($request->except('_token', '_method'));
        return back()->with('success', 'Kegiatan berhasil diupdate');
    }
    public function destroyKegiatan($id) {
        Epss_Kegiatan::destroy($id);
        return back()->with('success', 'Kegiatan berhasil dihapus');
    }

    // --- CRUD TAHAPAN ---
    public function storeTahapan(Request $request) {
        Epss_TahapanKegiatan::create($request->except('_token'));
        return back()->with('success', 'Tahapan berhasil ditambahkan');
    }
    public function updateTahapan(Request $request, $id) {
        Epss_TahapanKegiatan::find($id)->update($request->except('_token', '_method'));
        return back()->with('success', 'Tahapan berhasil diupdate');
    }
    public function destroyTahapan($id) {
        Epss_TahapanKegiatan::destroy($id);
        return back()->with('success', 'Tahapan berhasil dihapus');
    }

    // --- CRUD USULAN KEGIATAN ---
    public function storeUsulan(Request $request) {
        Epss_UsulanKegiatan::create($request->except('_token'));
        return back()->with('success', 'Usulan berhasil ditambahkan');
    }
    public function updateUsulan(Request $request, $id) {
        Epss_UsulanKegiatan::find($id)->update($request->except('_token', '_method'));
        return back()->with('success', 'Usulan berhasil diupdate');
    }
    public function destroyUsulan($id) {
        Epss_UsulanKegiatan::destroy($id);
        return back()->with('success', 'Usulan berhasil dihapus');
    }

    // --- CRUD NILAI ---
    public function storeNilai(Request $request) {
        Epss_Nilai::create($request->except('_token'));
        return back()->with('success', 'Nilai berhasil ditambahkan');
    }
    public function updateNilai(Request $request, $id) {
        Epss_Nilai::find($id)->update($request->except('_token', '_method'));
        return back()->with('success', 'Nilai berhasil diupdate');
    }
    public function destroyNilai($id) {
        Epss_Nilai::destroy($id);
        return back()->with('success', 'Nilai berhasil dihapus');
    }
}